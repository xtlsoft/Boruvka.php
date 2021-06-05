<?php

use Boruvka\Border;
use Boruvka\Boruvka;
use Boruvka\Graph;
use Boruvka\Node;

require_once "vendor/autoload.php";

$nodes = Node::fromArray([
    [0, 1.0],
    [1, 1.0],
    [2, 1.0],
    [3, 1.0],
    [4, 1.0],
]);

$borders = Border::fromArray([
    [0, 1, 4],
    [1, 2, 2],
    [2, 3, 8],
    [3, 4, 9],
    [4, 0, 6],
    [0, 3, 6],
    [0, 2, 4]
]);

$graph = new Graph($nodes, $borders);
$graph->dumpGraph();

var_dump($graph->asForest());

$boruvka = new Boruvka($graph);

$forest = $boruvka->calculate();

var_dump($forest->asForest());

$forest->dumpGraph();
