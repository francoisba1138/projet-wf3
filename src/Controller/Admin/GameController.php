<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameController
 * @package App\Controller\Admin
 *
 * @Route("/admin/jeu")
 */
class GameController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('admin/seller/index.html.twig');


    }





}