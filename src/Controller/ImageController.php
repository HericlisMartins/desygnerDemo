<?php

/** TO DO LIST
 *  - INSERT THE ID FROM IMGUR INTO DB 
 *  - CREATE A MODEL FOR ERRORS
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Image;
use App\Form\ImageType;
use App\Controller\Utils\ImgUR;

use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

/**
 * @Route("/api/image", name="image")
 */
class ImageController extends AbstractController
{
    private $entityManager;
    private $ImgRepository;

    public function __construct(EntityManagerInterface $entityManager, ImageRepository $ImgRepository)
    {
        $this->entityManager = $entityManager;
        $this->ImgRepository = $ImgRepository;
    }

    /**
     * @Route("/InsertLibrary", name="Insert image DB", methods={"POST"})
     * @param Request $request
     */
    public function InsertLibrary(Request $request)
    {
        if (($request->headers->get('Content-Type') != "application/json") &&
            ($request->headers->get('Content-Type') != "application/json;charset=UTF-8")
        )
            return $this->json([
                'message' => ['text' => "Invalid type" . $request->headers->get('Content-Type'), 'level' => 'error'],
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

        $response = new Response(
            json_encode($arrayOfimgs),
            Response::HTTP_OK,
            //['content-type' => 'text/html'] //test fail
            ['content-type' => 'application/json']
        );

        return $response;
    }


    /**
     * @Route(  "/imgur/{max}/{cache_state}/{keyword}", 
     *          name="Getimgur",
     *          requirements={ "max": "\d+", "cache_state": "-1|0|1" }
     *       )
     */
    public function Getimgur($max, $cache_state, $keyword)
    {

        //Validation keyword
        $validator = Validation::createValidator();
        $violations = $validator->validate($keyword, [
            new Length([
                'min' => 3,
                'max' => 15,
                'minMessage' => 'The keyword must be at least {{ limit }} characters long',
                'maxMessage' => 'The keyword cannot be longer than {{ limit }} characters',
                'allowEmptyString' => false,
            ]),
            new Regex([
                'pattern' => '/^[a-zA-Z0-9\s]+$/i',
                'message' => 'The keyword cannot have special characters'
            ])
        ]);
        
        //invalid kwyword
        if (count($violations) > 0) {
            $error_msg = [];

            foreach ($violations as $violation)
                $error_msg[] = $violation->getMessage();

            return $this->json([
                'message' => ['text' => join("\n",$error_msg ), 'level' => 'error'],
            ]);
        }
        
        $imgUR = new ImgUR($max, $keyword);

        /**CACHE  states */
        /** -1  Not use at all */
        /**  0  try to get from cache if not exists get the imgur json and save */
        /**  1  Restrict json from the cache */
        $response_body = ($cache_state == -1)?
            $imgUR->GetFromGalleryAPI() : $imgUR->GetFromCache($cache_state);

        $response = new Response(
            $response_body, //ERRORS MSG into the imgUR Class
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }
}
