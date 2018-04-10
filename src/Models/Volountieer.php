<?php

namespace Upnp\Models;

//use ActiveRecord\Model;
use \Illuminate\Database\Eloquent\Model;
class Volountieer extends Model
{
    protected $table = 'volountieer';


    protected $fillable = ["ime_prezime" , "datum", "adresa", "grad", "telefon", "email", "str_sprema", "zanimanje",
        "hobi", "iskustvo", "podrucje_rada", "poslovi" ,"nedeljni_sati" , "vreme" , "dodatna_obuka"];

    public $timestamps = false;

}