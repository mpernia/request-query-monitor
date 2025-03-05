<?php

namespace RequestQueryMonitor\DTOs;

class LogItem
{
    public function __construct(private readonly array $properties = [])
    {
    }

    public static function fromArray(array $properties): static
    {
        return new self($properties);
    }

    public function toArray(): array
    {
        return $this->properties;
    }
}
