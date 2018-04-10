<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/10/2018
 * Time: 10:10 PM
 */

namespace Upnp\Services;

use Upnp\Models\News;
use Upnp\Models\Album;
use Symfony\Component\Config\Definition\Exception\Exception;

class PublicService
{
    public function getNews()
    {
        try {
            /** @var News[] $news */
            $news = News::with('images')->get()->toArray();
            return $news;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function getAlbums()
    {
        try {
            /** @var Album[] $albums */
            $albums = Album::with('images')->get()->toArray();
            return $albums;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}