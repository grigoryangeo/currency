<?php

namespace App\Model;

use App\Model\Response\AbstractResponse;
use JMS\Serializer\Annotation as Serializer;

class ConvertorResponse extends AbstractResponse
{
    /**
     * Result value
     *
     * @var float
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("float")
     */
    protected $value;

    public function __construct(float $value)
    {
        $this->setValue($value);
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