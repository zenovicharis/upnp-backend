<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 15:04
 */

namespace Upnp\Services;
use Symfony\Component\Config\Definition\Exception\Exception;

use Upnp\Models\User;

class UserService
{
    public function __construct()
    {
    }

    public function getUserByEmail($email){

        try{
            $user = User::where('email', $email)->first();
            return $user;
        } catch (Exception $e){
            return false;
        }
    }

}