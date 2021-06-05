<?php

/**
 * @author Tianle Xu <xtl@xtlsoft.top>
 */

namespace Boruvka;

class Boruvka
{
    public function __construct(
        protected Graph $graph
    ) {
    }

    public function calculate(): Graph
    {
        $forest = new Graph(nodes: $this->graph->getNodes());
        while (true) {
            $components = $forest->asForest();
            $cheapest = array_map(fn () => null, $components);
            $label = [];
            foreach ($components as $k => $component) {
                foreach ($component as $node) {
                    $label[$node] = $k;
                }
            }
            foreach ($forest->getBorders() as $border) {
                $deal = function ($f) use ($cheapest, $label, $border) {
                    if ($cheapest[$label[$f]] == null || ($border->getWeight() < $cheapest[$label[$f]]->getWeight())) {
                        $cheapest[$label[$f]] = $border;
                    }
                };
                $deal($border->getFrom());
                $deal($border->getTo());
            }
            $count = 0;
            foreach ($this->graph->getBorders() as $border) {
                $from = $border->getFrom();
                $to = $border->getTo();
                if ($label[$from] != $label[$to]) {
                    if ($cheapest[$label[$from]] == null) {
                        $cheapest[$label[$from]] = $border;
                    } else if ($border->getWeight() < $cheapest[$label[$from]]->getWeight()) {
                        $cheapest[$label[$from]] = $border;
                    }
                    if ($cheapest[$label[$to]] == null) {
                        $cheapest[$label[$to]] = $border;
                    } else if ($border->getWeight() < $cheapest[$label[$to]]->getWeight()) {
                        $cheapest[$label[$to]] = $border;
                    }
                }
            }
            array_map(
                fn (?Border $border) => $border != null
                    ? ((++$count) && $forest->addBorder($border))
                    : null,
                $cheapest
            );
            if ($count == 0) break;
        }
        return $forest;
    }
}
