<?php

namespace App\Controller;

use Buzz\Browser;
use Buzz\Client\BuzzClientInterface;
use Buzz\Client\FileGetContents;
use GuzzleHttp\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{



    /**
     * id du jeu
     * @Route("/api/jeu/{id}")
     */
    public function gameDetails($id)
    {
        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());




        $apikey=
            [
            'user-key' => '128f06a547525a39878205e49b57fa50',
            "Accept" => "application/json"
            ];


        // recuperation des données d'un jeu par id jeu
        // id,slug,name,platforms,storyline,summary,cover,first_release_date,popularity,rating,similar_games,genres,
        $game = $browser->get(
            "https://api-v3.igdb.com/games/" . $id
            ."?fields=id,slug,name,platforms,storyline,summary,cover,first_release_date,popularity,rating,similar_games,genres",
            $apikey
        );

        $game=json_decode($game->getBody()->getContents(), true)[0];




        // recuperation des données couverture de jeu par id cover


        if ( array_key_exists('cover',$game)) {
            $cover = $browser->get(
                "https://api-v3.igdb.com/covers/" . $game['cover'] . "?fields=*",

                $apikey
            );
            $cover = json_decode($cover->getBody()->getContents(), true)[0];

            $coverName = $cover['image_id'];
        }else {

            $coverName = "nocover_qhhlj6";

        }


        // recuperation des données plateforme id jeu

        if ( array_key_exists('platforms',$game)) {

            $platforms = $game['platforms'];

            // dump($platforms);


            // recuperation des données plateformes par id plateforme

            foreach ($platforms as $platform) {

                $platformName = $browser->get(
                    "https://api-v3.igdb.com/platforms/" . $platform . "?fields=abbreviation",

                    $apikey
                );
                $platformName = json_decode($platformName->getBody()->getContents(), true);

                $platformName = $platformName[0]['abbreviation'];

                $platformNames[$platform] = $platformName;


            }

        }else {

            $platformNames[] = 'nc';

        }







// enregistrement des données dans array game

        if ( !array_key_exists('first_release_date',$game)) {
            $game['first_release_date']='-2208988800';

        }

    $game['cover_url']="https://images.igdb.com/igdb/image/upload/t_cover_big/" . $coverName . ".jpg";
    $game['platform_names']=$platformNames;





        return $this->render(
            'api/game_details.html.twig', [
            'game' => $game
        ]);






    }


   /**
    *
    *
     * @Route("/api/search/{name}/{scroll}", defaults={"scroll"=0})
     */
    public function games($name,$scroll)

    {
        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        $response = $browser->get(
            "https://api-v3.igdb.com/games/?fields=id,name&limit=50&offset=". $scroll*50 ."&search=" . $name,
//?fields=id,name,slug,storyline,summary,screenshots,first_release_date
                [
                    'user-key' => '128f06a547525a39878205e49b57fa50',
                    "Accept" => "application/json"
                ]
        );


        dump($response);




        return $this->render(
            'api/index.html.twig', [
            'response' => json_decode($response->getBody()->getContents()),
                'page' => $scroll,
            'request' => $name
        ]);
    }

    /**
     * id du jeu (possibilité d'utiliser le slug)
     * @Route("/api/game/{id}")
     */
    public function game($id)
    {
        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        $response = $browser->get(
            "https://api-v3.igdb.com/games/" . $id ."?fields=*",

            [
                'user-key' => '128f06a547525a39878205e49b57fa50',
                "Accept" => "application/json"
            ]
        );


        return $this->render(
            'api/game.html.twig', [
            'response' => json_decode($response->getBody()->getContents())
        ]);
    }
    /**
     * id de la plateforme
     * @Route("/api/platform/{id}")
     */
    public function platform($id)
    {
        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        $response = $browser->get(
            "https://api-v3.igdb.com/platforms/" . $id ."?fields=*",

            [
                'user-key' => '128f06a547525a39878205e49b57fa50',
                "Accept" => "application/json"
            ]
        );


        return $this->render(
            'api/game.html.twig', [
            'response' => json_decode($response->getBody()->getContents())
        ]);
    }



    /**
     * id de la cover
     * @Route("/api/cover/{id}")
     */
    public function cover($id)
    {
        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        $response = $browser->get(
            "https://api-v3.igdb.com/covers/" . $id ."?fields=*",

            [
                'user-key' => '128f06a547525a39878205e49b57fa50',
                "Accept" => "application/json"
            ]
        );


        return $this->render(
            'api/game.html.twig', [
            'response' => json_decode($response->getBody()->getContents())
        ]);
    }



}
