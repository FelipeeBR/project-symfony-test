<?php

namespace App\Controller;

use App\Entity\Cow;
use App\Form\CowType;
use App\Repository\CowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CowService;

final class CowController extends AbstractController
{
    #[Route('/cows', name: 'cow_index', methods: ['GET'])]
    public function index(CowRepository $cowRepository): Response
    {
        return $this->render('cow/index.html.twig', [
            'cows' => $cowRepository->findAll(),
        ]);
    }

    #[Route('/cow/new', name: 'cow_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CowService $cowService): Response {
        $cow = new Cow();
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                //dd($request);
                $cowService->create($cow);

                $this->addFlash('success', 'Vaca criada');
                return $this->redirectToRoute('cow_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erro: '.$e->getMessage());
            }
        }

        return $this->render('cow/new.html.twig', [
            'cow' => $cow,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cow/{id}', name: 'cow_show', methods: ['GET'])]
    public function show(Cow $cow): Response
    {
        return $this->render('cow/show.html.twig', [
            'cow' => $cow,
        ]);
    }

    #[Route('/cow/{id}/edit', name: 'cow_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cow $cow, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cow/edit.html.twig', [
            'cow' => $cow,
            'form' => $form,
        ]);
    }

    #[Route('/cow/{id}', name: 'cow_delete', methods: ['POST'])]
    public function delete(Request $request, Cow $cow, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cow->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cow_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/lista-abatimentos', name: 'cow_slaughter', methods: ['GET'])]
    public function listSlaughters(CowRepository $cowRepository): Response {
        $listSlaughter = $cowRepository->findSlaughter();
        return $this->render('cow/slaughter.html.twig', [
            'listSlaughter' => $listSlaughter,
        ]);
    }
}
