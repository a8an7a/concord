<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'birthday',
        'upload',
        'permissions',
    ];

    public function fullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getBirthday()
    {
        return date('d.m.Y', strtotime($this->birthday));
    }

    public function getAge()
    {
        $birthday = strtotime($this->birthday);
        $age = date('Y') - date('Y', $birthday);

        return date('md', $birthday) > date('md') ? $age - 1 : $age;
    }

    public function getUpload()
    {
        return $this->upload;
    }
}