<?php declare(strict_types = 1);

namespace App\Database;
// Setup Doctrine
$configuration = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../Entities'], true, null, null, false);

$connection_parameters = include('src/Config/connection.php');

$entity_manager = \Doctrine\ORM\EntityManager::create($connection_parameters['mysql'], $configuration);