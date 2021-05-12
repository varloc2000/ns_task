<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Exception\ModelNotFoundException;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $this->entityManager->getRepository(Item::class);
    }

    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function update(int $id, string $data): void
    {
        $item = $this->itemRepository->find($id);

        if ($item === null) {
            throw new ModelNotFoundException('No item found');
        }

        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $item = $this->itemRepository->find($id);

        if ($item === null) {
            throw new ModelNotFoundException('No item found');
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }

    public function getAllByUser(User $user): array
    {
        return $this->itemRepository->findBy(['user' => $user]);
    }
}
