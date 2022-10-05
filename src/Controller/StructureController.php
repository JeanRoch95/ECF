<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\Status;
use App\Entity\UserStructure;
use App\Repository\PermissionRepository;
use App\Repository\StatusRepository;
use App\Repository\UserStructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/structure/{id}', name: 'structure.show')]
    #[Security("is_granted('ROLE_STRUCTURE') || user === structure.getUserPartenaire() || is_granted('ROLE_ADMIN') ")]
    public function index($id,UserStructure $structure, PermissionRepository $permissionRepository, UserStructureRepository $repository): Response
    {

        $structure = $repository->find($id);

        $permission = $structure->getPermissions()->toArray();


        if(!$structure){
            $this->redirectToRoute('main');
        }

        return $this->render('structure/index.html.twig', [
            'structures' => $structure,
            'permissions' => $permission
        ]);
    }

    #[Route('/permuted/structure/{id}', name: 'permuted_structure', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[Entity('structure', expr: 'repository.find(id)')]
    public function permutedStatus($id, UserStructure $structure, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        if($structure->isStatus() == 0){
            $etat = 1;
        } else
        {
            $etat = 0;
        }
        $structure->setStatus($etat);
        $manager->flush();



        return $this->redirectToRoute('structure.show', ['id' => $structure->getId()]);
    }

    #[Route('/permuted/structure/permission/{id}/{perm_id}', name: 'permuted_permission_structure', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[Entity('structure', expr: 'repository.find(id)')]
    public function permutedStatusPermission($id, $perm_id ,Permission $permission, PermissionRepository $permissionRepository, UserStructure $structure, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $permission = $permissionRepository->find($perm_id);

        if($permission->getStatus()->getStatusPermId() === 0){
            $etat = 1;
        } else
        {
            $etat = 0;
        }
        $permission->getStatus()->setActive($etat);
        $manager->persist($permission);
        $manager->flush();

        return $this->redirectToRoute('structure.show', ['id' => $structure->getId()]);
    }
}
