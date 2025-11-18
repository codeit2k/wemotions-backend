<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="videos")
 */
class Video
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\\Entity\\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $owner;

    /** @ORM\Column(type="string", length=255) */
    private string $title;

    /** @ORM\Column(type="text", nullable=true) */
    private ?string $description = null;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $storagePath = null;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $thumbnailPath = null;

    /** @ORM\Column(type="integer") */
    private int $durationSeconds = 0;

    /** @ORM\Column(type="string", length=50) */
    private string $status = 'pending';

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $t): self { $this->title = $t; return $this; }
    public function setOwner(User $u): self { $this->owner = $u; return $this; }
    public function setStoragePath(?string $p): self { $this->storagePath = $p; return $this; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }
}
