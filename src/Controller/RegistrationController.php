<?php

namespace App\Controller;

use App\Entity\UserPartenaire;
use App\Entity\UserStructure;
use App\Form\PartenaireRegistrationFormType;
use App\Form\StructureRegistrationFormType;
use App\Form\UserPartenaireEditType;
use App\Form\UserPartenairePasswordType;
use App\Form\UserStructureEditType;
use App\Form\UserStructurePasswordType;
use App\service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/inscription/structure', name: 'structure.registry', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function registerStructure(Request $request,
                                      UserPasswordHasherInterface $userPasswordHasher,
                                      EntityManagerInterface $entityManager,
                                      MailService $service): Response
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

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration.verify.structure',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            $service->sendEmail(
                $user->getEmail(),
                'pages/registration/confirmation_email.html.twig',
                ['signedUrl' => $signatureComponents->getSignedUrl()],
                'Confirmation de votre Email'

            );

            $this->addFlash(
                'success',
                'Structure Crée'
            );

            return $this->redirectToRoute('main');
        }


        return $this->render('pages/registration/structure/register_structure.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/inscription/partenaire', name: 'partenaire.registry')]
    #[IsGranted('ROLE_ADMIN')]
    public function registerPartenaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailService $service): Response
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

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration.verify.partenaire',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            $service->sendEmail(
                $user->getEmail(),
                'pages/registration/confirmation_email.html.twig',
                ['signedUrl' => $signatureComponents->getSignedUrl()],
                'Confirmation de votre Email'
            );

            $this->addFlash(
                'success',
                'Partenaire Crée'
            );

            return $this->redirectToRoute('main');
        }

        return $this->render('pages/registration/partenaire/register_partenaire.html.twig', [
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

            $this->addFlash(
                'success',
                'Partenaire Modifié'
            );

                return $this->redirectToRoute('partenaire.show', ['id' => $choosenUser->getId()]);

        }
        return $this->render('pages/registration/partenaire/edit_partenaire.html.twig', [
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

            $this->addFlash(
                'success',
                'Structure modifiée'
            );

                return $this->redirectToRoute('structure.show', ['id' => $choosenUser->getId()]);

        }
        return $this->render('pages/registration/structure/edit_structure.html.twig', [
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

                $this->addFlash(
                    'success',
                    'Mot de passe modifié'
                );

                return $this->redirectToRoute('structure.show', ['id' => $structure->getId()]);
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('pages/registration/structure/structure_edit_password.html.twig', [
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

                $this->addFlash(
                    'success',
                    'Mot de passe modifié'
                );

                return $this->redirectToRoute('partenaire.show', ['id' => $partenaire->getId()]);
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('pages/registration/partenaire/partenaire_edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
