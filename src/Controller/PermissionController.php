<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Form\PermissionFormType;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends AbstractController
{

    #[Route('/permission', name: 'permission.show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(PermissionRepository $repository): Response
    {
        $permissions = $repository->findAll();

        return $this->render('pages/permission/permission_show.html.twig', [
            'permissions' => $permissions
        ]);
    }


    #[Route('/permission/create', name: 'permission.new', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {

        $permission = new Permission();

        $form = $this->createForm(PermissionFormType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()){
            $form->getData();

            $manager->persist($permission);
            $manager->flush();

            $this->addFlash(
                'success',
                'Permission créée'
            );

            return $this->redirectToRoute('permission.show');
        }

        return $this->render('pages/permission/permission_create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('permission/delete/{id}', name: 'permission.delete', methods: ['GET', 'POST'])]
    public function delete($id, PermissionRepository $repository, EntityManagerInterface $manager): Response
    {

        $permission = $repository->find($id);

        $manager->remove($permission);
        $manager->flush();

        $this->addFlash(
            'warning',
            'Permission supprimée !'
        );

        return $this->redirectToRoute('permission.show');
    }
}
