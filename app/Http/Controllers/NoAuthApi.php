<?php
namespace App\Http\Controllers;

use App\TermsOfService;
use App\User;

class NoAuthApi extends Controller
{
    public function activationResend() {
        $response = $this->getResponse(true);
        $U = User::where('email_address',request()->input('email_address'))->first();
        if (!empty($U)) {
            $U->resendActivationEmail();
        }
        return response()->json($response);
    }
    public function authUserGet()
    {
        $response = $this->getResponse(true);
        $response->data->auth_user = auth()->check() ? auth()->user() : null;
        return response()->json($response);
    }
    public function tosGet() {
        $response = $this->getResponse();
        $type = request()->input('tos_type');
        if ($tos = TermsOfService::where('name',$type)->first()) {
            $response->success = true;
            $response->data->tos = $tos->text;
        } else {
            $response->error = "Terms of Service not found.";
        }
        return response()->json($response);
    }
    public function userExists() {
        $response = $this->getResponse();
        $U = new User();
        if ($U->exists()) {
            $response->error = "Username is taken.";
        }
        return response()->json($response);
    }
    public function userLogin() {
        $response = $this->getResponse();
        $U = User::where('username',mb_strtolower(request()->input('username'),'UTF-8'))->first();
        if (empty($U)) {
            $response->error = "Invalid username or password.";
        } else if ($U->login()) {
            $response->success = true;
        } else {
            $response->error = $U->getErrors();
        }
        return response()->json($response);
    }
    public function userRegister() {
        $response = $this->getResponse();
        $U = new User();
        if ($U->register()) {
            $response->success = true;
        } else {
            $response->error = $U->getErrors();
        }
        return response()->json($response);
    }
}