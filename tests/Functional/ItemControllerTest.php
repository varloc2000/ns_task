<?php

namespace App\Tests;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ItemControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        
        $data = 'very secure new item data';

        $newItemData = ['data' => $data];

        $client->request('POST', '/item', $newItemData);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $client->getResponse()->getContent());

        $itemRepository->findOneByData($data);
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);

        $data = 'very secure new item data';
        $updateData = 'UPDATED very secure new item data';

        $newItemBody = ['data' => $data];

        $client->request('POST', '/item', $newItemBody);
        $client->request('GET', '/item');

        $itemsResponse = json_decode($client->getResponse()->getContent(), true);

        $updateItemBody = ['id' => $itemsResponse[0]['id'], 'data' => $updateData];

        $client->request('PUT', '/item', $updateItemBody);

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/item');
        $this->assertStringContainsString($updateData, $client->getResponse()->getContent());

        $itemRepository->findOneByData($updateData);
    }
}
