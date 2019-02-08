<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ConvertorRequest
{
    /**
     * Code currency from
     *
     * @var string
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("string")
     */
    protected $from;

    /**
     * Code currency to
     *
     * @var string
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("string")
     */
    protected $to;

    /**
     * Code currency from
     *
     * @var float
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("number")
     */
    protected $value;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('from', new Assert\Length(['max' => 5]));
        $metadata->addPropertyConstraint('from', new Assert\NotBlank());
        $metadata->addPropertyConstraint('to', new Assert\Length(['max' => 5]));
        $metadata->addPropertyConstraint('to', new Assert\NotBlank());
        $metadata->addPropertyConstraint('value', new Assert\NotBlank());
    }

    /**
     * @return null|string
     */
    public function getFrom():?string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return self
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTo():?string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return self
     */
    public function setTo(string $to): self
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return null|float
     */
    public function getValue():?float
    {
        return $this->value;
    }

    /**
     * @param null|float $value
     *
     * @return self
     */
    public function setValue(?float $value): self
    {
        $this->value = $value;
        return $this;
    }
}