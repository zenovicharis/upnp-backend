<?php

namespace Upnp\Controllers;

use Symfony\Component\Config\Definition\Exception\Exception;
use Upnp\Application;
use Upnp\Clients\ImgurClient;
use Upnp\Models\Image;
use Upnp\Services\AlbumService;
use Upnp\Services\NewsService;
use Upnp\EntityModels\NewsEntityModel;
use Upnp\EntityModels\ImageEntityModel;
use Upnp\EntityModels\VolountieerEntityModel;
use Upnp\Libraries\ValidationLibrary;
use Upnp\Services\VolountieerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MainController
{
    /** @var NewsService $newsService * */
    private $newsService;
    /** @var AlbumService $albumService * */
    private $albumService;
    /** @var ImgurClient $imgurClient * */
    private $imgurClient;
    /** @var  \Twig_Environment $twig */
    public $twig;
    /** @var ValidationLibrary $validationLibrary * */
    public $validationLibrary;


    /** @var VolountieerService $volountieerService * */
    public $volountieerService;

    public function __construct($newsService, $userService, $volountieerService, $imgurClient, $twig, $validationLibrary, $albumService)

    {
        $this->twig = $twig;
        $this->imgurClient = $imgurClient;
        $this->userService = $userService;
        $this->newsService = $newsService;
        $this->albumService = $albumService;
        $this->validationLibrary = $validationLibrary;
        $this->volountieerService = $volountieerService;
    }

    public function news()
    {
        $news = $this->newsService->readNews();
        return $this->twig->render('admin/news/news.twig', ['news' => $news]);
    }

    public function create()
    {
        $albums = $this->albumService->readAlbums();
        return $this->twig->render('admin/news/create.twig', ['albums' => $albums]);
    }

    public function update()
    {
        return $this->twig->render('admin/news/edit.twig');
    }

    public function login(Application $app, Request $request)
    {
        $isRedirected = $request->query->get("continue");
        if (!empty($isRedirected)) {
            return $this->twig->render('admin/news/login.twig', ['message' => true]);
        }
        return $this->twig->render('admin/news/login.twig');
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
            } else {
                return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
            }
            $news = $this->extractNews($request);

            $successfull = $this->newsService->createNews($news);
            return new RedirectResponse('/news/' . $successfull . '?message=Vest je uspesno kreirana!');
//            return $this->twig->render('admin/info.twig', ['message' => $successfull]);
        }
        $errors = $isValid->errors();
        $error_string = array_reduce($errors, function ($v1, $v2) {
            return $v1 . $v2[0] . '  ';
        });
        return $this->twig->render('admin/news/create.twig', ['error_message' => $error_string]);
    }

    public function editNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render('admin/news/edit.twig', ['news' => $news]);
    }

    public function deleteNews(Request $request, $id)
    {
        $successfull = $this->newsService->deleteNews($id);
        $news = $this->newsService->readNews();
        return $this->twig->render("admin/news/news.twig", ['news' => $news, 'deleteMessage' => $successfull]);
        //return new RedirectResponse("/dashboard");
    }

    public function updateNews(Request $request, $id)
    {
        $news = $this->extractNews($request);
        $successfull = $this->newsService->updateNews($news, $id);
//        var_dump($successfull);die();
//        $news = $this->newsService->readNews();
        return new RedirectResponse('/news/' . $id);

        //return new RedirectResponse("/dashboard");
    }

    public function singleNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        $message = $request->query->get("message");
        if (!empty($message)) {
            return $this->twig->render("admin/news/info.twig", ['news' => $news, 'message' => $message]);
        }
        return $this->twig->render("admin/news/info.twig", ['news' => $news]);
    }

    public function CreateVolountieer(Request $request)
    {
//        var_dump("hello");die();
//        $isValid = $this->validationLibrary->volountieerRules($request);
//        if ($isValid->validate()) {
        if (true) {
            $volountieer = $this->extractVolountieer($request);
            $successfull = $this->volountieerService->createVolountieer($volountieer);
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

    public function createAlbum()
    {
        return $this->twig->render("admin/album/create.twig");
    }

    public function createAlbumPost(Request $request)
    {
        $title = $request->request->get("title");
        $english_title = $request->request->get("english_title");

        $createdAlbumObject = $this->albumService->createAlbum($title, $english_title);
        // $request->request->set('album_id', $createdAlbumObject->id);

        $image = $request->files->get("image");
        if (!empty($image)) {
            /** @var ImageEntityModel $imageObj */
            $imageObj = $this->imgurClient->uploadImage($image);
            $imageSavedEntity = $this->newsService->createImage($imageObj, $createdAlbumObject->id);
            //$request->request->set('image_id', $imageSavedEntity->id);
        } else {
            return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
        }

        // redirect na info, kad ga napravimo.
        return new RedirectResponse('/album/info/' . $createdAlbumObject->id);

    }

    public function infoAlbum(Request $request, $id)
    {
        $album = $this->albumService->readAlbumById($id);

        return $this->twig->render("admin/album/info.twig", ['album' => $album]);
    }

    public function deleteAlbumImage(Request $request, $id)
    {
        $album = $this->albumService->deleteAlbumImage($id);
    }

    public function updateAlbum(Request $request, $id)
    {
        $title = $request->request->get("title");
        $english_title = $request->request->get("english_title");
        $updatedAlbum = $this->albumService->updateAlbum($title, $english_title, $id);

        if ($updatedAlbum) {
            return new RedirectResponse("/album/info/" . $id);
        } else {
            //implement message
        }

        //return $this->twig->render("admin/album/info.twig", ['album'=> $updatedAlbum]);
    }

    public function uploadImageToAlbum(Request $request, $id)
    {
        $image = $request->files->get("image");
        if (!empty($image)) {
            /** @var ImageEntityModel $imageObj */
            $imageObj = $this->imgurClient->uploadImage($image);
            $imageSavedEntity = $this->newsService->createImage($imageObj, $id);
        } else {
            return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
        }
        return new RedirectResponse("/album/edit/" . $id);
    }

    public function editAlbum(Request $request, $id)
    {
        $album = $this->albumService->readAlbumById($id);
        return $this->twig->render("admin/album/edit.twig", ['album' => $album]);
    }

    public function albums()
    {
        // $albums = $this->albumService->readAlbums();
        $albums = $this->albumService->readAlbumswithImages();
        return $this->twig->render("admin/album/albums.twig", ['albums' => $albums]);
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

    /*private function extractAlbum(Request $request)
    {
        $title = $request->request->get("title");
        $album = new AlbumEntityModel($title);
        return $album;
    }*/

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
            return new RedirectResponse('/news/');
        }
        return new RedirectResponse('/login?continue=failed');
    }

    public function logout()
    {
        session_destroy();
        return new RedirectResponse('/login');
    }

    public function deleteImage($id)
    {
        /** @var Image $image */
        $image = $this->newsService->getImageById($id);

//        var_dump($image);die();

//        $image->news->detach($id);
//        $image->detach($newsId);
//        $image->news->detach($id);
//        $success = $this->imgurClient->deleteImage($image->delete_hash);
        try {
            $image->news()->detach($id);
//            $image->delete();
//            $success = $this->newsService->deleteImage($newsId);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
//        if($success) {
//            return new RedirectResponse('/news/'.$newsId);
//        }
//        return
    }
}
