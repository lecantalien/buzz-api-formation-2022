<?php

namespace App\Services;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSimilarGames(Game $game): array
    {
        $allTypes = $game->getType()->toArray();
        $gameRepo = $this->em->getRepository(Game::class);
        $qb = $gameRepo->createQueryBuilder('g');
        $qb = $qb
            ->where('1 = 1')
            ->andWhere('g.id != :id')
            ->setParameter('id', $game->getId())
            ->andWhere(':alltypes MEMBER OF g.type')
            ->setParameter('alltypes', $allTypes)
            ->orderBy('g.id', 'DESC');


        return $qb->getQuery()->getResult();

    }

    /**
     * get a game by slug
     *
     * @param string $slug
     * @return Game|null
     */
    public function getBySlug(string $slug): ?Game
    {
        return $this->em->getRepository(Game::class)
            ->findOneBy(['slug' => $slug]);
    }

    public function getByID(int $id): ?Game
    {
        return $this->em->getRepository(Game::class)
            ->findOneBy(['id' => $id]);
    }

    /**
     * get a list by by slug
     *
     * @param int $page
     * @param int $perPage
     * @param bool $countMode - default false
     * - if true, return the number of games without pagination
     */

    public function getGames(
        int $page = 1,
        int $perPage = 10,
        bool $countMode = false,
    ): array
    {
        $repo = $this->em->getRepository(Game::class);
        if (!$countMode) {
            //findBy(array $criteria, array $orderBy = null,
            // $limit = null, $offset = null)
            return $repo->findBy([],
                ['dateRelease' => 'DESC'],//tri par date de sortie
                $perPage, ($page - 1) * $perPage);//pagination
        }
        $totalArticles = (int)$repo->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $ret = [
            'total' => $totalArticles,
            'perPage' => $perPage,
            'totalPages' => ceil($totalArticles / $perPage),
            ];
        return($ret);
    }
}