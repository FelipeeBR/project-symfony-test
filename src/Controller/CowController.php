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
use Knp\Component\Pager\PaginatorInterface;

final class CowController extends AbstractController
{
    #[Route('/cows', name: 'cow_index', methods: ['GET'])]
    public function index(CowRepository $cowRepository, PaginatorInterface $paginator, Request $request): Response {
        $queryBuilder = $cowRepository->createQueryBuilder('c');

        if($request->query->get('sort')) {
            $queryBuilder->orderBy(
                'c.' . $request->query->get('sort'),
                $request->query->get('direction', 'asc')
            );
        } else {
            $queryBuilder->orderBy('c.id', 'asc');
        }

        $cows = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), 
            3                     
        );

        return $this->render('cow/index.html.twig', [
            'cows' => $cows,
        ]);
    }

    #[Route('/cow/new', name: 'cow_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CowService $cowService): Response {
        $cow = new Cow();
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                $cowService->create($cow);

                $this->addFlash('success', 'Gado criado com sucesso');
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
            $this->addFlash('success', 'Gado atualizado com sucesso');
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
        $this->addFlash('success', 'Gado excluido com sucesso');
        return $this->redirectToRoute('cow_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/slaughter-list', name: 'cow_slaughter', methods: ['GET'])]
    public function listSlaughters(CowRepository $cowRepository): Response {
        $listSlaughter = $cowRepository->findSlaughter();
        return $this->render('cow/slaughter.html.twig', [
            'listSlaughter' => $listSlaughter,
        ]);
    }

    #[Route('/cow/{id}/slaughter', name: 'cow_update_slaughter', methods: ['GET'])]
    public function updateSlaughter(int $id, CowRepository $cowRepository, EntityManagerInterface $em): Response {
        $cow = $cowRepository->find($id);
        if(!$cow) {
            throw $this->createNotFoundException('Animal não encontrado');
        }

        $cow->setSlaughtered(true);
        $em->flush();
        $this->addFlash('success', 'Foi para o abate com sucesso');
        return $this->redirectToRoute('cow_index');
}
}
