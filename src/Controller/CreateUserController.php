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
use App\Service\FileUploader;

class CreateUserController extends AbstractController
{
    #[Route('/user/create', name: 'app_create_user', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher,
        FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $fileUploader->setTargetDirectory('public/uploads/profile-image');

        if ($form->isSubmitted() && $form->isValid()) {
            $profileImage = $form->get('profileImage')->getData();

            if ($profileImage) {
                $profileImageName = $fileUploader->upload($profileImage);
                $user->setProfileImageRoute($profileImageName);
            } else {
                $user->setProfileImageRoute('public/uploads/profile-image/default-avatar.jpg');
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_list_users');
        }

        return $this->render('create_user/index.html.twig', [
            'createForm' => $form->createView()
        ]);
    }
}
