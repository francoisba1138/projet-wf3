<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\BuyeradminType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function detail(User $buyer, Request $request)
    {
        $role= $buyer->getRole();
        $em = $this->getDoctrine()->getManager();
        $originalImage = null;


        if ($role=='ROLE_BUYER') {

            // si le buyer contient une image
            if( !is_null($buyer->getImage()) ) {
                // nom du fichier venant de la bdd
                $originalImage = $buyer->getImage();
                // on sette l'image avec un objet File sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $buyer->setImage(
                    new File($this->getParameter('profile_dir') . $originalImage)
                );
            }





            $form = $this->createForm(BuyeradminType::class, $buyer );
            $form->handleRequest($request);




            if( $form->isSubmitted()){

                dump($form->isValid());
                if( $form->isValid() ) {


                    /** @var UploadedFile $image */
                    $image = $buyer->getImage();

                    dump($image);


                    // s'il y a eu une image uploadée
                    if( !is_null($image) ) {

                        // nom sous lequel on va enregistrer l'image
                        $filename = uniqid() . '.' . $image->guessExtension();
                        // déplace l'image uploadée
                        $image->move(
                        // vers le répertoire /public/images.profile
                        // cf config/services.yaml
                            $this->getParameter('profile_dir'),
                            // nom du fichier
                            $filename
                        );
                        // on sette l'attribut image de l'article avec son nom
                        // pour enregistrement en bdd
                        $buyer->setImage($filename);

                        if( !is_null($originalImage) ) {
                            unlink($this->getParameter('profile_dir') . $originalImage);
                        }
                    } else {
                            // en modification, sans upload, on sette l'attribut image
                            // avec le nom de l'ancienne image
                            $buyer->setImage($originalImage);
                        }

                    $em->persist($buyer);
                    $em->flush();
                    $this->addFlash('success', "Le membre est enregistré");
                    return $this->redirectToRoute('app_admin_buyer_index');

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }

            return $this->render('admin/buyer/detail.html.twig',
                [

                    'form' => $form->createView(),
                    'original_image' => $originalImage,
                    'buyer' => $buyer

                ]
            );


        } else {

            return $this->redirectToRoute('app_index_index');
        }
    }



    /**
     * @Route("/suppression/{id}")
     */
    public function delete(User $buyer)
    {
        $em = $this->getDoctrine()->getManager();

        if( !is_null($buyer->getImage()) ) {


            unlink($this->getParameter('profile_dir') . $buyer->getImage());
        }


        $em->remove($buyer);
        $em->flush();
        $this->addFlash('success', "La fiche utilisateur est supprimée");
        return $this->redirectToRoute('app_admin_buyer_index');


    }



    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, User $buyer)
    {

        if( $request->isXmlHttpRequest()) {




            return $this->render(
                'admin/buyer/ajax_content.html.twig',
                ['buyer' => $buyer]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }

}