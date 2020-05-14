<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/image", name="image")
 */
class ImageController extends AbstractController
{

    private function ImgurHelper($keyword)
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api.imgur.com/3/gallery/search/',
            [
                'query' => ['q' => $keyword],
                'headers' => ['Authorization' => 'Client-ID 597bbf0bc309cca']
            ]
        );

        $content = $response->getContent(); //data

        return json_decode($content, true);
    }

    /**
     * @Route(  "/imgur/{keyword}", 
     *          name="Getimgur",
     *          requirements={ "keyword": "\w+" }
     *       )
     */
    public function Getimgur($keyword)
    {

        /** TODO:  VALIDATE THE KEYWORD BEFORE PARSE TO THE IMFURAPI */
        $data = $this->ImgurHelper($keyword);

        foreach ($data['data'] as $gallery_key => $gallery) {
            if (isset($gallery['images'])) {
                foreach ($gallery['images'] as $image) {
                    $array_response[] = array("title" => $image['title'], "description" => $image['description'], "url" => $image['link']);
                }
            }
        }

        $response = new Response(
            json_encode($array_response),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }
}
