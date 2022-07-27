<?php

namespace App\Controller;

use App\Entity\ArticlePicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageUploadApiController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em,
    ): Response
    {
        $file = $request->files->get('attachment', null);
        if (!$file instanceof UploadedFile) {
            throw new \Exception('File is not an instance of UploadedFile');
        }

        $fileName = uniqid('file_', true) . '.' . $file->guessExtension();
        $file->move(
            __DIR__ . '/../../public/assets/uploads',
            $fileName,
        );
        $articlePicture = new ArticlePicture();
        $articlePicture
            ->setName($fileName)
            ->setUrl('/assets/uploads/' . $fileName);
        $em->persist($articlePicture);
        $em->flush();
        return new JsonResponse(['ArticlePicture' => $articlePicture]);
    }
}
