<?php

use App\Models\User;
use Valitron\Validator;
use Slim\Psr7\UploadedFile;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

Validator::addRule('emailIsUnique', function($field, $value, $params, $fields) {
    $user = User::where('email', $value)
        ->where('email', '!=', Sentinel::check()->email)
        ->first();

    return $user ? false : true; 
}, 'Указанная {field} уже используется');

Validator::addRule('currentPassword', function($field, $value, $params, $fields) {
    return Sentinel::getUserRepository()->validateCredentials(
        Sentinel::check(),
        ['password' => $value]
    );
}, 'Указан неверный {field}');

Validator::addRule('photoRequired', function($field, $value, $params, $fields) {
    return $value->getSize() ? true : false;
});

Validator::addRule('photoFormat', function($field, $value, $params, $fields) {
    if (!$value->getSize()) {
        return true;
    }
    
    $mime = $value->getClientMediaType();
    
    if ($mime != 'image/jpeg' && $mime != 'image/png') {
        return false;
    }
    
    return true;
});

Validator::addRule('currentPassword', function($field, $value, $params, $fields) {
    return Sentinel::getUserRepository()->validateCredentials(
        Sentinel::check(),
        ['password' => $value]
    );
}, 'указан не верно');