<?php

/**
 * @author Tianle Xu <xtl@xtlsoft.top>
 */

namespace Boruvka;

class Node
{
    public function __construct(
        protected int $id,
        protected float $weight
    ) {
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function updateWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Construct from an array
     *
     * @param array[] $nodes
     * @return Node[]
     */
    public static function fromArray(array $nodes): array
    {
        return array_map(fn (array $item): Node => new self(id: $item[0], weight: $item[1]), $nodes);
    }
}
