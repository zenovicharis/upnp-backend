<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 14:15
 */

namespace Upnp\Middleware;

use Upnp\Application;
use Symfony\Component\HttpFoundation\Request;

class Authentication
{
    public function __construct(){
        session_start();
    }

    public function isLoggedIn(){
        if (!isset($_SESSION['user'])) {
            return false;
        } else {
            return true;
        }
    }

    public function checkCredentials(Application $app, Request $request){
        $email = $request->request->get('email');
        return $app['userService']->getUserByEmail($email);
    }

}