<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Category $categories = null;
    
    
    /**
     * @var Collection<int, CourseFile>
     */#[ORM\OneToMany(targetEntity: CourseFile::class, mappedBy: 'course', cascade: ['persist', 'remove'])]
private Collection $courseFiles;


    public function __construct()
    {
        $this->courseFiles = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCategories(): ?Category
    {
        return $this->categories;
    }

    public function setCategories(?Category $categories): static
    {
        $this->categories = $categories;
        return $this;
    }

   


    /**
     * @return Collection<int, CourseFile>
     */
    public function getCourseFiles(): Collection
    {
        return $this->courseFiles;
    }

    public function addCourseFile(CourseFile $courseFile): static
    {
        if (!$this->courseFiles->contains($courseFile)) {
            $this->courseFiles->add($courseFile);
            $courseFile->setCourse($this);
        }

        return $this;
    }

    public function removeCourseFile(CourseFile $courseFile): static
    {
        if ($this->courseFiles->removeElement($courseFile)) {
            // set the owning side to null (unless already changed)
            if ($courseFile->getCourse() === $this) {
                $courseFile->setCourse(null);
            }
        }

        return $this;
    }
}
