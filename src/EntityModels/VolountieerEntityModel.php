<?php

namespace Upnp\EntityModels;


class VolountieerEntityModel
{
    public $ime_prezime;
    public $datum;
    public $adresa;
    public $grad;
    public $telefon;
    public $email;
    public $str_sprema;
    public $zanimanje;
    public $hobi;
    public $iskustvo;
    public $podrucje_rada;
    public $poslovi;
    public $nedeljni_sati;
    public $vreme;
    public $dodatna_obuka;

    public function __construct($ime_prezime, $datum, $adresa, $grad, $telefon, $email, $str_sprema, $zanimanje, $hobi, $iskustvo, $podrucje_rada, $poslovi, $nedeljni_sati, $vreme, $dodatna_obuka)
    {
        $this->ime_prezime = $ime_prezime;
        $this->datum = $datum;
        $this->adresa = $adresa;
        $this->grad = $grad;
        $this->telefon = $telefon;
        $this->email = $email;
        $this->str_sprema = $str_sprema;
        $this->zanimanje = $zanimanje;
        $this->hobi = $hobi;
        $this->iskustvo = $iskustvo;
        $this->podrucje_rada = $podrucje_rada;
        $this->poslovi = $poslovi;
        $this->nedeljni_sati = $nedeljni_sati;
        $this->vreme = $vreme;
        $this->dodatna_obuka = $dodatna_obuka;
    }
}