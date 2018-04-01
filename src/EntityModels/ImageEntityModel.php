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
    public $imgurId;
    public $deleteHash;
    public $url;

    public function __construct($id, $deleteHash,$url)
    {
        $this->$imgurId = $id;
        $this->deleteHash= $deleteHash;
        $this->url = $url;
    }
}