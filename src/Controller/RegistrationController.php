<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPartenaire;
use App\Entity\UserStructure;
use App\Form\PartenaireRegistrationFormType;
use App\Form\StructureRegistrationFormType;
use App\Form\UserPartenaireEditType;
use App\Form\UserStructureEditType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_structure.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/inscription/partenaire', name: 'partenaire.registry')]
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

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
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
    #[Security("is_granted('ROLE_STRUCTURE' and user === choosenUser) || user === choosenUser.getUserPartenaire() || is_granted('ROLE_ADMIN')")]
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
}
