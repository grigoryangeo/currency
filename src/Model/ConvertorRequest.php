<?php

namespace App\Model;

use App\Entity\Currency;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ConvertorRequest
{
    /**
     * Code currency from
     *
     * @var Currency
     */
    protected $from;

    /**
     * Code currency to
     *
     * @var Currency
     */
    protected $to;

    /**
     * Code currency from
     *
     * @var float
     */
    protected $value;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('from', new Assert\NotBlank());
        $metadata->addPropertyConstraint('to', new Assert\NotBlank());
        $metadata->addPropertyConstraint('value', new Assert\NotBlank());
    }

    /**
     * @return null|Currency
     */
    public function getFrom():?Currency
    {
        return $this->from;
    }

    /**
     * @param Currency $from
     *
     * @return self
     */
    public function setFrom(Currency $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return null|Currency
     */
    public function getTo():?Currency
    {
        return $this->to;
    }

    /**
     * @param Currency $to
     *
     * @return self
     */
    public function setTo(Currency $to): self
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