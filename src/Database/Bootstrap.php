<?php declare(strict_types = 1);
// Setup Doctrine
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../../.env');

$configuration = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . '/../Entities'], true, null, null, false);
$connection_parameters = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'],
    'driverOptions'	=> ['1002'=> "SET NAMES 'UTF8' COLLATE 'utf8_general_ci'"]
];

$entity_manager = EntityManager::create($connection_parameters, $configuration);
