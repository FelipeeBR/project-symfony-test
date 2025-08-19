<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CowRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CowRepository $cowRepository): Response {
        $totalMilk = $cowRepository->findTotalMilk();
        $totalFood = $cowRepository->findTotalFood();

        return $this->render('home/index.html.twig', [
            'totalMilk' => $totalMilk,
            'totalFood' => $totalFood
        ]);
    }
}
