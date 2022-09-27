<?php

namespace App\Controller;

use App\Entity\UserPartenaire;
use App\Entity\UserStructure;
use App\Repository\UserPartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartenaireController extends AbstractController
{
    #[Route('/partenaire/{id}', name: 'partenaire.show')]
    public function index($id, UserPartenaireRepository $repository, UserStructure $structure): Response
    {

        $partenaire = $repository->find($id);

        $structurePart = $partenaire->getStructure()->toArray();





        if(!$partenaire){
            return $this->redirectToRoute('main');
        }

        return $this->render('partenaire/index.html.twig', [
            'partenaire' => $partenaire,
            'structures' => $structurePart
        ]);
    }
}
