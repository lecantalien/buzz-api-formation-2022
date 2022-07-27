<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function index(AuthenticationUtils         $authenticationUtils,
                          EntityManagerInterface      $em,
                          UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

/*
        $us = [
            ['mon@email.fr','bonobo', 'macaque'],
            ['dylan@gmail.fr','dylan63', 'macaque'],
            ['bobby@yahoo.fr','bfisher', 'macaque'],
            ['lechien@lycos.fr','dog', 'macaque'],
            ['personne@personne.fr','personne', 'macaque'],
        ];
        foreach($us as $u){
            // create random user
            $user = new User($u[1], $u[0]);
            $plaintextPassword = $u[2];
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $em->persist($user);
        }
        $em->flush();
*/

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}