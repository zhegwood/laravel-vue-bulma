<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthPages extends Controller
{
    public function __construct()
    {
        $this->middleware('authenticated');
    }

    public function authHome() {
        return view('auth.auth_home');
    }
    
    public function userSettings() {
        return view('auth.user_settings');
    }
}