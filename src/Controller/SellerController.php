<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\SellereditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class SellerController
 * @package App\Controller
 *
 * @Route("/vendeur")
 */
class SellerController extends AbstractController
{
    /**
     * @Route("/")
     *
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        $sellers =$repository->findBy(
            [
                'role' => 'ROLE_SELLER'
            ]

        );


        return $this->render('seller/index.html.twig',
            [
            'sellers' => $sellers,
            ]
        );


    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function detail(User $seller)
    {

        $role= $seller->getRole();
        $ads = $seller->getSellerAds();
        $comments = $seller->getUserComment();
        $collection = $seller->getCollection();









        if ($role=='ROLE_SELLER') {


            return $this->render('seller/profile.html.twig',
                [
                    'seller' => $seller,
                    'ads' => $ads,
                    'comments' => $comments,
                    'collection' => $collection
                ]
            );

        } else {

            return $this->redirectToRoute('app_index_index');
        }

    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(User $seller, Request $request)
    {
        $role= $seller->getRole();
        $em = $this->getDoctrine()->getManager();
        $originalImage = null;

        if ($role=='ROLE_SELLER') {

            // si le buyer contient une image
            if( !is_null($seller->getImage()) ) {
                // nom du fichier venant de la bdd
                $originalImage = $seller->getImage();
                // on sette l'image avec un objet File sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $seller->setImage(
                    new File($this->getParameter('profile_dir') . $originalImage)
                );
            }

            $form = $this->createForm(SellereditType::class, $seller );
            $form->handleRequest($request);

            if( $form->isSubmitted()){

                dump($form->isValid());
                if( $form->isValid() ) {
                    /**
                     * @var UploadedFile $image
                     */
                    $image = $seller->getImage();

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
                        $seller->setImage($filename);

                        if( !is_null($originalImage) ) {
                            unlink($this->getParameter('profile_dir') . $originalImage);
                        }
                    } else {
                        // en modification, sans upload, on sette l'attribut image
                        // avec le nom de l'ancienne image
                        $seller->setImage($originalImage);
                    }

                    $em->persist($seller);
                    $em->flush();
                    $this->addFlash('success', "Vos informations sont bien enregistrées");
                    return $this->redirectToRoute('app_seller_detail',['id'=> $seller->getId()]);

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }

            return $this->render('seller/edit.html.twig',
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
