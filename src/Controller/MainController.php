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
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    #[Route('/', name: 'main')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, UserPartenaireRepository $repository, PaginatorInterface $paginator): Response
    {

        $limit = 10;

        $page = $request->query->get("page", 1);

        $filter = $request->get("status");

        $partenaires = $repository->getPaginatedPart($page, $limit, $filter);

        $total = $repository->getTotalPart($filter);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('_partials/_content.html.twig', compact('partenaires', 'total', 'filter', 'page', 'limit'))
            ]);
        }

        return $this->render('main/index.html.twig', compact('partenaires', 'total', 'limit', 'page'));
    }

}
