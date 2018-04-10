<?php

namespace Upnp\Services;

use Upnp\Models\Album;
use Upnp\Models\Image;
use Upnp\EntityModels\AlbumEntityModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class AlbumService
{
    public function createAlbum($title)
    {
        try {
            $album = Album::create([
                "title" => $title
            ]);
            return $album;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updateAlbum($title, $id)
    {
        try {
            $album = Album::find($id)->update([
                "title" => $title
            ]);
            return $album;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function readAlbumswithImages()
    {
        try {
            /** @var Album[] $albums */
            $albums = Album::get_album_with_images();
            return $albums;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function readAlbumById($id)
    {
        try {
            /** @var Album[] $albums */
            $albums = Album::with('images')->find($id)->toArray();
            return $albums;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function readAlbums()
    {
        try {
            /** @var Album[] $albums */
            $albums = Album::all()->toArray();
            return $albums;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}