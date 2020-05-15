<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;

use App\Entity\Image;
use App\Form\ImageType;

use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '\..\..\.env.desygner');

/**
 * @Route("/api/image", name="image")
 */
class ImageController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, ImageRepository $ImgRepository)
    {
        $this->entityManager = $entityManager;
        $this->ImgRepository = $ImgRepository;
    }

    private function ImgurHelper($keyword)
    {
        $clienId = $_ENV['IMGUR_CLIENTID'];
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api.imgur.com/3/gallery/search/',
            [
                'query' => ['q' => $keyword],
                'headers' => ['Authorization' => 'Client-ID ' . $clienId]
            ]
        );

        $content = $response->getContent(); //data

        return json_decode($content, true);
    }


    /**
     * @Route("/InsertLibrary", name="Insert image DB", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function InsertLibrary(Request $request)
    {
        if( ($request->headers->get('Content-Type')!="application/json") &&
           ( $request->headers->get('Content-Type')!="application/json;charset=UTF-8"))
            return $this->json([
                'message' => ['text' =>"Invalid type".$request->headers->get('Content-Type'), 'level' => 'error'],
            ]);

        $content = json_decode($request->getContent());

        $form = $this->createForm(ImageType::class);
        $form->submit((array) $content);

        /**VALIDATION REQUEST USING THE SYMFONY FORM COMPONENT */
        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $propertyName = $error->getOrigin()->getName();
                $errors[$propertyName] = $error->getMessage();
            }
            return $this->json([
                'message' => ['text' => join("\n", $errors), 'level' => 'error'],
            ]);
        }

        $img = new Image();
        $img->setUrl($content->url);
        $img->setTitle($content->title);
        $img->setDescription($content->description);

        try {
            $this->entityManager->persist($img);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            return $this->json([
                'message' => ['text' => 'Image has to be unique!', 'level' => 'error']
            ]);
        }
        return $this->json([
            'message' => ['text' => 'Image has been insert into the library!', 'level' => 'success']
        ]);
    }

    /**
     * @Route("/readLibrary", name="readLibrary", methods={"GET"})
     */
    public function readLibrary()
    {
        $imgs = $this->ImgRepository->findAll();

        $arrayOfimgs = [];
        foreach ($imgs as $img) {
            $arrayOfimgs[] = $img->imageToArray();
        }

        return $this->json($arrayOfimgs);
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
                    $description = ($description == null) ? $keyword : $description;
                    $title = ($title == null) ? $keyword : $title;
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
