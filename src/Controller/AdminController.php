<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticlePicture;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    public function newArticle(
        Request      $request,
        EntityManagerInterface $em,
    ): Response
    {
        $article = new Article();

        $article->setTitle('Nouvel article du ' . date('d/m/Y'));
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('attachement')->getData();
            if($file instanceof UploadedFile) {
                $fileName = uniqid('file_', true).'.'.$file->guessExtension();
                $file->move(
                    __DIR__ . '/../../public/assets/uploads',
                    $fileName,
                );
                $articlePicture = new ArticlePicture();
                $articlePicture
                    ->setName($fileName)
                    ->setUrl('/assets/uploads/' . $fileName)
                ;
                $article->setImage($articlePicture);
                $em->persist($articlePicture);
            }
            $em->persist($article);
            $em->flush();
        }

        return $this->render('admin/new-article.html.twig', [
            'formidable' => $form->createView(),
            'user' => [
                'name' => 'Bobby',
                'age' => '42',
            ],
        ]);
    }
}
