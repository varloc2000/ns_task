<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Exception\ModelNotFoundException;
use App\Repository\ItemRepository;
use App\Service\ItemService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var ItemRepository|MockObject
     */
    private $itemRepository;

    /**
     * @var ItemService
     */
    private $itemService;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->itemRepository);

        $this->itemService = new ItemService($this->entityManager);
    }

    public function testCreate(): void
    {
        /** @var User $user */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $this->entityManager->expects($this->once())->method('persist')->with($expectedObject);
        $this->entityManager->expects($this->once())->method('flush');

        $this->itemService->create($user, $data);
    }

    public function testUpdate(): void
    {
        $data = 'updated secret data';
        $expectedItem = new Item();
        $expectedItem->setData($data);

        $this->itemRepository->expects($this->once())->method('find')->with($this->isType('int'))->willReturn(new Item());
        $this->entityManager->expects($this->once())->method('persist')->with($expectedItem);
        $this->entityManager->expects($this->once())->method('flush');

        $this->itemService->update(1, $data);
    }

    public function testUpdateError(): void
    {
        $data = 'updated secret data';
        $expectedItem = new Item();
        $expectedItem->setData($data);

        $this->itemRepository->expects($this->once())->method('find')->with($this->isType('int'))->willReturn(null);
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        $this->expectException(ModelNotFoundException::class);

        $this->itemService->update(1, $data);
    }

    public function testDelete(): void
    {
        $expectedItem = new Item();

        $this->itemRepository->expects($this->once())->method('find')->with($this->isType('int'))->willReturn($expectedItem);
        $this->entityManager->expects($this->once())->method('remove')->with($expectedItem);
        $this->entityManager->expects($this->once())->method('flush');

        $this->itemService->delete(1);
    }

    public function testDeleteError(): void
    {
        $this->itemRepository->expects($this->once())->method('find')->with($this->isType('int'))->willReturn(null);
        $this->entityManager->expects($this->never())->method('remove');
        $this->entityManager->expects($this->never())->method('flush');

        $this->expectException(ModelNotFoundException::class);

        $this->itemService->delete(1);
    }
}
