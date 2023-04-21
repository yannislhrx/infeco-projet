<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class DashboardController extends AbstractController
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(): Response
    {

        
         // Récupération du jeton de sécurité courant
         $token = $this->tokenStorage->getToken();

         // Vérification si l'utilisateur est authentifié
         if (!$token || !$token->isAuthenticated()) {
             throw new \LogicException('L\'utilisateur n\'est pas authentifié.');
         }
 
         // Récupération de l'utilisateur courant
         $user = $token->getUser();


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,

        ]);
    }
}
