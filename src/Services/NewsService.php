<?php

namespace Upnp\Services;

use Upnp\EntityModels\ImageEntityModel;
use Upnp\Models\News;
use Upnp\EntityModels\NewsEntityModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class NewsService
{
    public function __construct()
    {

    }

    public function createNews(NewsEntityModel $entityModel)
    {
        try {
            $news = News::create([
                "title" => $entityModel->title,
                "content" => $entityModel->content,
                "image_id" => $entityModel->image_id,
                "category" => $entityModel->category,
                "language" => $entityModel->language
            ]);
            return (int)$news->id;
        } catch (Exception $e) {
            var_dump($e->getMessage());die();
        }
    }

    public function updateNewsImage($entityModel, $id)
    {
        try {
            $news = News::find($id)->update([
                "image_id" => $entityModel->id
            ]);
            return (int)$news->id;
        } catch (Exception $e) {
            var_dump($e->getMessage());die();
        }
    }

    public function readNews()
    {
        try {
            /** @var News[] $news */
            $news = News::get_images_with_news();
            return $news;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function readProjects()
    {
        try {
            /** @var News[] $projs */
            $projs = News::get_images_with_projects();
            return $projs;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function deleteNews($id)
    {
        try {
            $news = News::find($id);
            $news->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateNews(NewsEntityModel $entityModel, $id)
    {
        try {
            $news = News::find($id)->update([
                "title" => $entityModel->title,
                "content" => $entityModel->content,
                "category" => $entityModel->category,
                "language" => $entityModel->language
            ]);
            return $news;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
            return false;
        }
    }

    public function NewsById($id)
    {
        try {
            $news = News::get_news_with_id($id);

            return $news->toArray();
        } catch (Exception $e) {
            return $e;
        }
    }
}