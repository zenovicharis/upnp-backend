<?php

namespace Upnp\Controllers;

use Upnp\EntityModels\VolountieerEntityModel;
use Upnp\Services\NewsService;
use Upnp\EntityModels\NewsEntityModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController
{
    /** @var NewsService $newsService * */
    private $newsService;
    /** @var  \Twig_Environment $twig */
    public $twig;

    public function __construct($newsService, $twig)
    {
        $this->newsService = $newsService;
        $this->twig = $twig;
    }

    public function dashboard(){
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['news'=> $news]);
    }

    public function create(){
        return $this->twig->render('admin_front/create-news.twig');
    }

    public function update(){
        return $this->twig->render('admin_front/update-news.twig');
    }

    public function login(){
        return $this->twig->render('admin_front/login.twig');
    }

    public function createNews(Request $request){
        $news = $this->extractNews($request);
        $successfull = $this->newsService->createNews($news);
        return $this->twig->render('admin_front/create-news.twig', ['message'=> $successfull]);
        /*if($successfull == false)  {
            return new JsonResponse('',500);
        };
        return new JsonResponse($successfull, 201);*/
    }

    public function getNewsById(Request $request, $id){
        $news = $this->newsService->NewsById($id);
        /*if($successfull == false)  {
            return new JsonResponse('',500);
        };
        return new JsonResponse($successfull, 201);*/
        return $this->twig->render('admin_front/update-news.twig', ['news' => $news]);
    }

    public function deleteNews(Request $request, $id){
        $successfull = $this->newsService->deleteNews($id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['news'=> $news, 'deleteMessage'=> $successfull]);
        //return new RedirectResponse("/dashboard");
    }

    public function updateNews(Request $request, $id){
        $news = $this->extractNews($request);
        $successfull = $this->newsService->updateNews($news,$id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['updateMessage' => $successfull, 'news' => $news]);
        //return new RedirectResponse("/dashboard");
    }

    public function singleNew(Request $request, $id){
        $news = $this->newsService->NewsById($id);
        return $this->twig->render("admin_front/single-news.twig", ['news' => $news]);

    }

    public function CreateVolountieer(Request $request){
        $volountieer = $this->extractVolountieer($request);
        $successfull = $this->newsService->createVolountieer($volountieer);
        if($successfull == false)  {
            return new JsonResponse('',500);
        };
        return new JsonResponse($successfull, 201);
    }

    public function getVolountieers(){
        $successfull = $this->newsService->readVolountieers();
        if($successfull == false)  {
            return new JsonResponse('',500);
        };
        return new JsonResponse($successfull, 201);
    }

    //private functions

    private function extractNews(Request $request){
        $title = $request->request->get("title");
        $content = $request->request->get("content");
        $image = $request->request->get("image");
        $category = $request->request->get("category");
        $language = $request->request->get("language");
        // TODO: Implement validation for News
        $news = new NewsEntityModel($title, $content,$category, $image, $language);
        return $news;
    }

    private function extractVolountieer(Request $request){
         $ime_prezime = $request->request->get('ime_prezime');
         $datum = $request->request->get('datum');
         $adresa = $request->request->get('adresa');
         $grad = $request->request->get('grad');
         $telefon = $request->request->get('telefon');
         $email = $request->request->get('email');
         $str_sprema = $request->request->get('str_sprema');
         $zanimanje = $request->request->get('zanimanje');
         $hobi = $request->request->get('hobi');
         $iskustvo = $request->request->get('iskustvo');
         $podrucje_rada = $request->request->get('podrucje_rada');
         $poslovi = $request->request->get('poslovi');
         $nedeljni_sati = $request->request->get('nedeljni_sati');
         $vreme = $request->request->get('vreme');
         $dodatna_obuka = $request->request->get('dodatna_obuka');
        // TODO: Implement validation for News
        $volountieer = new VolountieerEntityModel($ime_prezime, $datum, $adresa, $grad, $telefon, $email, $str_sprema, $zanimanje, $hobi, $iskustvo, $podrucje_rada, $poslovi, $nedeljni_sati, $vreme, $dodatna_obuka);
        return $volountieer;
    }
}