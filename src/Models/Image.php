<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 21:38
 */

namespace Upnp\Models;

use ActiveRecord\Model;

class Image extends Model
{
    static $table_name = 'images';
    static $belongs_to = [
        [
            'news',
            'className' => 'News',
            'class_name' => 'News',
            'foreign_key' => 'image_id'
        ]
    ];

    public function serialize(){
        return $this->to_array();
    }
}