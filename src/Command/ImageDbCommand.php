<?php

namespace App\Command;

use App\Entity\ArticlePicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:image-db')]
class ImageDbCommand extends Command
{

    protected static $defaultName = 'app:image-db';

    protected EntityManagerInterface $em;

    public function __construct( EntityManagerInterface $em , ?string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function execute(InputInterface $input,
                               OutputInterface $output): int
    {
        $output->writeln('Hello World!');

        $allImages = $this->em->getRepository(ArticlePicture::class)->findAll();

        dump($allImages);

        return Command::SUCCESS;
        // return Command::FAILURE;
        // return Command::INVALID
    }
}