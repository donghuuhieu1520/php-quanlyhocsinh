<?php declare(strict_types = 1);
// Setup Doctrine
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$configuration = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../Entities'], true, null, null, false);

$connection_parameters = include('src/Config/connection.php');

$entity_manager = EntityManager::create($connection_parameters['mysql'], $configuration);
