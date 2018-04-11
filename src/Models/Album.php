<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/6/2018
 * Time: 11:59 PM
 */

namespace Upnp\Models;

//use ActiveRecord\Model;
use Upnp\EntityModels\ImageEntityModel;
use \Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = "albums";
    protected $fillable = ["title", "english_title"];
//    protected $guarded = ["updated_at", "created_at"];
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany('Upnp\Models\Image', 'album_id', 'id');
    }

    public static function get_album_with_images()
    {

        $albums = Album::with('images')->get()->toArray();
        return $albums;
    }

}