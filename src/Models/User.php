<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 15:04
 */

namespace Upnp\Models;

use ActiveRecord\Model;

class User extends Model
{
    static $table_name = 'user';

    public function serialize()
    {
        return $this->to_array();
    }
}
