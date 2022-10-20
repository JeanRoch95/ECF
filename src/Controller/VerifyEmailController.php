<?php

namespace App\Controller;

use App\Entity\UserPartenaire;
use App\Repository\UserPartenaireRepository;
use App\Repository\UserStructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyEmailController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }


    #[Route('verify', name: 'registration.verify.structure')]
    public function verifyStructureEmail(Request $request, UserStructureRepository $structureRepository, EntityManagerInterface $manager): Response
    {
        $id = $request->get('id');


        if(null === $id){
            return $this->redirectToRoute('logout');
        }
        $user = $structureRepository->find($id);

        if(null === $user){
            return $this->redirectToRoute('structure.show', ['id' => $id]);
        }



        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_login');
        }

        $user->setIsVerified(1);
        $manager->flush($user);

        $this->addFlash('success', 'Votre email a bien été vérifié');

        return $this->redirectToRoute('structure.edit.password', ['id' => $id]);
    }

    #[Route('checked', name: 'registration.verify.partenaire')]
    public function verifyPartenaireEmail(Request $request,UserPartenaireRepository $partenaireRepository, EntityManagerInterface $manager): Response
    {
        $id = $request->get('id');


        if(null === $id){
            return $this->redirectToRoute('logout');
        }
        $user = $partenaireRepository->find($id);

        if(null === $user){
            return $this->redirectToRoute('partenaire.show.show', ['id' => $id]);
        }



        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_login');
        }

        $user->setIsVerified(1);
        $manager->flush($user);

        $this->addFlash('success', 'Votre email a bien été vérifié');

        return $this->redirectToRoute('partenaire.edit.password', ['id' => $id]);
    }
}
