<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserController extends AbstractController
{
    #[Route('/user/create', name: 'app_create_user', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setProfileImageRoute('');

            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_list_users');
        }

        return $this->render('create_user/index.html.twig', [
            'createForm' => $form->createView()
        ]);

        // return $this->render('create_user/index.html.twig', [
        //     'controller_name' => 'CreateUserController',
        // ]);
    }
}
