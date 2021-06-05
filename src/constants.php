<?php

namespace Boruvka;

const BORUVKA_GRAPH_NON_DIRECTIONAL = true;

function swap(mixed &$a, mixed &$b): void
{
    list($a, $b) = [$b, $a];
}
