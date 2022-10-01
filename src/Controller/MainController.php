<?php

namespace App\Controller;

use App\Repository\UserPartenaireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserPartenaireRepository $repository): Response
    {

        $partenaire = $repository->findAll();



        return $this->render('main/index.html.twig', [
            'partenaires' => $partenaire
        ]);
    }
}
