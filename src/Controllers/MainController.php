<?php

namespace Upnp\Controllers;

use Upnp\Application;
use Upnp\Clients\ImgurClient;
use Upnp\Services\NewsService;
use Upnp\EntityModels\NewsEntityModel;
use Upnp\EntityModels\ImageEntityModel;
use Upnp\EntityModels\VolountieerEntityModel;
use Upnp\Libraries\ValidationLibrary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /** @var ValidationLibrary $validationLibrary * */
    public $validationLibrary;

    public function __construct($newsService, $userService, $imgurClient, $twig, $validationLibrary)
    {
        $this->twig = $twig;
        $this->newsService = $newsService;
        $this->validationLibrary = $validationLibrary;
        $this->userService = $userService;
        $this->imgurClient = $imgurClient;
    }

    public function dashboard()
    {
        $news = $this->newsService->readNews();
        return $this->twig->render('admin/dashboard.twig', ['news' => $news]);
    }

    public function create()
    {
        return $this->twig->render('admin/create-news.twig');
    }

    public function update()
    {
        return $this->twig->render('admin/update-news.twig');
    }

    public function login(Application $app, Request $request)
    {
        $isRedirected = $request->query->get("continue");
        if (!empty($isRedirected)) {
            return $this->twig->render('admin/login.twig', ['message' => true]);
        }
        return $this->twig->render('admin/login.twig');
    }

    public function createNews(Request $request)
    {
        $isValid = $this->validationLibrary->newsRules($request);
        if ($isValid->validate()) {

            $image = $request->files->get("image");
            if (!empty($image)) {
                /** @var ImageEntityModel $imageObj */
                $imageObj = $this->imgurClient->uploadImage($image);
                $imageSavedEntity = $this->newsService->createImage($imageObj);
                $request->request->set('image_id', $imageSavedEntity->id);
            }else {
                return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
            }
            $news = $this->extractNews($request);

            $successfull = $this->newsService->createNews($news);
            return new RedirectResponse('/news/'.$successfull);
//            return $this->twig->render('admin/single-news.twig', ['message' => $successfull]);
        }
        $errors = $isValid->errors();
        $error_string = array_reduce($errors,function($v1, $v2){
            return $v1 .$v2[0].'  ';
        });
        return $this->twig->render('admin/create-news.twig', ['error_message' => $error_string]);
    }

    public function editNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render('admin/update-news.twig', ['news' => $news]);
    }

    public function deleteNews(Request $request, $id)
    {
        $successfull = $this->newsService->deleteNews($id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin/dashboard.twig", ['news' => $news, 'deleteMessage' => $successfull]);
        //return new RedirectResponse("/dashboard");
    }

    public function updateNews(Request $request, $id)
    {
        $news = $this->extractNews($request);
        $successfull = $this->newsService->updateNews($news, $id);
//        var_dump($successfull);die();
//        $news = $this->newsService->readNews();
        return new RedirectResponse('/news/'.$id);
        //return new RedirectResponse("/dashboard");
    }

    public function singleNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render("admin/single-news.twig", ['news' => $news]);

    }

    public function CreateVolountieer(Request $request)
    {
        $isValid = $this->validationLibrary->volountieerRules($request);
        if ($isValid->validate()) {
            $volountieer = $this->extractVolountieer($request);
            $successfull = $this->newsService->createVolountieer($volountieer);
            if ($successfull == false) {
                return new JsonResponse('', 500);
            };
            return new JsonResponse($successfull, 201);

        }
        $errors = $isValid->errors();
        return new JsonResponse($errors, JsonResponse::HTTP_EXPECTATION_FAILED);
    }

    public function getVolountieers()
    {
        $successfull = $this->newsService->readVolountieers();
        if ($successfull == false) {
            return new JsonResponse('', 500);
        };
        return new JsonResponse($successfull, 201);
    }

    public function createAlbum(){
        return $this->twig->render("admin/album/create.twig");
    }

    public function infoAlbum(){
        return $this->twig->render("admin/album/info.twig");
    }

    public function editAlbum(){
        return $this->twig->render("admin/album/edit.twig");
    }

    public function albums(){
        return $this->twig->render("admin/album/albums.twig");
    }


    //private functions
    private function extractNews(Request $request)
    {
        $title = $request->request->get("title");
        $content = $request->request->get("content");
        $category = $request->request->get("category");
        $language = $request->request->get("language");
        $image_id = $request->request->get("image_id");
        //image_id table, reference to image table with images
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
        $volountieer = new VolountieerEntityModel($ime_prezime, $datum, $adresa, $grad, $telefon, $email, $str_sprema, $zanimanje, $hobi, $iskustvo, $podrucje_rada, $poslovi, $nedeljni_sati, $vreme, $dodatna_obuka);
        return $volountieer;
    }

    public function loginValidate(Application $app, Request $request)
    {
        $user = $request->request->get('user');
        $password = $request->request->get('password');
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->toArray();
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
