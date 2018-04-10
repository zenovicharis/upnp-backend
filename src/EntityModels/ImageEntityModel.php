<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 20:53
 */

namespace Upnp\EntityModels;


class ImageEntityModel
{
    public $id;
    public $deletehash;
    public $link;

    //public $album_id;

    public function __construct($id, $deletehash, $link/*, $album_id*/)
    {
        $this->id = $id;
        $this->deletehash = $deletehash;
        $this->link = $link;
        // $this->album_id = $album_id;
    }
}