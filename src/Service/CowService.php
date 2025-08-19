<?php

namespace App\Service;

use App\Entity\Cow;
use App\Repository\CowRepository;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $cowFarm = $cow->getFarm();
        if(!$cowFarm) {
            throw new \Exception("O Gado deve estar associado a uma fazenda");
        }

        if($cow->getBirth() > new \DateTime()) {
            throw new \Exception("Data de nascimento não pode ser maior que a data atual");
        }

        $farm = $this->farmRepository->find($cowFarm->getId());
        if(!$farm) {
            throw new \Exception("Fazenda não encontrada");
        }

        if(count($farm->getCows()) >= $allowedQuantity) {
            throw new BadRequestHttpException('Essa fazenda já possui o número máximo de gados');
        }

        $this->entityManager->persist($cow);
        $this->entityManager->flush();

        return $cow;
    }
}