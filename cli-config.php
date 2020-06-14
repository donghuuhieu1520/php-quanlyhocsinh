<?php
// cli-config.php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once "src/Database/Bootstrap.php";

return ConsoleRunner::createHelperSet($entity_manager);
