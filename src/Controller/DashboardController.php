<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {

        $my_number = 12;
        $my_tab = [1,2,3];

        return $this->render('dashboard/index.html.twig', [
            'my_number' => $my_number,
            'my_tab' => $my_tab,
        ]);
    }
}
