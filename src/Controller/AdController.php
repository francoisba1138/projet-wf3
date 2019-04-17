<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController
 * @package App\Controller
 *
 * @Route("/annonce")
 */
class AdController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('ad/index.html.twig');
    }
}
