<?php

namespace Upnp\EntityModels;


class NewsEntityModel
{
    public $title;
    public $content;
    public $category;
    public $image;
    public $language;

    public function __construct($title, $content,$category, $image, $language)
    {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->image = $image;
        $this->language = $language;
    }
}