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
    protected $fillable = ["imgur_id", "delete_hash", "url", "album_id"];
//    protected $guarded = ["updated_at", "created_at"];
    public $timestamps = false;

    public function news()
    {
        return $this->hasOne('Upnp\Models\News', 'image_id', 'id');
    }

    public function albums()
    {
        return $this->belongsTo('Upnp\Models\Album', 'album_id')->withDefault([
            'name' => 'album',
        ]);
    }

//    public function serialize(){
//        return $this->to_array();
//    }
}