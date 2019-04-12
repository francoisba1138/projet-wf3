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
     * @Route("/api/search")
     */
    public function index()
    {

        $client = new FileGetContents(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        $response = $browser->get(
            "https://api-v3.igdb.com/games/count?filter[rating][gt]=75",

            [
                'user-key' => '128f06a547525a39878205e49b57fa50',
                "Accept" => "application/json"
            ]
        );


        return $this->render(
            'api/index.html.twig', [
            'response' => json_decode($response->getBody()->getContents())
        ]);
    }
}
