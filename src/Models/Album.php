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
    protected $fillable = ["title"];
//    protected $guarded = ["updated_at", "created_at"];
    public $timestamps = false;

    public function images()
    {
        return $this->hasOne('Upnp\Models\Image', 'image_id', 'id');
    }

//    public function serialize(){
//        return $this->to_array();
//    }
}