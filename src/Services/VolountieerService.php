<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 6.4.18
 * Time: 21:48
 */

namespace Upnp\Services;

use Upnp\EntityModels\VolountieerEntityModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class VolountieerService
{
    public function __construct()
    {
    }

    public function createVolountieer(VolountieerEntityModel $entityModel){
        try{
            var_dump($entityModel);die();
            $volountieer = Volountieer::create([
                "ime_prezime" => $entityModel->ime_prezime,
                "datum" => $entityModel->datum,
                "adresa" => $entityModel->adresa,
                "grad" => $entityModel->grad,
                "telefon" => $entityModel->telefon,
                "email" => $entityModel->email,
                "str_sprema" => $entityModel->str_sprema,
                "zanimanje" => $entityModel->zanimanje,
                "hobi" => $entityModel->hobi,
                "iskustvo" => $entityModel->iskustvo,
                "podrucje_rada" => $entityModel->podrucje_rada,
                "poslovi" => $entityModel->poslovi,
                "nedeljni_sati" => $entityModel->nedeljni_sati,
                "vreme" => $entityModel->vreme,
                "dodatna_obuka" => $entityModel->dodatna_obuka
            ]);
            return (int)$volountieer->id;
        } catch (Exception $e){
            return false;
        }
    }

    public function getValountieers(){
        try{
            $volountieer = Volountieer::all()->get();
        } catch (Exception $e) {
            var_dump($e->getMessage());die();
        }

    }


}