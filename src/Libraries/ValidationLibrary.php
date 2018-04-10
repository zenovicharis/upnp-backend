<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/5/2018
 * Time: 7:46 PM
 */

namespace Upnp\Libraries;

use Valitron;

class ValidationLibrary extends Valitron\Validator
{
  //  private static NEWS_RULE = ['title', 'content', 'category', 'language'];
    private function NEWS_RULE(){
        return ['title', 'content', 'category', 'language'];
    }

    private function VOLOUNTIEER_RULE(){
        return ['ime_prezime', 'datum', 'adresa', 'grad', 'telefon', 'email', 'str_sprema', 'zanimanje', 'hobi', 'iskustvo', 'podrucje_rada', 'poslovi', 'nedeljni_sati', 'vreme', 'dodatna_obuka'];
    }

/*    private static VOLOUNTIEER_RULE = ['ime_prezime', 'datum', 'adresa', 'grad', 'telefon', 'email', 'str_sprema', 'zanimanje', 'hobi', 'iskustvo', 'podrucje_rada', 'poslovi', 'nedeljni_sati', 'vreme', 'dodatna_obuka'];*/

    public function __construct(array $data = array(), array $fields = array(), $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
    }

    public function newsRules($request)
    {
        $dataArray = $request->request->all();
        $val = $this->withData($dataArray);
        return $val->rule("required", $this->NEWS_RULE())
            ->message("'{field} is required'");
    }

    public function volountieerRules($request)
    {
        $dataArray = $request->request->all();
        $val = $this->withData($dataArray);
        return $val->rule("required", $this->VOLOUNTIEER_RULE())
            ->message("'{field} is required'");
    }
}