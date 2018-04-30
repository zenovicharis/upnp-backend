<?php

namespace Upnp\Controllers;

use Upnp\Application;
use Upnp\Clients\ImgurClient;
use Upnp\Models\Album;
use Upnp\Models\Image;
use Upnp\Services\AlbumService;
use Upnp\Services\MailService;
use Upnp\Services\NewsService;
use Upnp\Services\VolountieerService;
use Upnp\Services\ImageService;
use Upnp\EntityModels\NewsEntityModel;
use Upnp\EntityModels\ImageEntityModel;
use Upnp\EntityModels\AlbumEntityModel;
use Upnp\EntityModels\VolountieerEntityModel;
use Upnp\Libraries\ValidationLibrary;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Config\Definition\Exception\Exception;

class MainController
{
    /** @var NewsService $newsService * */
    private $newsService;
    /** @var AlbumService $albumService * */
    private $albumService;
    /** @var VolountieerService $volountieerService * */
    private $volountieerService;
    /** @var ImageService $imageService * */
    private $imageService;
    /** @var ImgurClient $imgurClient * */
    private $imgurClient;
    /** @var  \Twig_Environment $twig */
    public $twig;
    /** @var ValidationLibrary $validationLibrary * */
    public $validationLibrary;

    /** @var MailService $mailService * */
    public $mailService;

    public function __construct($newsService, $userService, $volountieerService, $imgurClient, $twig, $validationLibrary, $albumService, $imageService, $mailService)

    {
        $this->twig = $twig;
        $this->mailService = $mailService;
        $this->imgurClient = $imgurClient;
        $this->userService = $userService;
        $this->newsService = $newsService;
        $this->albumService = $albumService;
        $this->imageService = $imageService;
        $this->validationLibrary = $validationLibrary;
        $this->volountieerService = $volountieerService;
    }

    public function news(Request $request)
    {
        $news = $this->newsService->readNews();
        $newsEng = $this->filterLanguage($news, "english");
        $newsSrb = $this->filterLanguage($news, "serbian");
        $deletemessage = $request->query->get("deletemessage");
        if (!empty($deletemessage)) {
            return $this->twig->render('/news-list/news-list.html.twig', ['newsEn' => $newsEng, 'newsSrb' => $newsSrb, 'deletemessage' => $deletemessage]);
        }
        return $this->twig->render('/news-list/news-list.html.twig', ['newsEn' => $newsEng, 'newsSrb' => $newsSrb]);
    }

    public function projects(Request $request)
    {
        $proj = $this->newsService->readProjects();
        $projEng = $this->filterLanguage($proj, "english");
        $projSrb = $this->filterLanguage($proj, "serbian");
        $deletemessage = $request->query->get("deletemessage");
        if (!empty($deletemessage)) {
            return $this->twig->render('/projects-list/projects-list.html.twig', ['newsEn' => $projEng, 'newsSrb' => $projSrb, 'deletemessage' => $deletemessage]);
        }
        return $this->twig->render('/projects-list/projects-list.html.twig', ['newsEn' => $projEng, 'newsSrb' => $projSrb]);
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
//        var_dump("heee");die();
        $isRedirected = $request->query->get("continue");
//        if (!empty($isRedirected)) {
//            return $this->twig->render('/news-list/news-list.html', ['message' => true]);
//        }
        return $this->twig->render('/login/login.html');
    }

    public function changeNewsImage(Request $request, $id){
        $image = $request->files->get("image");

        if (!empty($image)) {
            /** @var ImageEntityModel $imageObj */
            $imageObj = $this->imgurClient->uploadImage($image);
            $imageSavedEntity = $this->imageService->createImage($imageObj);

            $successfull = $this->newsService->updateNewsImage($imageSavedEntity, $id);
            return new RedirectResponse('/news/' . $id . '?message=Vest je uspesno kreirana!');
        } else {
            return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
        }
    }

    public function createNews(Request $request)
    {
        $isValid = $this->validationLibrary->newsRules($request);
        if ($isValid->validate()) {

            $image = $request->files->get("image");
            if (!empty($image)) {
                /** @var ImageEntityModel $imageObj */
                $imageObj = $this->imgurClient->uploadImage($image);
                $imageSavedEntity = $this->imageService->createImage($imageObj);
                $request->request->set('image_id', $imageSavedEntity->id);
            } else {
                return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
            }
            $news = $this->extractNews($request);
//            var_dump($news);die();
            $successfull = $this->newsService->createNews($news);
//            var_dump($successfull);die();
            return new RedirectResponse('/news/' . $successfull . '?message=Vest je uspesno kreirana!');
//            return $this->twig->render('admin/info.twig', ['message' => $successfull]);
        }
        $errors = $isValid->errors();
        $error_string = array_reduce($errors, function ($v1, $v2) {
            return $v1 . $v2[0] . '  ';
        });
        return $this->twig->render('/news/create', ['error_message' => $error_string]);
    }

    public function getCreateNews()
    {
        return $this->twig->render('/news-form/news-form.html.twig', ['news' => new NewsEntityModel(), 'edit' => false]);
    }

    public function editNews(Request $request, $id)
    {
        $news = $this->newsService->NewsById($id);
        return $this->twig->render('/news-form/news-form.html.twig', ['news' => $news, 'edit' => true]);//
    }

    public function updateNews(Request $request, $id)
    {
        $newsModel = $this->extractNews($request);
        $newsEdited = $this->newsService->updateNews($newsModel, $id);

        return new RedirectResponse("/news/" . $id . '?updatemessage=Vest je uspesno izmenjena');
    }

