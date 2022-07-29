<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class CartManager
{
    protected EntityManagerInterface $em;
    protected RequestStack $rs;
    protected Security $security;

    public function __construct(
        EntityManagerInterface $em,
        RequestStack $rs,
        Security $security,
    )
    {
        $this->em = $em;
        $this->rs = $rs;
        $this->security = $security;
    }

    public function getUserActualCart(): ?Cart
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return null;
        }

        $cart = $this->em->getRepository(Cart::class)
            ->findOneBy([
                'owner' => $user,
                'paid' => false,
            ]);

        if($cart instanceof Cart) {
            return $cart;
        }
        return null;
    }
    public function _initCart(): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }
        $cart = $this->em->getRepository(Cart::class)
            ->findOneBy([
                'owner' => $user,
                'paid' => false,
            ]);

        if (!$cart instanceof Cart) {
            $cart = new Cart($user);
            $this->em->persist($cart);
            $this->em->flush();
        }

        $this->rs->getCurrentRequest()?->getSession()->set('cart', $cart -> getId());

    }

}