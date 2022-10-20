<?php

namespace App\Controller;

use App\Repository\UserAdminRepository;
use App\Repository\UserPartenaireRepository;
use App\Repository\UserStructureRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
class SecurityController extends AbstractController
{

    #[Route(path: 'login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_login');
    }


}
