<?php

//No Auth Page Routes
Route::get('/','NoAuthPages@index');
Route::get('/login','NoAuthPages@login');
Route::get('/register','NoAuthPages@register');
Route::get('/activate/{hash}','NoAuthPages@activate');
Route::get('/resend','NoAuthPages@resend');


//Auth Page Routes
Route::get('/app','AuthPages@authHome');
Route::get('/logout','AuthPages@logout');
Route::get('/user-settings','AuthPages@userSettings');


//No Auth API Routes
Route::get('/api/auth-user/get','NoAuthApi@authUserGet');
Route::post('/api/tos/get','NoAuthApi@tosGet');
Route::post('/api/user/exists','NoAuthApi@userExists');
Route::post('/api/user/login','NoAuthApi@userLogin');
Route::post('/api/user/register','NoAuthApi@userRegister');
Route::post('/api/activation/resend','NoAuthApi@activationResend');


//Auth API Routes
Route::get('/api/user/inactivate','AuthApi@userInactivate');
Route::get('/api/user/logout','AuthApi@userLogout');
Route::post('/api/user/save','AuthApi@userSave');