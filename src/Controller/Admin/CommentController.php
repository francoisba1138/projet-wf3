<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller\Admin
 *
 * @Route("/admin/commentaire")
 */
class CommentController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('admin/comment/index.html.twig');


    }


    /**
     * @Route("/{id}")
     */
    public function detail()
    {

        return $this->render('admin/comment/detail.html.twig');


    }





}