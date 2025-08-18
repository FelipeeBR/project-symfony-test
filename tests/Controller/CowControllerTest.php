<?php

namespace App\Tests\Controller;

use App\Entity\Cow;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CowControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $cowRepository;
    private string $path = '/cow/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->cowRepository = $this->manager->getRepository(Cow::class);

        foreach ($this->cowRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cow index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'cow[code]' => 'Testing',
            'cow[milk]' => 'Testing',
            'cow[food]' => 'Testing',
            'cow[weight]' => 'Testing',
            'cow[birth]' => 'Testing',
            'cow[farm]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->cowRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cow();
        $fixture->setCode('My Title');
        $fixture->setMilk('My Title');
        $fixture->setFood('My Title');
        $fixture->setWeight('My Title');
        $fixture->setBirth('My Title');
        $fixture->setFarm('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cow');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cow();
        $fixture->setCode('Value');
        $fixture->setMilk('Value');
        $fixture->setFood('Value');
        $fixture->setWeight('Value');
        $fixture->setBirth('Value');
        $fixture->setFarm('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cow[code]' => 'Something New',
            'cow[milk]' => 'Something New',
            'cow[food]' => 'Something New',
            'cow[weight]' => 'Something New',
            'cow[birth]' => 'Something New',
            'cow[farm]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cow/');

        $fixture = $this->cowRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getMilk());
        self::assertSame('Something New', $fixture[0]->getFood());
        self::assertSame('Something New', $fixture[0]->getWeight());
        self::assertSame('Something New', $fixture[0]->getBirth());
        self::assertSame('Something New', $fixture[0]->getFarm());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cow();
        $fixture->setCode('Value');
        $fixture->setMilk('Value');
        $fixture->setFood('Value');
        $fixture->setWeight('Value');
        $fixture->setBirth('Value');
        $fixture->setFarm('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/cow/');
        self::assertSame(0, $this->cowRepository->count([]));
    }
}
