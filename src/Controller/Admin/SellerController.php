<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SellerController
 * @package App\Controller\Admin
 *
 * @Route("/admin/vendeur")
 */
class SellerController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('admin/seller/index.html.twig');


    }





}