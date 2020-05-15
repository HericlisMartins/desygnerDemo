<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'\..\..\.env.desygner');

/**
 * @Route("/api/image", name="image")
 */
class ImageController extends AbstractController
{

    private function ImgurHelper($keyword)
    {
        $clienId=$_ENV['IMGUR_CLIENTID'];
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api.imgur.com/3/gallery/search/',
            [
                'query' => ['q' => $keyword],
                'headers' => ['Authorization' => 'Client-ID '.$clienId]
            ]
        );

        $content = $response->getContent(); //data

        return json_decode($content, true);
    }

    /**
     * @Route(  "/imgur/{max}/{keyword}", 
     *          name="Getimgur",
     *          requirements={ "max": "\d+" }
     *       )
     */
    public function Getimgur($keyword, $max)
    {
        /** TODO:  VALIDATE THE KEYWORD BEFORE PARSE TO THE IMFURAPI */
        $data = $this->ImgurHelper($keyword);

        $array_response = array();

        foreach ($data['data'] as $gallery_key => $gallery) {
            /*FROM ALBUNS*/
            if ($gallery['is_album']) {
                $image = $gallery['images'][0];
                if ($image["type"] == "image/jpeg") {
                    $description = substr($image['description'], 0, 30);
                    $title = substr($gallery['title'], 0, 10);
                    $description = ($description==null)?$keyword:$description;
                    $title = ($title==null)?$keyword:$title;
                    $array_response[] = array("title" => $title, "description" => $description, "url" => $image['link']);
                }
            }

            if (count($array_response) == $max)
                break;
        }

        if (count($array_response) <= 0)
            $array_response[] = array("error" => "No results");

        $response = new Response(
            json_encode($array_response),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }
}
