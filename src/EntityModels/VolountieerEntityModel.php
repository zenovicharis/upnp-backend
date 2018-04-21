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
        $this->hobi = $hobi;
        $this->grad = $grad;
        $this->email = $email;
        $this->datum = $datum;
        $this->adresa = $adresa;
        $this->telefon = $telefon;
        $this->iskustvo = $iskustvo;
        $this->zanimanje = $zanimanje;
        $this->str_sprema = $str_sprema;
        $this->ime_prezime = $ime_prezime;
        $this->dodatna_obuka = $dodatna_obuka;
        $this->vreme = $vreme[0];
        $this->poslovi = $poslovi[0];
        $this->podrucje_rada = $podrucje_rada[0];
        $this->nedeljni_sati = $nedeljni_sati[0];
    }
}