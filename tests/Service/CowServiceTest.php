<?php

namespace App\Tests\Service;

use App\Entity\Cow;
use App\Entity\Farm;
use App\Service\CowService;
use App\Repository\CowRepository;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CowServiceTest extends TestCase {
    
    private CowRepository $cowRepository;
    private FarmRepository $farmRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private CowService $service;

    protected function setUp(): void
    {
        $this->cowRepository = $this->createMock(CowRepository::class);
        $this->farmRepository = $this->createMock(FarmRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->service = new CowService(
            $this->cowRepository,
            $this->farmRepository,
            $this->entityManager,
            $this->validator
        );
    }

    public function test_create_cow_date_future_exception(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Data de nascimento não pode ser maior que a data atual");

        $farm = new Farm();
        $farm->setSize(10);
        $farm->setId(1);

        $cow = new Cow();
        $cow->setBirth(new \DateTime('+1 day'));
        $cow->setFarm($farm);

        // Configura os mocks
        $this->farmRepository->method('find')
            ->with(1)
            ->willReturn($farm);

        $this->validator->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->service->create($cow);
    }
}