    public function deleteNews(Request $request, $id)
    {
        // if Succesful return message
//        var_dump($id);die();
        $successfull = $this->newsService->deleteNews($id);
        return new RedirectResponse('/news?deletemessage=Vest je uspesno izbrisana!');
    }

    public function singleNews(Request $request, $id)
    {
        // if no news return message
        $news = $this->newsService->NewsById($id);
        $message = $request->query->get("message");
        $updatemessage = $request->query->get("updatemessage");
        if (!empty($message)) {
            return $this->twig->render("/news-form-info/news-form-info.html.twig", ['news' => $news, 'message' => $message]);
        } elseif (!empty($updatemessage)) {
            return $this->twig->render("/news-form-info/news-form-info.html.twig", ['news' => $news, 'updatemessage' => $updatemessage]);
        }
        return $this->twig->render("/news-form-info/news-form-info.html.twig", ['news' => $news]);
    }

    public function CreateVolountieer(Request $request)
    {
        $isValid = $this->validationLibrary->volountieerRules($request);
        if ($isValid->validate()) {
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
        $successfull = $this->volountieerService->getValountieers();
        if ($successfull == false) {
            return new JsonResponse('', 500);
        };
        return new JsonResponse($successfull, 201);
    }

    public function deleteAlbumImage(Request $request, $id)
    {
        // if message if fails
//        var_dump($id);die();
        $succesful = $this->imageService->deleteAlbumImage($id);

        return new RedirectResponse("/album/info/" . $succesful);

    }

    public function uploadImageToAlbum(Request $request, $id)
    {
        $image = $request->files->get("image");
        if (!empty($image)) {
            /** @var ImageEntityModel $imageObj */
            $imageObj = $this->imgurClient->uploadImage($image);
            $imageSavedEntity = $this->imageService->createImage($imageObj, $id);
        } else {
            return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
        }
        return new RedirectResponse("/album/edit/" . $id);
    }

    public function getAllVolunteer(){
        $volunteers = $this->volountieerService->getValountieers();
        return $this->twig->render("/volountieer-list/volountieer-list.html.twig", ['volountieers' => $volunteers]);
    }


    public function getVolunteer($id){
        $volunteer = $this->volountieerService->getValountieer($id);
        return $this->twig->render("/volountieer-form-info/volountieer-form-info.html.twig", ['volonter' => $volunteer]);
    }



    //List All Albums
    public function albums(Request $request)
    {
        $albums = $this->albumService->readAlbumswithImages();
        $deletemessage = $request->query->get("deletemessage");
        if (!empty($deletemessage)) {
            return $this->twig->render("/albums-list/albums-list.html.twig", ['albums' => $albums, 'deletemessage' => $deletemessage]);
        }
        return $this->twig->render("/albums-list/albums-list.html.twig", ['albums' => $albums]);
    }

    // Edit Album GET
    public function editAlbum(Request $request, $id)
    {
        $album = $this->albumService->readAlbumById($id);
        return $this->twig->render("/album-form/album-form.html.twig", ['album' => $album, 'edit' => true]);
    }

    // Edit Album POST
    public function updateAlbum(Request $request, $id)
    {
        $title = $request->request->get("title");
        $english_title = $request->request->get("english_title");
        $updatedAlbum = $this->albumService->updateAlbum($title, $english_title, $id);

        if ($updatedAlbum) {
            return new RedirectResponse("/album/info/" . $id . '?updatemessage=Album je uspesno izmenjen');
        } else {
            //implement message
        }

        //return $this->twig->render("admin/album/info.twig", ['album'=> $updatedAlbum]);
    }


    // Info Album Get
    public function infoAlbum(Request $request, $id)
    {
        $album = $this->albumService->readAlbumById($id);
        $message = $request->query->get("message");
        $updatemessage = $request->query->get("updatemessage");
        if (!empty($message)) {
            return $this->twig->render("/album-form-info/album-form-info.html.twig", ['album' => $album, 'message' => $message]);
        } elseif (!empty($updatemessage)) {
            return $this->twig->render("/album-form-info/album-form-info.html.twig", ['album' => $album, 'updatemessage' => $updatemessage]);
        }
        return $this->twig->render("/album-form-info/album-form-info.html.twig", ['album' => $album]);
    }


    // Create Album GET
    public function createAlbum()
    {
        return $this->twig->render("/album-form/album-form.html.twig", ['edit' => false, 'album' => new AlbumEntityModel()]);
    }


    // Create Album POST
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
            $imageSavedEntity = $this->imageService->createImage($imageObj, $createdAlbumObject->id);
            //$request->request->set('image_id', $imageSavedEntity->id);
        } else {
            return new JsonResponse('image not uploaded', JsonResponse::HTTP_EXPECTATION_FAILED);
        }

        // redirect na info, kad ga napravimo.
        return new RedirectResponse('/album/info/' . $createdAlbumObject->id . '?message=Album je uspesno kreiran!');

    }


    // Delete Album GET
    public function deleteAlbum(Request $request, $id)
    {
        $album = Album::find($id);
        $album->delete();
        return new RedirectResponse('/album?deletemessage=Album je uspesno izbrisan!');
    }

    public function sendMail(Application $app, Request $request)
    {
        $phone = $request->request->get('phone');
        $subject = $request->request->get('subject');
        $content = $request->request->get('content');
        $company = $request->request->get('company');
        $clientName = $request->request->get('name');
        $clientMail = $request->request->get('senderEmail');

        $isSent = $this->mailService->sendMail($clientMail, $clientName, $subject, $content);
        return new Response();
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
            return new RedirectResponse('/news');
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
        $image = $this->imageService->getImageById($id);

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
