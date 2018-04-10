<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.4.18
 * Time: 16:15
 */


namespace Upnp\Clients;

use GuzzleHttp\Client;
use Upnp\EntityModels\ImageEntityModel;
use GuzzleHttp\Exception;


class ImgurClient
{
    /** @var  Client $client */
    private $client;
    private $clientId;
    private $clientSecretId;
    private $baseUrl = 'https://api.imgur.com/';

    public function __construct($config)
    {
        $this->clientId = $config['clientId'];
        $this->clientSecretId = $config['clientSecret'];
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'verify' => false // verify false, da ne cekira ssl sertifikate. -_-
        ]);
    }


    public function uploadImage($image)
    {
        /* $rawImage = file_get_contents($image->getRealPath());
         $header = ['Authorization' => 'Client-ID '.$this->clientId];
        try {
            $response = $this->client->post('/3/image', [
                'form_params' => [
                    'image' => base64_encode($rawImage)
                ],
                'headers' => $header
            ]);
        } catch (\Exception $e){
            var_dump($e->getMessage());die();
        }
         $content = $response->getBody()->getContents();
         $image = json_decode($content);*/

        //  return new ImageEntityModel($image->data->id, $image->data->deletehash,  $image->data->link);
        return new ImageEntityModel('KOLbQBX', 'oqyIsRzBwK1qXs4', 'https://i.imgur.com/VdxpZLe.jpg');
    }

    public function deleteImage($id) {
        $header = ['Authorization' => 'Client-ID '.$this->clientId];
        try {
            $response = $this->client->delete("/3/image/". $id, [
               'headers' => $header
           ]);

        }catch (\Exception $e) {
            var_dump($e->getMessage());die();
        }
        $content = $response->getBody()->getContents();
        $data = json_decode($content);
        return $data['success'];
    }
}
