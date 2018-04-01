<?php

namespace Upnp\Controllers;

use Upnp\Application;
use Upnp\Clients\ImgurClient;
use Upnp\Services\NewsService;
use Upnp\EntityModels\NewsEntityModel;
use Upnp\EntityModels\ImageEntityModel;
use Upnp\EntityModels\VolountieerEntityModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController
{
    /** @var NewsService $newsService * */
    private $newsService;
    /** @var ImgurClient $imgurClient * */
    private $imgurClient;
    /** @var  \Twig_Environment $twig */
    public $twig;

    public function __construct($newsService, $userService, $imgurClient, $twig)
    {
        $this->twig = $twig;
        $this->newsService = $newsService;
        $this->userService = $userService;
        $this->imgurClient = $imgurClient;
    }

    public function dashboard()
    {
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['news' => $news]);
    }

    public function create()
    {
        return $this->twig->render('admin_front/create-news.twig');
    }

    public function update()
    {
        return $this->twig->render('admin_front/update-news.twig');
    }

    public function login(Application $app, Request $request)
    {
        $isRedirected = $request->query->get("continue");
        if (!empty($isRedirected)) {
            return $this->twig->render('admin_front/login.twig', ['message' => true]);
        }
        return $this->twig->render('admin_front/login.twig');
    }

    public function createNews(Request $request)
    {
        $image = $request->files->get("image");
        if(!empty($image)){
            /** @var ImageEntityModel $imageObj */
            $imageObj= $this->imgurClient->uploadImage($image);
            $imageSavedEntity = $this->newsService->createImage($imageObj);
            $request->set("image_id", $imageSavedEntity->id);
        }
        $news = $this->extractNews($request);
        $successfull = $this->newsService->createNews($news);
        return $this->twig->render('admin_front/create-news.twig', ['message' => $successfull]);
    }

    public function editNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render('admin_front/update-news.twig', ['news' => $news]);
    }

    public function deleteNews(Request $request, $id)
    {
        $successfull = $this->newsService->deleteNews($id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['news' => $news, 'deleteMessage' => $successfull]);
        //return new RedirectResponse("/dashboard");
    }

    public function updateNews(Request $request, $id)
    {
        $news = $this->extractNews($request);
        $successfull = $this->newsService->updateNews($news, $id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin_front/dashboard.twig", ['updateMessage' => $successfull, 'news' => $news]);
        //return new RedirectResponse("/dashboard");
    }

    public function singleNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render("admin_front/single-news.twig", ['news' => $news]);

    }

    public function CreateVolountieer(Request $request)
    {
        $volountieer = $this->extractVolountieer($request);
        $successfull = $this->newsService->createVolountieer($volountieer);
        if ($successfull == false) {
            return new JsonResponse('', 500);
        };
        return new JsonResponse($successfull, 201);
    }

    public function getVolountieers()
    {
        $successfull = $this->newsService->readVolountieers();
        if ($successfull == false) {
            return new JsonResponse('', 500);
        };
        return new JsonResponse($successfull, 201);
    }

    //private functions

    private function extractNews(Request $request)
    {
        $title = $request->request->get("title");
        $content = $request->request->get("content");
        $category = $request->request->get("category");
        $language = $request->request->get("language");
        $image_id = $request->request->get("image_id");
        // TODO: Implement validation for News
        $news = new NewsEntityModel($title, $content, $category, $image_id, $language);
        return $news;
    }

    private function extractVolountieer(Request $request)
    {
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
        // TODO: Implement validation for Volountieer
        $volountieer = new VolountieerEntityModel($ime_prezime, $datum, $adresa, $grad, $telefon, $email, $str_sprema, $zanimanje, $hobi, $iskustvo, $podrucje_rada, $poslovi, $nedeljni_sati, $vreme, $dodatna_obuka);
        return $volountieer;
    }

    public function loginValidate(Application $app, Request $request)
    {
        $user = $request->request->get('user');
        $password = $request->request->get('password');
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->to_array();
            return new RedirectResponse('/dashboard');
        }
        return new RedirectResponse('/login?continue=failed');
    }


    public function logout()
    {
        session_destroy();
        return new RedirectResponse('/login');
    }
}

