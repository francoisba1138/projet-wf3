<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SelleradminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        $repository = $this->getDoctrine()->getRepository(User::class);

        $sellers =$repository->findBy(
            [
                'role' => 'ROLE_SELLER'
            ]
        );

        return $this->render('admin/seller/index.html.twig',

            [
                'sellers' => $sellers

            ]


        );


    }
    /**
     * @Route("/{id}")
     */
    public function detail(User $seller, Request $request)
    {
        $role= $seller->getRole();
        $em = $this->getDoctrine()->getManager();
        $originalImage = null;


        if ($role=='ROLE_SELLER') {

            // si le seller contient une image
            if( !is_null($seller->getImage()) ) {
                // nom du fichier venant de la bdd
                $originalImage = $seller->getImage();
                // on sette l'image avec un objet File sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $seller->setImage(
                    new File($this->getParameter('profile_dir') . $originalImage)
                );
            }





            $form = $this->createForm(SelleradminType::class, $seller );
            $form->handleRequest($request);




            if( $form->isSubmitted()){

                dump($form->isValid());
                if( $form->isValid() ) {


                    /** @var UploadedFile $image */
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
                    $this->addFlash('success', "Le vendeur est enregistré");
                    return $this->redirectToRoute('app_admin_seller_index');

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }

            return $this->render('admin/seller/detail.html.twig',
                [

                    'form' => $form->createView(),
                    'original_image' => $originalImage,
                    'seller'=> $seller

                ]
            );


        } else {

            return $this->redirectToRoute('app_index_index');
        }
    }



    /**
     * @Route("/suppression/{id}")
     */
    public function delete(User $seller)
    {
        $em = $this->getDoctrine()->getManager();

        if( !is_null($seller->getImage()) ) {


            unlink($this->getParameter('profile_dir') . $seller->getImage());
        }


        $em->remove($seller);
        $em->flush();
        $this->addFlash('success', "La fiche vendeur est supprimée");
        return $this->redirectToRoute('app_admin_seller_index');


    }


    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, User $seller)
    {

        if( $request->isXmlHttpRequest()) {

            return $this->render(
                'admin/seller/ajax_content.html.twig',
                ['seller' => $seller]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }












}