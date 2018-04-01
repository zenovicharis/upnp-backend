<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 15:04
 */

namespace Upnp\Services;

use Upnp\Models\User;

class UserService
{
    public function __construct()
    {
    }

    public function getUserByEmail($email){
        try{
            $user = User::find_by_email($email);
            return $user;
        } catch (Exception $e){
            return false;
        }
    }

}