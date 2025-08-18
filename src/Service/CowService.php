<?php

namespace App\Service;

use App\Entity\Cow;
use App\Repository\CowRepository;
use App\Repository\FarmRepository;
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
        $cowFarm = $cow->getFarm();
        if(!$cowFarm) {
            throw new \Exception("A vaca deve estar associada a uma fazenda");
        }

        if($cow->getBirth() > new \DateTime()) {
            throw new \Exception("Data de nascimento não pode ser maior que a data atual");
        }

        $farm = $this->farmRepository->find($cowFarm->getId());
        if(!$farm) {
            throw new \Exception("Fazenda não encontrada");
        }

        if(count($farm->getCows()) >= $allowedQuantity) {
            throw new BadRequestHttpException('Essa fazenda já possui o número máximo de vacas');
        }

        $this->entityManager->persist($cow);
        $this->entityManager->flush();

        return $cow;
    }

    /*public function findSlaughter() {
        $arroba = 18 * 15;
        return Cow::where(function ($query) use ($arroba) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 5')
                  ->orWhere('milk', '<', 40)
                  ->orWhere(function ($sub) {
                      $sub->where('milk', '<', 70)
                          ->whereRaw('(food / 7) > 50');
                  })
                  ->orWhere('wheight', '>', $arroba);
        })
        ->where('abatido', false)
        ->paginate(3);
    }*/
}