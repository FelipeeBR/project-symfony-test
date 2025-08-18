<?php

namespace App\Service;

use App\Entity\Cow;
use App\Entity\Veterinario;
use App\Repository\CowRepository;
use App\Repository\FarmRepository;
use App\Repository\VeterinarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CowService {
    public function __construct(
        private CowRepository $cowRepository,
        private FarmRepository $farmRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
    ) {}

    public function create(Cow $cow) {
        $errors = $this->validator->validate($cow);
        $farm = $this->farmRepository->find($cow->getFarm()->getId());
        $quantityCow = 18;
        $allowedQuantity = $farm->getSize() * $quantityCow;

        if(count($errors) > 0) {
            throw new BadRequestHttpException($errors[0]->getMessage());
        }

        if(count($farm->getCows()) >= $allowedQuantity) {
            throw new BadRequestHttpException('Essa fazenda já possui o número máximo de vacas');
        }

        $this->entityManager->persist($cow);
        $this->entityManager->flush();

        return $cow;
    }
}