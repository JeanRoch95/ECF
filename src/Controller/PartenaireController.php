<?php

namespace App\Controller;

use App\Entity\UserPartenaire;
use App\Repository\UserPartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartenaireController extends AbstractController
{
    #[Route('/partenaire/{id}', name: 'partenaire.show')]
    #[Security("is_granted('ROLE_ADMIN') || is_granted('ROLE_PARTENAIRE')")]
    public function index($id, UserPartenaireRepository $repository): Response
    {


        $partenaire = $repository->find($id);

        $structurePart = $partenaire->getStructure()->toArray();


        if (!$partenaire) {
            return $this->redirectToRoute('main');
        }

        return $this->render('partenaire/index.html.twig', [
            'partenaire' => $partenaire,
            'structures' => $structurePart
        ]);
    }



    #[Route('/permuted/{id}', name: 'permuted', methods: ['GET', 'POST'])]
    #[Entity('partenaire', expr: 'repository.find(id)')]
    #[IsGranted('ROLE_ADMIN')]
    public function permutedStatus($id, UserPartenaire $partenaire, EntityManagerInterface $manager, UserPartenaireRepository $repository): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        if($partenaire->getStatus() == 0){
            $etat = 1;
        } else
        {
            $etat = 0;
        }
        $partenaire->setStatus($etat);
        $manager->flush();




        return $this->redirectToRoute('partenaire.show', ['id' => $partenaire->getId()]);
    }

}
