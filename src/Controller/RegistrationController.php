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
use App\Service\FileUploader;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
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

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_list_users');
        }

        return $this->render('registration/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
