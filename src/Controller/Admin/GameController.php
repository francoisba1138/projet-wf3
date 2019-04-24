<?php


namespace App\Controller\Admin;

use App\Entity\Game;

use App\Form\GameadminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class GameController
 * @package App\Controller\Admin
 *
 * @Route("/admin/jeu")
 */
class GameController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $games =$repository->findAll();

        return $this->render('admin/game/index.html.twig',

            [
                'games' => $games

            ]


        );


    }
    /**
     * @Route("/{id}")
     */
    public function detail(Game $game, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $originalImage = null;

$date=$game->getDate();





            // si le game contient une image
            if( !is_null($game->getCover()) ) {
                // nom du fichier venant de la bdd
                $originalImage = $game->getCover();
                // on sette l'image avec un objet File sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $game->setCover(
                    new File($this->getParameter('cover_dir') . $originalImage)
                );
            }

            $form = $this->createForm(GameadminType::class, $game );
            $form->handleRequest($request);

            if( $form->isSubmitted()){


                if( $form->isValid() ) {


                    /** @var UploadedFile $cover */
                    $cover = $game->getCover();

                    dump($cover);


                    // s'il y a eu une image uploadée
                    if( !is_null($cover) ) {

                        // nom sous lequel on va enregistrer l'image
                        $filename = uniqid() . '.' . $cover->guessExtension();
                        // déplace l'image uploadée
                        $cover->move(
                        // vers le répertoire /public/images.cover
                        // cf config/services.yaml
                            $this->getParameter('cover_dir'),
                            // nom du fichier
                            $filename
                        );
                        // on sette l'attribut image de l'article avec son nom
                        // pour enregistrement en bdd
                        $game->setCover($filename);

                        if( !is_null($originalImage) ) {
                            unlink($this->getParameter('cover_dir') . $originalImage);
                        }
                    } else {
                        // en modification, sans upload, on sette l'attribut image
                        // avec le nom de l'ancienne image
                        $game->setCover($originalImage);
                    }

                    $em->persist($game);
                    $em->flush();
                    $this->addFlash('success', "Le jeu est enregistré");
                    return $this->redirectToRoute('app_admin_game_index');

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }

            return $this->render('admin/game/detail.html.twig',
                [

                    'form' => $form->createView(),
                    'original_image' => $originalImage,
                    'game'=>$game


                ]
            );



    }



    /**
     * @Route("/suppression/{id}")
     */
    public function delete(Game $game)
    {
        $em = $this->getDoctrine()->getManager();

        if( !is_null($game->getCover()) ) {


            unlink($this->getParameter('cover_dir') . $game->getCover());
        }


        $em->remove($game);
        $em->flush();
        $this->addFlash('success', "La fiche jeu est supprimée");
        return $this->redirectToRoute('app_admin_game_index');


    }


    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, Game $game)
    {

        if( $request->isXmlHttpRequest()) {

            return $this->render(
                'admin/game/ajax_content.html.twig',
                ['game' => $game]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }












}