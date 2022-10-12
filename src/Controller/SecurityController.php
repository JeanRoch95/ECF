<?php

namespace App\Controller;

use App\Repository\UserAdminRepository;
use App\Repository\UserPartenaireRepository;
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
    public function login(AuthenticationUtils $authenticationUtils, UserPartenaireRepository $partenaireRepository): Response
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

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/magic', name: 'magic_log')]
    public function magic(UserAdminRepository $adminRepository, LoginLinkHandlerInterface $loginLinkHandler, MailerInterface $mailer ): Response
    {
        $users = $adminRepository->findAll();

        foreach ($users as $user) {
            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
            $email = (new Email())
                ->from('bot@test.com')
                ->to($user->getEmail())
                ->subject('Magic login link')
                ->text('You can use this link to login: ' . $loginLinkDetails->getUrl());
            $mailer->send($email);
        }

        return new Response('Magic!');
    }
}
