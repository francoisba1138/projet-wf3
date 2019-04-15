<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $repository = $this->getDoctrine()->getRepository(User::class);

        $buyers =$repository->findBy(
            [
                'role' => 'ROLE_BUYER'
            ]
        );

        return $this->render('admin/buyer/index.html.twig',

            [
                'buyers' => $buyers

            ]

            );


    }

    /**
     * @Route("/{id}")
     */
    public function detail(User $buyer)
    {
        $role= $buyer->getRole();

        if ($role=='ROLE_BUYER') {


            return $this->render('admin/buyer/detail.html.twig',
                [
                    'buyer' => $buyer

                ]
            );
        } else {

            return $this->redirectToRoute('app_index_index');
        }
    }


    /**
     * @Route("/edition/{id}", requirements={"id": "\d+"})
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $originalImage = null;

        $user = $em->find(User::class, $id);
        if( is_null($user) ) {
            throw new NotFoundHttpException();
        }
        // si l'article contient une image
        if( !is_null($user->getImage()) ) {
            // nom du fichier venant de la bdd
            $originalImage = $user->getImage();
            // on sette l'image avec un objet File sur l'emplacement de l'image
            // pour le traitement par le formulaire
            $user->setImage(
                new File($this->getParameter('upload_dir') . $originalImage)
            );

        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if( $form->isSubmitted() ) {
            if( $form->isValid() ) {
                /** @var UploadedFile $image */
                $image = $article->getImage();
                // s'il y a eu une image uploadée
                if( !is_null($image) ) {
                    // nom sous lequel on va enregistrer l'image
                    $filename = uniqid() . '.' . $image->guessExtension();
                    // déplace l'image uploadée
                    $image->move(
                    // vers le répertoire /public/images
                    // cf config/services.yaml
                        $this->getParameter('upload_dir'),
                        // nom du fichier
                        $filename
                    );
                    // on sette l'attribut image de l'article avec son nom
                    // pour enregistrement en bdd
                    $user->setImage($filename);
                    // en modification on supprime l'ancienne image
                    // s'il y en a une
                    if( !is_null($originalImage) ) {
                        unlink($this->getParameter('upload_dir') . $originalImage);
                    }
                } else {
                    // en modification, sans upload, on sette l'attribut image
                    // avec le nom de l'ancienne image
                    $article->setImage($originalImage);
                }
                $em->persist($article);
                $em->flush();
                $this->addFlash('success', "L'article est enregistré");
                return $this->redirectToRoute('app_admin_article_index');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        return $this->render(
            'admin/article/edit.html.twig',
            [
                'form' => $form->createView(),
                'original_image' => $originalImage
            ]
        );
    }



















    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, User $buyer)
    {

        if( $request->isXmlHttpRequest() ) {




            return $this->render(
                'admin/user/ajax_content.html.twig',
                ['buyer' => $buyer]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }


}