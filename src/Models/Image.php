<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 21:38
 */

namespace Upnp\Models;

//use ActiveRecord\Model;
use Upnp\EntityModels\ImageEntityModel;
use \Illuminate\Database\Eloquent\Model;
class Image extends Model
{
    protected $table = "images";
    protected $fillable = ["imgur_id" , "delete_hash", "url"];
//    protected $guarded = ["updated_at", "created_at"];
    public $timestamps = false;

    public function news()
    {
        return $this->hasOne('Upnp\Models\News', 'image_id', 'id');
    }

//    public function serialize(){
//        return $this->to_array();
//    }
}