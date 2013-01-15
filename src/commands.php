<?php

// console commands for console.php file

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

$console
        ->register('ribbit:create-user')
        ->setDefinition(array(
            new InputOption('username', "u", InputOption::VALUE_REQUIRED, 'user username'),
            new InputOption('email', "e", InputOption::VALUE_REQUIRED, 'user email'),
            new InputOption('name', "a", InputOption::VALUE_REQUIRED, 'user realname'),
            new InputOption('password', "p", InputOption::VALUE_REQUIRED, 'user password'),
        ))
        ->setDescription('create a new user with default values')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
                    $output->writeln("creating user");
                    $output->writeln(print_r($input->getOptions(), true));
                }
);
// EN : create roles
// FR : créer des utilisateurs
$console->register("ribbit:create-role")
        ->setDefinition(array(
            new InputOption("title", "t", InputOption::VALUE_REQUIRED, "role's title"),
        ))
        ->setDescription('create a new role')
        ->setCode(function(InputInterface $input, OutputInterface $output)use($app) {
                    $options = $input->getOptions();
                    $role = new Ribbit\Entity\Role();
                    if ($options["title"]) {
                        $role->setTitle($options["title"]);
                        $app["em"]->persist($role);
                        $app["em"]->flush();
                        $output->writeln("Role {$role->getTitle()} created with ID {$role->getId()}.");
                        return 0;
                    } else {
                        $output->writeln("Argument title is mandatory, no role created");
                        return 1;
                    }
                }
);
// EN : list roles
// FR : lister les roles
$console->register("ribbit:list-roles")
        ->setDescription("list roles")
        ->setCode(function(InputInterface $input, OutputInterface $output)use($app) {
                    $em = $app["em"];
                    /** @var $em \Doctrine\ORM\EntityManager */
                    $roles = $em->getRepository("Ribbit\Entity\Role")->findAll();
                    foreach ($roles as $role) {
                        $output->writeln($role->getTitle());
                    }
                }
);

$console->setHelperSet(new HelperSet(array(
    "em" => new EntityManagerHelper($app["em"]),
    "db" => new ConnectionHelper($app["em"]->getConnection()),
        )
        )
);

Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($console);