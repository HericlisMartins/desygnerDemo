<?php

namespace App\Controller\Utils;

use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '\..\..\..\.env.desygner');

class ImgUR
{
    private $keyword;
    private $max;
    private $cache_key;
    private $imgURPage;
    private $imgURnum;

    function __construct($max, $keyword)
    {
        $this->keyword = $keyword;
        $this->max = $max;
        $this->cache_key = 'imgur_'.$keyword;
        $this->imgURPage = 0;
        $this->imgURnum = 0;
    }

    private function FormatData($json_data)
    {
        $json_response = array();

        foreach ($json_data as $image) {

            if ($image['is_album'])/*FROM ALBUNS*/
                $image = $image['images'][0]; // first image

            $description = $image['description'];
            $title = $image['title'];
            $url = $image['link'];

            $description = ($description == null) ? $this->keyword : $description; //replace by keyword if null
            $title = ($title == null) ? $this->keyword : $title; //replace by keyword if null
            $description = substr($description, 0, 30); // MAX CHAR 30
            $title = substr($title, 0, 12); // MAX CHAR 10

            $json_response[] = array('url' => $url, 'title' => $title, 'description' => $description);
            $this->imgURnum++;
                
            if ($this->max == $this->imgURnum)
                break;
        }
        
        if($this->imgURnum < $this->max ){
            $this->imgURPage++;
            $json_response=array_merge(json_decode($this->GetFromGalleryAPI(), true), $json_response);
        }

        if (!$this->imgURnum)
            return false;
        else
            return json_encode($json_response);
    }

    public function GetFromGalleryAPI()
    {

        $client = HttpClient::create();

        $clienId    = $_ENV['IMGUR_CLIENTID'];
        $imgurAPI   = $_ENV['IMGUR_API'];

        $response = $client->request(
            'GET',
            $imgurAPI.$this->imgURPage,
            [
                'query' => ['q' => $this->keyword, 'q_type' => 'jpg'],
                'headers' => ['Authorization' => 'Client-ID ' . $clienId]
            ]
        );

        $imgUR_response = json_decode($response->getContent(), true);
        $imgUR_response = $imgUR_response['data'];

        //return false if not found imgs or the json data alredy formated. 
        $json_data = $this->FormatData($imgUR_response);

        if (!$json_data)
            return json_encode(['message' => ['text' => 'No satisfactory results were found using the keyword', 'level' => 'error']]);
        else
            return $json_data;
    }


    public function GetFromCache($state)
    {
        
        $cache = new ApcuAdapter($this->cache_key); 
        $imgs_cache = $cache->getItem($this->cache_key); //CACHE ITEM KEY

        if ($imgs_cache->isHit())
        {   
            echo"Cache FOUND";
            //RETURN VALUE FROM THE CACHE
            return $imgs_cache->get();
        }
        else if ($state==1){ // cache not hit and the request only accepts return from the cache.
            return json_encode([
                'message' => ['text' => 'Keyword "'.$this->keyword.' " was not found within cache', 'level' => 'error']
            ]);
        }
        else{   //get new objects and save cache
            echo "cache not found creating...";
            $json = $this->GetFromGalleryAPI();
            $imgs_cache->set($json);
            $cache->save($imgs_cache);
            return $json;
        }

    }
}
