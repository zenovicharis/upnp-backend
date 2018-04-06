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
   // public $imgurId;
    public $deletehash;
    public $link;

    public function __construct($id, $deletehash, $link)
    {
        $this->id = $id;
       // $this->imgurId = $imgurId;
        $this->deletehash= $deletehash;
        $this->link = $link;
    }
}