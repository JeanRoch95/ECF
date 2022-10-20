<?php

namespace App\Controller;

use App\Repository\UserPartenaireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, UserPartenaireRepository $repository): Response
    {


        $filter = $request->get("status");

        $partenaires = $repository->getPaginatedPart($filter);

        $total = $repository->getTotalPart($filter);


        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('_partials/_content.html.twig', compact('partenaires', 'total'))
            ]);
        }

        $partenaire = $repository->findAll();

        return $this->render('main/index.html.twig', compact('partenaires', 'total'));
    }

    #[Route('partenaire/search', name: 'partenaire.search', methods: ['GET', 'POST'])]
    public function search(Request $request, UserPartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {

    }
}
