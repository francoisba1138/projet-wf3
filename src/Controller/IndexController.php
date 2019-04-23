<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $buyers =$repository->findBy(
            ['role' => 'ROLE_BUYER'],
            ['id' => 'DESC'],
            5
        );

        $sellers =$repository->findBy(
            ['role' => 'ROLE_SELLER'],
            ['id' => 'DESC'],
            5
        );

        $adrepository = $this->getDoctrine()->getRepository(Ad::class);

        $ads =$adrepository->findBy(
            [],
            ['id' => 'DESC'],
            5
        );

        return $this->render('index/index.html.twig',
        [
            'buyers' => $buyers,
            'sellers' => $sellers,
            'ads' => $ads
        ]
    );
    }


    /**
     * @Route("/contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);

        // pré-remplissage des champs
        if(!is_null($this->getUser())) {
            $form->get('firstname')->setData($this->getUser());
            $form->get('lastname')->setData($this->getUser());
            $form->get('email')->setData($this->getUser()->getEmail());
        }

        $form ->handleRequest($request);

        // si le form est soumis et si il est valide
        if($form->isSubmitted()){
            if($form->isValid()) {
                $data = $form->getData();


                $mail = $mailer->createMessage();
                $mail
                    ->setSubject('Nouveau message')
                    ->setFrom('contact@game.fr')
                    ->setTo('contact@game.fr')
                    ->setBody('$mailBody')
                    ->setReplyTo($data['email']);

                $mailer->send($mail);

                // on crée le template
                $mailBody = $this->renderView(
                    'index/contact_body.html.twig',
                    [
                        'data' => $data
                    ]
                );

                $this->addFlash('success','Votre message a bien été envoyé');
            }
            else{
                    $this->addFlash('error','Le formulaire contient des erreurs');
                }

        }


        return $this->render(
            'index/contact.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
