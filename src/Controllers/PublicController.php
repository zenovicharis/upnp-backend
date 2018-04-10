<?php
/**
 * Created by PhpStorm.
 * User: imamo
 * Date: 4/10/2018
 * Time: 10:09 PM
 */

namespace Upnp\Controllers;

use Upnp\Services\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;

class PublicController
{

    /** @var PublicService $publicService */
    public $publicService;

    public function __construct($publicService)
    {
        $this->publicService = $publicService;
    }

    public function getNews()
    {
        $news = $this->publicService->getNews();
        return new JsonResponse($news);
    }

    public function getAlbums()
    {
        $albums = $this->publicService->getAlbums();
        return new JsonResponse($albums);
    }
}