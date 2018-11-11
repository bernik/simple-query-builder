<?php 

use Kahlan\Filters\Filters; 

$commandLine = $this->commandLine();
$commandLine->option("no-header", "default", true);
$commandLine->option("reporter", "default", "bar");