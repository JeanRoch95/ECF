<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPartenaire;
use App\Entity\UserStructure;
use App\Form\PartenaireRegistrationFormType;
use App\Form\StructureRegistrationFormType;
use App\Form\UserPartenaireEditType;
use App\Form\UserPartenairePasswordType;
use App\Form\UserStructureEditType;
use App\Form\UserStructurePasswordType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription/structure', name: 'structure.registry', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function registerStructure(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new UserStructure();
        $form = $this->createForm(StructureRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('main');
        }

        return $this->render('registration/register_structure.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/inscription/partenaire', name: 'partenaire.registry')]
    #[IsGranted('ROLE_ADMIN')]
    public function registerPartenaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new UserPartenaire();
        $form = $this->createForm(PartenaireRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('main');
        }

        return $this->render('registration/register_partenaire.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('edition/partenaire/{id}', name: 'partenaire.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_PARTENAIRE') and user === choosenUser || is_granted('ROLE_ADMIN')")]

    public function editPartenaire(UserPartenaire $choosenUser,
                                   Request $request,
                                   EntityManagerInterface $manager,
                                   )
    {
        $form = $this->createForm(UserPartenaireEditType::class, $choosenUser);

        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('partenaire.show', ['id' => $choosenUser->getId()]);

        }
        return $this->render('registration/edit_partenaire.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('edition/structure/{id}', name: 'structure.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_STRUCTURE') and user === choosenUser || user === choosenUser.getUserPartenaire() || is_granted('ROLE_ADMIN')")]
    public function editStructure(UserStructure $choosenUser,
                                  Request $request,
                                  EntityManagerInterface $manager,

                                  )
    {
        $form = $this->createForm(UserStructureEditType::class, $choosenUser);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('structure.show', ['id' => $choosenUser->getId()]);

        }
        return $this->render('registration/edit_structure.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('edit/structure/password/{id}', name: 'structure.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_STRUCTURE') and user === structure")]
    public function editStructurePassword(EntityManagerInterface $manager, Request $request, UserStructure $structure, UserPasswordHasherInterface $hasher): Response
    {

        $form = $this->createForm(UserStructurePasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){
            if($hasher->isPasswordValid($structure, $form->getData()['plainPassword'])){
                    $structure->setPassword(
                        $hasher->hashPassword(
                            $structure,
                            $form->getData()['newPassword']
                        )
                );
                $manager->persist($structure);
                $manager->flush();
                return $this->redirectToRoute('structure.show', ['id' => $structure->getId()]);
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('registration/structure_edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('edit/partenaire/password/{id}', name: 'partenaire.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_PARTENAIRE') and user === partenaire")]

    public function editPassword(EntityManagerInterface $manager, Request $request, UserPartenaire $partenaire, UserPasswordHasherInterface $hasher): Response
    {

        $form = $this->createForm(UserPartenairePasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){
            if($hasher->isPasswordValid($partenaire, $form->getData()['plainPassword'])){
                $partenaire->setPassword(
                    $hasher->hashPassword(
                        $partenaire,
                        $form->getData()['newPassword']
                    )
                );
                $manager->persist($partenaire);
                $manager->flush();
                return $this->redirectToRoute('partenaire.show', ['id' => $partenaire->getId()]);
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('registration/partenaire_edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
