<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/12/2018
 * Time: 6:20 PM
 */

namespace Upnp\Services;

use Upnp\Models\Image;
use Upnp\Clients\ImgurClient;
use Upnp\EntityModels\ImageEntityModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class ImageService
{
    /** @var ImgurClient $imgurClient * */
    private $imgurClient;

    public function __construct(ImgurClient $imgurClient)
    {
        $this->imgurClient = $imgurClient;
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

    public function getImageById($id)
    {
        try {
            $image = Image::with("News")->find($id);

            return $image;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function deleteImage($id)
    {
        try {
            /** @var Image $image */
            $image = Image::find($id);
//            var_dump($image);die();
            $image->delete();
            return true;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}