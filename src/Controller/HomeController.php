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
        $totalCowAndFood = $cowRepository->findTotalCowAndFood();

        return $this->render('home/index.html.twig', [
            'totalMilk' => $totalMilk,
            'totalFood' => $totalFood,
            'totalCowAndFood' => $totalCowAndFood
        ]);
    }

    #[Route('/slaughtered', name: 'app_slaughtered')]
    public function slaughter(CowRepository $cowRepository): Response {
        $listSlaughtered = $cowRepository->findSlaughtered();
        return $this->render('home/slaughtered.html.twig', [
            'listSlaughtered' => $listSlaughtered,
        ]);
    }
}
