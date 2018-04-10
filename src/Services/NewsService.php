<?php

namespace Upnp\Services;

use Illuminate\Support\Facades\DB;
use Upnp\Models\News;
use Upnp\Models\Image;
use Upnp\Models\Volountieer;
use Upnp\EntityModels\NewsEntityModel;
use Upnp\EntityModels\VolountieerEntityModel;
use Upnp\EntityModels\ImageEntityModel;
use \Illuminate\Database\Eloquent;
use Symfony\Component\Config\Definition\Exception\Exception;

class NewsService
{
    public function __construct()
    {
    }

    public function createNews(NewsEntityModel $entityModel)
    {
        try {
            $news = News::create([
                "title" => $entityModel->title,
                "content" => $entityModel->content,
                "image_id" => $entityModel->image_id,
                "category" => $entityModel->category,
                "language" => $entityModel->language
            ]);
            return (int)$news->id;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createVolountieer(VolountieerEntityModel $entityModel)
    {
        try {
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
        } catch (Exception $e) {
            return false;
        }
    }

    public function createImage(ImageEntityModel $imageObj, $createdAlbumObject = null)
    {
        try {
            $image = Image::create([
                "imgur_id" => $imageObj->id,
                "delete_hash" => $imageObj->deletehash,
                "url" => $imageObj->link,
                "album_id" => $createdAlbumObject
            ]);
            return $image;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function readNews()
    {
        try {
            /** @var News[] $news */
            $news = News::get_images_with_news();
            return $news;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function NewsById($id)
    {
        try {
            $news = News::where('id', $id)->with('images')->get()->toArray();
            //$news = News::find($id)->with('images')->get()->toArray();
            return $news;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function readVolountieers()
    {
        try {
            $volountieers = Volountieer::find('all');
            $volountieersInArray = $this->toNewsArray($volountieers);
            return $volountieersInArray;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteNews($id)
    {
        try {
            $news = News::find($id);
            $news->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateNews(NewsEntityModel $entityModel, $id)
    {
        try {
            $news = News::find($id)->update([
                "title" => $entityModel->title,
                "content" => $entityModel->content,
                "category" => $entityModel->category,
                "language" => $entityModel->language
            ]);
            return $news;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
            return false;
        }
    }

//    protected function toNewsArray($news){
//        $array = array();
//        foreach($news as $new){
//            $array[] = $new->to_array();
//        }
//        return $array;
//    }
}