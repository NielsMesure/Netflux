<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use mysql_xdevapi\CollectionRemove;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue] //autoIncrément
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $illustration = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'movies')]
    private Collection $category;

    #[ORM\Column]
    private ?bool $isBest = null;

    #[ORM\Column(length: 255)]
    private ?string $illustrationHeaders = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Comment::class)]
    private Collection $commentsMovie;

    #[ORM\Column]
    private ?string $movieLink = null;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->commentsMovie = new ArrayCollection();
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

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

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


    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function isIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

        return $this;
    }

    public function getIllustrationHeaders(): ?string
    {
        return $this->illustrationHeaders;
    }

    public function setIllustrationHeaders(string $illustrationHeaders): self
    {
        $this->illustrationHeaders = $illustrationHeaders;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentsMovie(): Collection
    {
        return $this->commentsMovie;
    }

    public function addCommentsMovie(Comment $commentsMovie): self
    {
        if (!$this->commentsMovie->contains($commentsMovie)) {
            $this->commentsMovie->add($commentsMovie);
            $commentsMovie->setMovie($this);
        }

        return $this;
    }

    public function removeCommentsMovie(Comment $commentsMovie): self
    {
        if ($this->commentsMovie->removeElement($commentsMovie)) {
            // set the owning side to null (unless already changed)
            if ($commentsMovie->getMovie() === $this) {
                $commentsMovie->setMovie(null);
            }
        }

        return $this;
    }

    public function getMovieLink(): ?string
    {
        return $this->movieLink;
    }

    public function setMovieLink(string $movieLink): self
    {
        $this->movieLink = $movieLink;


        return $this;
    }


}
