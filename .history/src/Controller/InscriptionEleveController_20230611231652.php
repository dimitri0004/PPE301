<?php

namespace App\Controller;

use App\Entity\UsersEleves;
use App\Form\RegistrationFormEleveType;
use App\Repository\UsersElevesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/inscription/eleve', name: 'inscription_eleve_')]
class InscriptionEleveController extends AbstractController
{
    #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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

            return $this->redirectToRoute('inscription_eleve_ajout');
        }

        return $this->render('secretaire/inscription_eleve/registerEleve.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('', name: 'liste')]
    public function liste(UsersElevesRepository $usersElevesRepository): Response
        {
            $inscriptions = $usersElevesRepository->findBy([],[]);
            return $this->render('secretaire/inscription_eleve/listeEleve.html.twig', compact('inscriptions') 
            );
        }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(Request $request,UsersEleves $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
           
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
    
                return $this->redirectToRoute('inscription_eleve_liste');
            }
    
            return $this->render('secretaire/inscription_eleve/ModifierEleve.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    
}
