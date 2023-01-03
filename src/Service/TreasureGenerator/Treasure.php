<?php
namespace App\Service\TreasureGenerator;

use App\Interface\TreasureInterface;

class Treasure implements TreasureInterface
{
    private int $value;
    
    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'value' => $this->value
        ];
    }
}