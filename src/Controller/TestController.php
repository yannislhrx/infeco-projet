<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        $test_var = 'je suis var de tewt';

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'test_var' => $test_var,
        ]);
    }
}
