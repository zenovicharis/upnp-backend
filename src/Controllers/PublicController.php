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

    /** @var  \Twig_Environment $twig */
    public $twig;

    public function __construct($twig, $publicService)
    {
        $this->twig = $twig;
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


    public function landing()
    {
        $news = $this->publicService->getNews();
        return $this->twig->render('/index.html');//, ['news' => $news]
    }

    public function volunteer()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/volountieer-service/volountieer-service.html');//, ['news' => $news]
    }

    public function contact()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/contact/contact.html');//, ['news' => $news]
    }

    public function gallery()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/gallery/gallery.html');//, ['news' => $news]
    }

    public function news()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/news/news.html');//, ['news' => $news]
    }

    public function patreon()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/patreon/patreon.html');//, ['news' => $news]
    }

    public function aboutus()
    {
//        $news = $this->publicService->getNews();
        return $this->twig->render('/about/about.html');//, ['news' => $news]
    }
//    public function getAlbums()
//    {
//        $albums = $this->publicService->getAlbums();
//        return new JsonResponse($albums);
//    }
//
//
//    public function getNews()
//    {
//        $news = $this->publicService->getNews();
//        return new JsonResponse($news);
//    }
//
//    public function getAlbums()
//    {
//        $albums = $this->publicService->getAlbums();
//        return new JsonResponse($albums);
//    }
}