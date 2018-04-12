<?php

namespace Upnp\Services;

use Upnp\Models\Album;
use Symfony\Component\Config\Definition\Exception\Exception;

class AlbumService
{
    public function createAlbum($title, $english_title)
    {
        try {
            $album = Album::create([
                "title" => $title,
                "english_title" => $english_title
            ]);
            return $album;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updateAlbum($title, $english_title, $id)
    {
        try {
            $album = Album::find($id)->update([
                "title" => $title,
                "english_title" => $english_title
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