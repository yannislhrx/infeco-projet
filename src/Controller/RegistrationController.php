<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Users;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = '';
        $userreg = new Users();
        $form = $this->createForm(UserType::class, $userreg);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($userreg, $userreg->getPlainPassword());
            $userreg->setPassword($password);
            $roles = $userreg->getRoles();
            // $roles[] = 'AGENCE';
            $userreg->setRoles($roles);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userreg);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
