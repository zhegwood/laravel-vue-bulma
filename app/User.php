<?php

namespace App;

use Auth;
use DB;
use Hash;
use Mail;
use View;
use App\Mail\ActivationEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'active', 'active_hash', 'active_hash_expires', 'remember_token', 'last_login', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $appends = ['full_name'];



    /**
     * Error Handling
     */
    
    protected $errors = [];

    private function addError($error) {
        $this->errors[] = $error;
    }

    public function getErrors($as_array = false) {
        if ($as_array) {
            return $this->errors;
        }
        return implode("<br/>",$this->errors);
    }


    /**
     * Attributes
     */

    public function getFullNameAttribute() {
        return $this->first_name." ".$this->last_name;
    }

    /**
     * Public Functions
     */
    public function activate() {
        if (strtotime($this->active_hash_expires) > strtotime(gmdate('Y-m-d H:i:s'))) {
            $this->active = 'Y';
            $this->updated_at = gmdate('Y-m-d H:i:s');
            $this->deleted_at = null;
            return $this->save();
        }
        $this->addError("The link has expired.  Please resend the activation email.");
        return false;
    }
    public function exists() {
        $count = $this::where('username',request()->input('username'))->count();
        return $count > 0;
    }
    public function inactivate() {
        $this->active = "N";
        $this->updated_at = gmdate('Y-m-d H:i:s');
        return $this->save();
    }
    public function login() {
        if ($this->active === 'N') {
            $this->addError("User is not active.");
            return false;
        }
        $request = request();
        $credentials = ['username' => $request->username,'password'=>$request->input('password')];
        $remember = $request->input('remember',false);
        if (Auth::attempt($credentials, $remember)) {
            $this->last_login = gmdate('Y-m-d H:i:s');
            $this->updated_at = gmdate('Y-m-d H:i:s');
            return $this->save();
        }
        $this->addError("Invalid username or password.");
        return false;
    }
    public function register() {
        $request = request();
        $this->first_name = $request->input('first_name',null);
        $this->last_name = $request->input('last_name',null);
        $this->email_address = mb_strtolower($request->input('email_address',''),'UTF-8');
        $this->username = $request->input('username','');
        $this->password = $request->input('password',null);
        if ($this->validUser()) {
            DB::beginTransaction();
            $now = gmdate('Y-m-d H:i:s');
            $this->password = Hash::make($request->input('password'));
            $this->active_hash = md5($now);
            $this->active_hash_expires = gmdate('Y-m-d H:i:s', strtotime("+4 hours", strtotime($now)));
            $this->updated_at = $now;
            $this->created_at = $now;
            if (!$this->save()) {
                return false;
            }

            if ($this->sendActivationEmail()) {
                DB::commit();
                return true;
            }
            DB::rollback();
        }
        return false;
    }
    public function resendActivationEmail() {
        $now = gmdate("Y-m-d H:i:s");
        $this->active_hash = md5($now);
        $this->active_hash_expires = gmdate('Y-m-d H:i:s', strtotime("+4 hours", strtotime($now)));
        if ($this->save()) {
            if ($this->sendActivationEmail()) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function saveUser() {
        $request = request();
        $password_change = $request->input('password',null) !== null;
        $this->first_name = $request->input('first_name',$this->first_name);
        $this->last_name = $request->input('last_name',$this->last_name);
        $this->email_address = $request->input('email_address',$this->email_address);
        $this->password = $password_change ? $request->input('password') : $this->password;
        if ($this->validUser($password_change)) {
            if ($password_change) {
                $this->password = Hash::make($request->input('password'));
            }
            $now = gmdate('Y-m-d H:i:s');
            $this->updated_at = $now;
            return $this->save();
        }
        return false;
    }
    public function sendActivationEmail() {
        $data = [
            "link" => url('/activate/' . $this->active_hash),
        ];
        Mail::to($this->email_address)->send(new ActivationEmail($data));
        return true;
    }



    /**
     * Private functions
     */
    private function validUser($password_change = true) {
        $request = request();
        if (empty($this->first_name) || $this->first_name === "") {
            $this->addError("First Name is required.");
            return false;
        }
        if (empty($this->last_name) || $this->last_name === "") {
            $this->addError("Last Name is required.");
            return false;
        }
        if (empty($this->email_address) || $this->email_address === "") {
            $this->addError("Email Address is required.");
            return false;
        }
        if (!filter_var($this->email_address, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Email Address is invalid.");
            return false;
        }
        if (empty($this->username) || $this->username === "") {
            $this->addError("Username is required.");
            return false;
        }
        if ($this->exists($this->username)) {
            $this->addError('Username is taken.');
            return false;
        }
        if ($password_change) {
            if (empty($this->password)) {
                $this->addError("Password is required.");
                return false;
            }
            if ($this->password !== $request->input('password_confirm')) {
                $this->addError('Passwords do not match.');
                return false;
            }
        }
        if (!$request->input('agree')) {
            $this->addError('You must agree to the Terms of Service.');
            return false;
        }
        return true;
    }
}