<?php

namespace App\CurrencyLoader;

class CurrencyDto
{
    /**
     * @var string $code
     */
    protected $code;

    /**
     * @var string $source
     */
    protected $source;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var float $value
     */
    protected $value;

    /**
     * @var bool $active
     */
    protected $active;

    public function __construct(string $code, string $source, string $name, string $value)
    {
        $this->code   = $code;
        $this->source = $source;
        $this->name   = $name;
        $this->value  = $value;
        $this->active = true;
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
