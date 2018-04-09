<?php

namespace Upnp\Services;

use Upnp\Models\Album;
use Upnp\EntityModels\AlbumEntityModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class AlbumService
{
    public function createAlbum(AlbumEntityModel $entityModel){
        try{
            $album = Album::create([
                "title" => $entityModel->title
            ]);
            return $album;
        } catch (Exception $e){
            return $e;
        }
    }

    public function readAlbumswithImages(){
        try{
            /** @var Album[] $albums */
            $albums = Album::get_album_with_images();
            return $albums;
        } catch (Exception $e){
            var_dump($e->getMessage());die();
        }
    }

    public function readAlbums(){
        try{
            /** @var Album[] $albums */
            $albums = Album::all()->toArray();
            return $albums;
        } catch (Exception $e){
            var_dump($e->getMessage());die();
        }
    }
}