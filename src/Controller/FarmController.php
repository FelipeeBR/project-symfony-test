<?php

namespace App\Controller;

use App\Entity\Farm;
use App\Form\FarmType;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FarmController extends AbstractController
{
    #[Route('/farms', name: 'farm_index', methods: ['GET'])]
    public function index(FarmRepository $farmRepository, PaginatorInterface $paginator, Request $request): Response {
        $queryBuilder = $farmRepository->createQueryBuilder('f');

        if($request->query->get('sort')) {
            $queryBuilder->orderBy(
                'f.' . $request->query->get('sort'),
                $request->query->get('direction', 'asc')
            );
        } else {
            $queryBuilder->orderBy('f.id', 'asc');
        }

        $farms = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), 
            3                     
        );

        return $this->render('farm/index.html.twig', [
            'farms' => $farms,
        ]);
    }

    #[Route('/farm/new', name: 'farm_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $farm = new Farm();
        $form = $this->createForm(FarmType::class, $farm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($farm);
            $entityManager->flush();

            return $this->redirectToRoute('farm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('farm/new.html.twig', [
            'farm' => $farm,
            'form' => $form,
        ]);
    }

    #[Route('/farm/{id<\d+>}', name: 'farm_show', methods: ['GET'])]
    public function show(Farm $farm): Response {
        return $this->render('farm/show.html.twig', [
            'farm' => $farm,
        ]);
    }

    #[Route('/farm/{id<\d+>}/edit', name: 'farm_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Farm $farm, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(FarmType::class, $farm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('farm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('farm/edit.html.twig', [
            'farm' => $farm,
            'form' => $form,
        ]);
    }

    #[Route('/vfarm/{id<\d+>}/delete', name: 'farm_delete', methods: ['POST'])]
    public function delete(Request $request, Farm $farm, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete'.$farm->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($farm);
            $entityManager->flush();
        }

        return $this->redirectToRoute('farm_index', [], Response::HTTP_SEE_OTHER);
    }
}
