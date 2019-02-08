<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 *
 * @ORM\Table(name="app_currency", indexes={@ORM\Index(name="search_idx", columns={"code", "source"})})
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Currency
{
    const SOURCES_CBR = "CBR";
    const SOURCES_ECB = "ECB";

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @var string $code
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     *
     */
    protected $code;

    /**
     * @var string $source
     * @ORM\Column(name="source", type="string", length=255, nullable=false)
     *
     */
    protected $source;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    protected $name;

    /**
     * @var float $value
     * @ORM\Column(name="value", type="float", nullable=false)
     *
     */
    protected $value;

    /**
     * @var bool $active
     * @ORM\Column(name="active", type="boolean", nullable=false)
     *
     */
    protected $active;

    /**
     * @var datetime $createdAt
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     *
     */
    protected $updatedAt;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('code', new Assert\Length(['max' => 255]));
        $metadata->addPropertyConstraint('code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('name', new Assert\Length(['max' => 255]));
        $metadata->addPropertyConstraint('value', new Assert\NotBlank());
        $metadata->addPropertyConstraint('createdAt', new Assert\NotBlank());
        $metadata->addPropertyConstraint('updatedAt', new Assert\NotBlank());
        $metadata->addPropertyConstraint('source', new Assert\NotBlank());
        $metadata->addPropertyConstraint('source', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'source',
            new Assert\Choice(
                [
                    'callback' => ['getSources'],
                ]
            )
        );
    }

    /**
     * avalible sources
     *
     * @return array
     */
    public static function getSources(): array
    {
        return [self::SOURCES_CBR, self::SOURCES_ECB];
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

    /**
     * @return ?DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     *
     * @return self
     */
    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return self
     */
    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }
}