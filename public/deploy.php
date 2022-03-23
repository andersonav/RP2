<?php

$key = isset($_GET['k']) ? $_GET['k'] : null;

chdir(__DIR__.'/..');

echo exec('php deploy.php '.$key.' > deploy.log &');
