<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

class AuthApi extends Controller
{
    public function __construct()
    {
        $this->middleware('authenticated');
    }
    //
    public function userInactivate() {
        $response = $this->getResponse();
        $user = request()->user();
        if ($user->inactivate()) {
            Auth::logout();
            $response->success = true;
        } else {
            $response->error = $user->getErrors();
        }
        return response()->json($response);
    }
    public function userLogout() {
        $response = $this->getResponse(true);
        Auth::logout();
        return response()->json($response);
    }
    public function userSave() {
        $response = $this->getResponse();
        $user = request()->user();
        if ($user->saveUser()) {
            $response->success = true;
        } else {
            $response->error = $user->getErrors();
        }
        return response()->json($response);
    }
}