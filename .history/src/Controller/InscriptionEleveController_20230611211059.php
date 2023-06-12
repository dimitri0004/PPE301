<?php

namespace App\Controller;

use App\Entity\UsersEleves;
use App\Form\RegistrationFormEleveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class InscriptionEleveController extends AbstractController
{
    #[Route('/inscription', name: 'inscription_eleve')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new UsersEleves();
        $form = $this->createForm(RegistrationFormEleveType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();
            
           
            $user->setPassword($plainPassword);
            $user->setRoles(['ROLE_ElEVE']);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('inscription_eleve');
        }

        return $this->render('secretaire/inscription_eleve/registerEleve.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
