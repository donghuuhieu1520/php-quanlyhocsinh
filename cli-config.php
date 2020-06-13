<?php
require_once './src/Database/Bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entity_manager)
));

return $helperSet;
