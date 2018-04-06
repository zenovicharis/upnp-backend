<?php

namespace Upnp\Models;

use ActiveRecord\Model;

class News extends Model
{
    static $table_name = 'news';
    static $has_only = [
        [
            'images',
            'className' => 'Image',
            'class_name' => 'Image'
        ]
    ];

    public function serialize(){
        return $this->to_array([
            'include' =>
                ['images']
        ]);
    }
} //images