<?php

/**
 * @author Tianle Xu <xtl@xtlsoft.top>
 */

namespace Boruvka;

class Graph
{
    protected array $nodeAssociation;

    // Storage rule:
    // Tuple(Node, Slice(Border))
    protected array $nodes = [];
    /**
     * @var Border[]
     */
    protected array $borders = [];

    public function __construct(
        array $nodes = [],
        array $borders = []
    ) {
        array_map(fn (Node $node): self => $this->addNode($node), $nodes);
        array_map(fn (Border $border): self => $this->addBorder($border), $borders);
    }

    public function addNode(Node $node): self
    {
        $this->nodes[$node->getID()] = [$node, []];
        return $this;
    }

    public function hasNode(int $id): bool
    {
        return array_key_exists($id, $this->nodes);
    }

    public function addBorder(Border $border): self
    {
        if (!$this->hasNode($border->getFrom()) || !$this->hasNode($border->getTo())) {
            throw new \Exception("");
        }
        $this->borders[] = $border;
        foreach ([$border->getFrom(), $border->getTo()] as $n) {
            $this->nodes[$n][1][] = $border;
        }
        return $this;
    }

    public function dumpGraph(): void
    {
        foreach ($this->nodes as $node) {
            echo "{$node[0]->getID()}({$node[0]->getWeight()}) ";
        }
        echo "\r\n";
        foreach ($this->borders as $border) {
            echo "{$border->getFrom()}<-{$border->getWeight()}->{$border->getTo()}\r\n";
        }
    }

    /**
     * Get Nodes
     *
     * @return Node[]
     */
    public function getNodes(): array
    {
        return array_map(fn (array $node): Node => $node[0], $this->nodes);
    }

    /**
     * Get Borders
     *
     * @return Border[]
     */
    public function getBorders(): array
    {
        return $this->borders;
    }

    public function hasRing(): bool
    {
        $degrees_val = array_map(fn (array $node): int => count($node[1]), $this->nodes);
        $degrees_key = array_map(fn (array $node): int => $node[0]->id, $this->nodes);
        $degrees = array_combine($degrees_key, $degrees_val);
        $queue = new \SplQueue();
        $visited = 0;
        foreach ($degrees as $id => $item) {
            if ($item <= 1) {
                $queue->push($id);
                ++$visited;
            }
        }
        while (!$queue->isEmpty()) {
            $item = $queue->pop();
            $next = $this->nodes[$item][1];
            foreach ($next as $nxt) {
                $id = $nxt->getFrom() == $item ? $nxt->getTo() : $nxt->getFrom();
                $degrees[$id]--;
                if ($degrees[$id] == 1) {
                    $queue->push($id);
                    ++$visited;
                }
            }
        }
        return $visited != count($this->nodes);
    }

    protected function forestDFS(int $id, array &$nodes): void
    {
        foreach ($this->nodes[$id][1] as $nxt) {
            $nid = $nxt->getFrom() == $id ? $nxt->getTo() : $nxt->getFrom();
            if ($nodes[$nid] == -1) {
                $nodes[$nid] = $nodes[$id];
                $this->forestDFS($nid, $nodes);
            }
        }
    }

    public function asForest(): array
    {
        $rslt = [];
        $curr_color = -1;
        $nodes_val = array_map(fn (): int => -1, $this->nodes);
        $nodes_key = array_map(fn (array $node): int => $node[0]->getID(), $this->nodes);
        $nodes = array_combine($nodes_key, $nodes_val);
        foreach ($nodes_key as $id) {
            if ($nodes[$id] == -1) {
                $curr_color++;
                $nodes[$id] = $curr_color;
            }
            if (!array_key_exists($nodes[$id], $rslt)) {
                $rslt[$nodes[$id]] = [];
            }
            $rslt[$nodes[$id]][] = $id;
            $this->forestDFS($id, $nodes);
        }
        return $rslt;
    }
}
