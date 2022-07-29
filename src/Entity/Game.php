<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\ManyToMany(targetEntity: GameGenre::class)]
    private $type;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Media::class)]
    private $pictures;

    #[ORM\Column(type: 'array')]
    private $plateformes = [];

    #[ORM\Column(type: 'float', nullable: true)]
    private $promotion;

    #[ORM\Column(type: 'datetime')]
    private $dateAdded;

    #[ORM\Column(type: 'datetime', nullable: true)]
        private $dateRelease;

    #[ORM\Column(type: 'text')]
    private $description;

    public function __construct(string $name ,float $price = 0, array $plateformes = [])
    {
        $this->setName($name);
        $this->price = $price;
        $this->plateformes = $plateformes;
        $this->dateAdded = new \DateTime();
        $this->promotion = null;
        $this->description = '';

        $this->type = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($name);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, GameGenre>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(GameGenre $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
        }

        return $this;
    }

    public function removeType(GameGenre $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getPriceWithPromotion(): ?float
    {
        if ($this->promotion <= 0) {
            return $this->price;
        }
        return round($this->price * ((100 - $this->promotion) / 100), 2);
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Media $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setGame($this);
        }

        return $this;
    }

    public function removePicture(Media $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getGame() === $this) {
                $picture->setGame(null);
            }
        }

        return $this;
    }

    public function getPlateformes(): ?array
    {
        return $this->plateformes;
    }

    public function setPlateformes(array $plateformes): self
    {
        $this->plateformes = $plateformes;

        return $this;
    }

    public function getPromotion(): ?float
    {
        return $this->promotion;
    }

    public function setPromotion(?float $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTimeInterface $dateAdded): self
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    public function getDateRelease(): ?\DateTimeInterface
    {
        return $this->dateRelease;
    }

    public function setDateRelease(?\DateTimeInterface $dateRelease): self
    {
        $this->dateRelease = $dateRelease;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
