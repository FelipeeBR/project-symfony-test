<?php

namespace App\Tests\Controller;

use App\Entity\Farm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FarmControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $farmRepository;
    private string $path = '/farm/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->farmRepository = $this->manager->getRepository(Farm::class);

        foreach ($this->farmRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Farm index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'farm[name]' => 'Testing',
            'farm[size]' => 'Testing',
            'farm[responsible]' => 'Testing',
            'farm[veterinarian]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->farmRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Farm();
        $fixture->setName('My Title');
        $fixture->setSize('My Title');
        $fixture->setResponsible('My Title');
        $fixture->setVeterinarian('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Farm');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Farm();
        $fixture->setName('Value');
        $fixture->setSize('Value');
        $fixture->setResponsible('Value');
        $fixture->setVeterinarian('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'farm[name]' => 'Something New',
            'farm[size]' => 'Something New',
            'farm[responsible]' => 'Something New',
            'farm[veterinarian]' => 'Something New',
        ]);

        self::assertResponseRedirects('/farm/');

        $fixture = $this->farmRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSize());
        self::assertSame('Something New', $fixture[0]->getResponsible());
        self::assertSame('Something New', $fixture[0]->getVeterinarian());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Farm();
        $fixture->setName('Value');
        $fixture->setSize('Value');
        $fixture->setResponsible('Value');
        $fixture->setVeterinarian('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/farm/');
        self::assertSame(0, $this->farmRepository->count([]));
    }
}
