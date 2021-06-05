<?php

/**
 * @author Tianle Xu <xtl@xtlsoft.top>
 */

namespace Boruvka;

class Border
{
    public function __construct(
        protected int $from,
        protected int $to,
        protected float $weight
    ) {
        if (defined("BORUVKA_GRAPH_NON_DIRECTIONAL")) {
            if ($from > $to) {
                swap($this->from, $this->to);
            }
        }
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
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
     * @param array[] $borders
     * @return Border[]
     */
    public static function fromArray(array $borders): array
    {
        return array_map(fn (array $item): Border => new self(from: $item[0], to: $item[1], weight: $item[2]), $borders);
    }
}
