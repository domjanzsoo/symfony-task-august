<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Exception;
use App\Utils\Paginator;

class DeleteUserController extends AbstractController
{
    #[Route('/user/delete', name: 'app_delete_user')]
    public function index(Request $request, EntityManagerInterface $em, Paginator $paginator): Response
    {
        $em->getConnection()->beginTransaction();

        try {
            $repo = $em->getRepository(User::class);

            $repo->deleteUsers($request->request->all('userSelect'));

            $getAllQuery = $repo->getAllQuery($request->get('search', null));
            $page = !empty($request->get('page')) && is_numeric($request->get('page')) ? $request->get('page') : 1;
            $paginator->paginate($getAllQuery, $page);

            $em->getConnection()->commit();

            return $this->render('list_users/index.html.twig', [
                'controller_name' => 'ListUsersController',
                'paginator' => $paginator
            ]);
        } catch (Exception $e) {
            $em->getConnection()->rollBack();

            throw $e;
        }
    }
}
