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

final class VeterinarianController extends AbstractController
{
    #[Route('/veterinarians', name: 'veterinarian_index', methods: ['GET'])]
    public function index(VeterinarianRepository $veterinarianRepository): Response {
        return $this->render('veterinarian/index.html.twig', [
            'veterinarians' => $veterinarianRepository->findAll(),
        ]);
    }

    #[Route('/veterinarian/{id<\d+>}', name: 'veterinarian_show', methods: ['GET'])]
    public function show(string $id, VeterinarianRepository $veterinarianRepository): Response {
        return $this->render('veterinarian/show.html.twig', [
            'veterinarian' => $veterinarianRepository->find($id),
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
