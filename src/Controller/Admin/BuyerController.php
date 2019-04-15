<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyerController
 * @package App\Controller\Admin
 *
 * @Route("/admin/membre")
 */
class BuyerController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('admin/buyer/index.html.twig');


    }





}