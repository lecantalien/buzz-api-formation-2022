<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Game;
use App\Services\CartManager;
use App\Services\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SteamApiController extends AbstractController
{
    public function cartGetContent(
        CartManager $cm,
    ): Response
    {
        $cm->_initCart();

        $data = [
            'error' => false,
            'message' => 'Hello World!',
            'cart' => null,
        ];

        $c = $cm->getUserActualCart();
        if ($c instanceof Cart) {
            $cartContent = [];
            foreach ($c->getCartItems() as $cartItem) {
                /**
                 * @var CartItem $cartItem
                 */
                $cartContent[] = [
                    'rel_id' => $cartItem->getId(),
                    'game_id' => $cartItem->getGame()?->getId(),
                    'name' => $cartItem->getGame()?->getName(),
                    'price' => $cartItem->getGame()?->getPrice(),
                    'promotion' => $cartItem->getGame()?->getPromotion(),
                    'price_vat' => $cartItem->getGame()?->getPriceWithPromotion(),
                ];
            }
            $data['cart']['total_price'] = $c->getTotalPrice();
            $data['cart']['date'] = $c->getDateCreation();
            $data['cart']['dateUpdate'] = $c->getDateUpdate();
            $data['cart']['guid'] = $c->getGuid();
            $data['cart']['items'] = $cartContent;
        }

        return new JsonResponse($data);
    }

    public function addToCart(
        Request                $request,
        CartManager            $cm,
        GameService            $gameService,
        EntityManagerInterface $em,
    ): Response
    {
        $cm->_initCart();
        $data = [
            'error' => false,
            'message' => 'Hello World!',
            'cart' => null,
        ];


        $gameId = $request->get('game_id', null);
        if ($gameId === null) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Game id is required',
            ], 400);
        }

        $game = $gameService->getByID((int)$gameId);
        if (!$game instanceof Game) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Game not found',
            ], 404);
        }


        $c = $cm->getUserActualCart();
        if (!$c instanceof Cart) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Cart not found',
            ], 404);
        }

        foreach ($c->getCartItems() as $cartItem) {
            if ($cartItem->getGame()->getId() === $game->getId()) {
                return new JsonResponse([
                    'error' => true,
                    'message' => 'Game already in cart',
                ], 400);
            }
        }

        $cartItem = new CartItem($c, $game);

        $em->persist($cartItem);
        $em->flush();

        $data['message'] = 'Game added to cart';

        return new JsonResponse($data, 201);
    }

    public function deleteFromCart(
        Request                $request,
        CartManager            $cm,
        GameService            $gameService,
        EntityManagerInterface $em,
    ): Response
    {
        $cm->_initCart();
        $data = [
            'error' => false,
            'message' => 'Hello World!',
            'cart' => null,
        ];


        $gameId = $request->get('game_id', null);
        if ($gameId === null) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Game id is required',
            ], 400);
        }

        $game = $gameService->getByID((int)$gameId);
        if (!$game instanceof Game) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Game not found',
            ], 404);
        }


        $c = $cm->getUserActualCart();
        if (!$c instanceof Cart) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Cart not found',
            ], 404);
        }

        foreach ($c->getCartItems() as $cartItem) {
            if ($cartItem->getGame()->getId() === $game->getId()) {
                // todo delete from cart

                $em->remove($cartItem);
                $em->flush();

                $data['message'] = 'Game deleted from cart';
                return new JsonResponse($data, 200);
            }
        }


        return new JsonResponse([
            'error' => true,
            'message' => 'Game not in cart',
        ], 400);
    }
}