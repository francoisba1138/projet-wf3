<?php


namespace App\Controller\Admin;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController
 * @package App\Controller\Admin
 *
 * @Route("/admin/annonce")
 */
class AdController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('admin/ad/index.html.twig');


    }

    /**
     * @Route("/{id}")
     */
    public function detail(Ad $ad)
    {

        return $this->render('admin/ad/detail.html.twig');


    }





}