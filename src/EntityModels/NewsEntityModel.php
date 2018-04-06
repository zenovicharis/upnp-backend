<?php

namespace Upnp\EntityModels;


class NewsEntityModel
{
    public $title;
    public $content;
    public $category;
    public $image_id;
    public $language;

    public function __construct($title, $content,$category, $image_id, $language)
    {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->image_id = $image_id;
        $this->language = $language;
    }
}