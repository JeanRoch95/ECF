<?php

namespace App\Controller;

use App\Repository\UserPartenaireRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, UserPartenaireRepository $repository, PaginatorInterface $paginator): Response
    {

        $filter = $request->get("status");

        $total = $repository->getTotalPart($filter);

        $partenaires = $paginator->paginate(
            $repository->getPaginatedPart($filter), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5/*limit per page*/
        );

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('_partials/_content.html.twig', compact('partenaires', 'total'))
            ]);
        }

        return $this->render('main/index.html.twig', compact('partenaires', 'total'));
    }

    #[Route('partenaire/search', name: 'partenaire.search', methods: ['GET', 'POST'])]
    public function search(Request $request, UserPartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {

    }
}
