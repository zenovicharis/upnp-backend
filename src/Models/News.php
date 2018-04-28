<?php

namespace Upnp\Models;

//use ActiveRecord\Model;


use \Illuminate\Database\Eloquent\Model;


class News extends Model
{
    protected $table = "news";

    protected $fillable = ["title", "content", "category", "language", "image_id"];
    public $timestamps = false;

    public function images()
    {
        return $this->belongsTo('Upnp\Models\Image', 'image_id')->withDefault([
            'name' => 'image',
        ]);
    }

    public static function get_images_with_news()
    {

        $news = News::orderByDesc('created')->where('category', 'vest')->with('images')->get()->toArray();
        return $news;
    }

    public static function get_images_with_projects()
    {

        $projects = News::orderByDesc('created')->where('category', 'projekt')->with('images')->get()->toArray();
        return $projects;
    }


    public static function get_news_with_id($id)
    {

        $news = News::with('images')->find($id);
        return $news;
    }
//    static $table_name = 'news';
//    static $has_only = [
//        [
//            'image',
//            'className' => 'Image',
//            'class_name' => 'Image',
//            'foreign_key' => 'image_id'
//        ]
//    ];
//
//    public function serialize(){
//        return $this->to_array([
//            'include' =>
//                ['images']
//        ]);
//    }
} //images