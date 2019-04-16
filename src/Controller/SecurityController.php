<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", defaults={"id": null}, requirements={"id": "\d+"}))
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $user = new User();
        $originalImage = null;
        $em = $this->getDoctrine()->getManager();

            // si le buyer contient une image
            if( !is_null($user->getImage()) ) {
                // nom du fichier venant de la bdd
                $originalImage = $user->getImage();
                // on sette l'image avec un objet File sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $user->setImage(
                    new File($this->getParameter('profile_dir') . $originalImage)
                );
            }

        $form = $this->createForm(UserType::class, $user,[
            'validation_groups' => ['registration']
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UploadedFile $image */
                $image = $user->getImage();
                // s'il y a eu une image uploadée
                if (!is_null($image)) {
                    // nom sous lequel on va enregistrer l'image
                    $filename = uniqid() . '.' . $image->guessExtension();

                    // déplace l'image uploadée
                    $image->move(
                    // vers le répertoire /public/images
                    // cf config/services.yaml
                        $this->getParameter('profile_dir'),
                        // nom du fichier
                        $filename
                    );

                    // on sette l'attribut image de l'article avec son nom
                    // pour enregistrement en bdd
                    $user->setImage($filename);

                    // en modification on supprime l'ancienne image
                    // s'il y en a une
                    if (!is_null($originalImage)) {
                        unlink($this->getParameter('profile_dir') . $originalImage);
                    }
                } else {
                    // en modification, sans upload, on sette l'attribut image
                    // avec le nom de l'ancienne image
                    $user->setImage($originalImage);
                }

                // encode le mot de passe à partir de la config "encoders"
                // de config/packages/security.yaml
                $password = $passwordEncoder->encodePassword(
                    $user,
                    $user->getPlainPassword()
                );

                $user->setPassword($password);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre compte est créé');

                return $this->redirectToRoute('app_index_index');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render(
            'security/register.html.twig',
            [
                'form' => $form->createView(),
                'original_image' => $originalImage
            ]
        );
    }

    /**
     * @Route("/connexion")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // traitement du formulaire par Security
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if (!empty($error)) {
            $this->addFlash('error', 'Identifiants incorrects');
        }

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername
            ]
        );
    }

    /**
     * @Route("/deconnexion")
     */
    public function logout()
    {
        // cette méthode peut rester vide, il faut juste que sa route existe
        // pour être passée dans la section logout de config/packages/security.yaml
    }
}
