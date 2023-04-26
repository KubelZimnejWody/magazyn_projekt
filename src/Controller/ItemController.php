<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route("/items", name: "app_items_list")]
    public function listItems(ItemRepository $itemRepository): Response
    {
        return $this->render("items/list.html.twig", [
            "items" => $itemRepository->findAll()
        ]);
    }
}