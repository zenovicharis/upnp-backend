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

    public function getNews($lang)
    {
        $news = $this->publicService->getNews();
        $news = $this->filterLanguage($news, $lang);
        return new JsonResponse($news);
    }

    public function getProjects($lang)
    {
        $news = $this->publicService->getProjects();
        $news = $this->filterLanguage($news, $lang);
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
        return $this->twig->render('/volountieer-service/volountieer-service.html');//, ['news' => $news]
    }

    public function contact()
    {
        return $this->twig->render('/contact/contact.html');//, ['news' => $news]
    }

    public function gallery()
    {
        return $this->twig->render('/gallery/gallery.html');//, ['news' => $news]
    }

    public function news()
    {
        return $this->twig->render('/news/news.html');//, ['news' => $news]
    }

    public function projects()
    {
        return $this->twig->render('/projects/projects.html');//, ['news' => $news]
    }

    public function patreon()
    {
        return $this->twig->render('/patreon/patreon.html');//, ['news' => $news]
    }

    public function aboutus()
    {
        return $this->twig->render('/about/about.html');//, ['news' => $news]
    }


    public function landingEn()
    {
        $news = $this->publicService->getNews();
        return $this->twig->render('/en-landing/en-landing.html');//, ['news' => $news]
    }

    public function volunteerEn()
    {
        return $this->twig->render('/en-volountieer-service/en-volountieer-service.html');//, ['news' => $news]
    }

    public function contactEn()
    {
        return $this->twig->render('/en-contact/en-contact.html');//, ['news' => $news]
    }

    public function galleryEn()
    {
        return $this->twig->render('/en-gallery/en-gallery.html');//, ['news' => $news]
    }

    public function newsEn()
    {
        return $this->twig->render('/en-news/en-news.html');
    }

    public function projectEn()
    {
        return $this->twig->render('/en-projects/en-projects.html');//, ['news' => $news]
    }


    public function patreonEn()
    {
        return $this->twig->render('/en-patreon/en-patreon.html');//, ['news' => $news]
    }

    public function aboutusEn()
    {
        return $this->twig->render('/en-about/en-about.html');//, ['news' => $news]
    }

    public function getSingleNews($id)
    {
        $news = $this->publicService->NewsById($id);
        $newsSuggestions = $this->publicService->getThreeNewsSuggestions('serbian');
//        var_dump($news);die();
        return $this->twig->render('/single-news/single-news.html.twig', ['news' => $news, 'suggestions' => $newsSuggestions]);
    }

    public function getSingleNewsEn($id)
    {
        $news = $this->publicService->NewsById($id);
        $newsSuggestions = $this->publicService->getThreeNewsSuggestions('english');
        return $this->twig->render('/en-single-news/en-single-news.html.twig', ['news' => $news, 'suggestions' => $newsSuggestions]);
    }

    protected function filterLanguage($array, $lang){
        $container  = [];
        foreach($array as $el){
            if($el['language'] == $lang){
                $container[] = $el;
            }
        }
        return $container;
    }
}