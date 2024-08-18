<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\Paginator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Enums\RankEnum;

class ListUsersController extends AbstractController
{
    #[Route('/users', name: 'app_list_users')]
    public function index(Request $request, EntityManagerInterface $em, Paginator $paginator): Response
    {
        $query = $em->getRepository(User::class)->getAllQuery();
        $paginator->paginate($query, $request->query->get('page', 1));

        return $this->render('list_users/index.html.twig', [
            'controller_name' => 'ListUsersController',
            'paginator' => $paginator,
            'rankEnum' => RankEnum::class
        ]);
    }
}
