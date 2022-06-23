<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegistrationController extends AbstractController
{
    #[Route("/admin/membre/list" , name:"register_list")]
    public function list(EntityManagerInterface $entityManager) : Response{

        $membres = $entityManager->getRepository(User::class)->findAll();
        return $this->render("registration/list.html.twig" , [
            "membres" => $membres
        ]);

    }

    #[Route("/admin/membre/update/{id}" , name:"register_update" )]
    public function update($id){

    }

    #[Route("/admin/membre/suppr/{id}" , name:"register_suppr" )]
    public function suppr($id){
        
    }

    #[Route('/admin/membre/register', name: 'register_new')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles([$form->get("role")->getData()]);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('vehicule_list');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
