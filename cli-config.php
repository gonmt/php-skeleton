<?php

require 'vendor/autoload.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use CompanyName\Apps\FirstApp\Backend\FirstAppBackendKernel;

$config = [
    'migrations_paths' => [
        'DoctrineMigrations' => './migrations'
    ],
];


$kernel = new FirstAppBackendKernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$entityManager = $kernel->getContainer()->get(EntityManager::class);

return DependencyFactory::fromEntityManager(new ConfigurationArray($config), new ExistingEntityManager($entityManager));