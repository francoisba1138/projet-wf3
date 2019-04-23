<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BuyereditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyerController
 * @package App\Controller
 *
 * @Route("/membre")
 *
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

        return $this->render('buyer/index.html.twig',
            [
                'buyers' => $buyers

            ]

        );
    }



    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
     public function detail(User $buyer)
    {
        $role= $buyer->getRole();
        $collection= $buyer->getCollection();
        $comments= $buyer->getUserComment();

        dump($collection);
        dump($comments);


        if ($role=='ROLE_BUYER') {


        return $this->render('buyer/profile.html.twig',
            [
                'buyer' => $buyer,
                'collection' => $collection,
                'comments' => $comments,

            ]
        );
    } else {

    return $this->redirectToRoute('app_index_index');
    }


    }


    /**
     * @Route("/edit/{id}")
     */
    public function edit(User $buyer, Request $request)
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


            $form = $this->createForm(BuyereditType::class, $buyer );
            $form->handleRequest($request);


            if( $form->isSubmitted()){

                dump($form->isValid());
                if( $form->isValid() ) {


                    /**
                     * @var UploadedFile $image
                     */
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
                    $this->addFlash('success', "Vos informations sont bien enregistrées");
                    return $this->redirectToRoute('app_buyer_detail',['id'=> $buyer->getId()]);

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }

            return $this->render('buyer/edit.html.twig',
                [

                    'form' => $form->createView(),
                    'original_image' => $originalImage

                ]
            );


        } else {

            return $this->redirectToRoute('app_index_index');
        }

    }


}
