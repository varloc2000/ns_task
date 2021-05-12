<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Exception\ApplicationException;
use App\Exception\ModelNotFoundException;
use App\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list(ItemService $itemService): JsonResponse
    {
        $items = $itemService->getAllByUser($this->getUser());

        $allItems = [];
        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $this->json($allItems);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ItemService $itemService): JsonResponse
    {
        $data = $request->get('data');

        if (empty($data)) {
            throw new BadRequestHttpException('No "data" parameter in request');
        }

        $itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, int $id, ItemService $itemService): JsonResponse
    {
        if (empty($id)) {
            throw new BadRequestHttpException('No "id" parameter in request');
        }

        try {
            $itemService->delete($id);
        } catch (ApplicationException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->json([]);
    }

    /**
     * @Route("/item", name="items_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, ItemService $itemService): JsonResponse
    {
        $data = $request->get('data');

        if (empty($data)) {
            throw new BadRequestHttpException('No "data" parameter in request');
        }

        $id = (int) $request->get('id');

        if (empty($id)) {
            throw new BadRequestHttpException('No "id" parameter in request');
        }

        try {
            $itemService->update($id, $data);
        } catch (ApplicationException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->json([]);
    }
}
