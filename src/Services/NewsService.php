<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsService
{
    /**
     * @var array
     */
    protected array $news;
    /**
     * @var bool
     */
    protected bool $isLoaded;

    /**
     *
     */
    public function __construct()
    {
        $this->isLoaded = false;
        $this->news = [];
    }

    /**
     * @return array
     */
    public function getNews(): array
    {
        if (!$this->isLoaded) {
            $this->loadNewsFromFile();
        }
        return $this->news;
    }

    protected function loadNewsFromFile(): void
    {
        $path = __DIR__ . '/../../data/article.json';
        $contentJson = file_get_contents($path);
        try {
            $contentArray = json_decode($contentJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            //throw new \RuntimeException('Impossible de lire le fichier JSON');
            throw new NotFoundHttpException('Impossible de lire le fichier JSON');
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur inconnue');
        }

        $this->news = $contentArray['items'] ?? [];
        $this->isLoaded = true;

        //dump($this->news);die;
    }

}