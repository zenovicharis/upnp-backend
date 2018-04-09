<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/8/2018
 * Time: 5:33 PM
 */

namespace Upnp\EntityModels;

class AlbumEntityModel
{
    public $title;
    public function __construct($title)
    {
        $this->title = $title;
    }
}