<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 21:38
 */

namespace Upnp\Models;


class Image
{
    static $table_name = 'images';

    public function serialize(){
        return $this->to_array();
    }
}