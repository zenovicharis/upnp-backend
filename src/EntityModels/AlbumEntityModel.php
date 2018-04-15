<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 14.4.18
 * Time: 21:51
 */

namespace Upnp\EntityModels;


class AlbumEntityModel
{
    public $title;
    public $english_title;
    public $images;

    public function __construct($title = '', $english_title = '', $images = [])
    {
        $this->title = $title;
        $this->english_title = $english_title;
        $this->images = $images;
    }
}