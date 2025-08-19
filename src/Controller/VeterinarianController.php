<?php

namespace App\Controller;

use App\Entity\Veterinarian;
use App\Repository\VeterinarianRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\VeterinarianType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

final class VeterinarianController extends AbstractController
{
    #[Route('/veterinarians', name: 'veterinarian_index', methods: ['GET'])]
    public function index(VeterinarianRepository $veterinarianRepository, PaginatorInterface $paginator, Request $request): Response {
        $queryBuilder = $veterinarianRepository->createQueryBuilder('v');

        if($request->query->get('sort')) {
            $queryBuilder->orderBy(
                'v.' . $request->query->get('sort'),
                $request->query->get('direction', 'asc')
            );
        } else {
            $queryBuilder->orderBy('v.id', 'asc');
        }

        $veterinarians = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), 
            3                     
        );


        return $this->render('veterinarian/index.html.twig', [
            'veterinarians' => $veterinarians,
        ]);
    }

    #[Route('/veterinarian/{id<\d+>}', name: 'veterinarian_show', methods: ['GET'])]
    public function show(string $id, VeterinarianRepository $veterinarianRepository): Response {
        $veterinarian = $veterinarianRepository->find($id);
    
        if(!$veterinarian) {
            throw $this->createNotFoundException('veterinario nao encontrado');
        }

        $farms = $veterinarian->getFarms();

        return $this->render('veterinarian/show.html.twig', [
            'veterinarian' => $veterinarian,
            'farms' => $farms
        ]);
    }

    #[Route('/veterinarian/new', name: 'veterinarian_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $veterinarian = new Veterinarian;
        
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($veterinarian);
            $entityManager->flush();

            return $this->redirectToRoute('veterinarian_show', ['id' => $veterinarian->getId()]);
        }
        return $this->render('veterinarian/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/veterinarian/{id<\d+>}/edit', name: 'veterinarian_edit', methods: ['GET', 'POST'])]
    public function edit(Veterinarian $veterinarian, Request $request, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($veterinarian);
            $entityManager->flush();

            return $this->redirectToRoute('veterinarian_show', ['id' => $veterinarian->getId()]);
        }
        return $this->render('veterinarian/edit.html.twig', [
            'veterinarian' => $veterinarian,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/veterinarian/{id<\d+>}/delete', name: 'veterinarian_delete', methods: ['POST'])]
    public function delete(Veterinarian $veterinarian, EntityManagerInterface $entityManager) {
        $entityManager->remove($veterinarian);
        $entityManager->flush();
        return $this->redirectToRoute('veterinarian_index');
    }
}
