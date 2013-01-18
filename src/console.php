<?php

// @note @php @symfony FR : créer une application console
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

use Ribbit\Entity\User;

$console = new Application('My Silex Application', 'n/a');

### BEGINCUSTOMCODE
/* @var Doctrine\ORM\EntityManager $em  */
$em = $app["em"];
$console
        ->register('ribbit:register-user')
        ->setDefinition(array(
            new InputOption('username', "u", InputOption::VALUE_REQUIRED, 'user username'),
            new InputOption('email', "e", InputOption::VALUE_REQUIRED, 'user email'),
            new InputOption('name', "a", InputOption::VALUE_OPTIONAL, 'user realname'),
            new InputOption('password', "p", InputOption::VALUE_OPTIONAL, 'user password',"password"),
            new InputOption('salt', "s", InputOption::VALUE_OPTIONAL, 'password salt',"salt"),
        ))
        ->setDescription('create a new user with default values')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
                    $output->writeln("creating user ".$input->getOption("username"));
                    $user = new User();
                    $user->setUsername($input->getOption("username"));
                    $user->setName($input->getOption("name"));
                    $user->setEmail($input->getOption("email"));
                    $user->setPassword($input->getOption("password"));
                    $user->setSalt($input->getOption("salt"));
                    $newUser = $app["user_manager"]->register($user);
                    if($newUser){
                        $output->writeln("new user created with ID {$newUser->getId()}.");
                    }else{
                        $output->writeln("Error registering new user");
                    }
                }
);
// EN : create roles
// FR : créer des roles
$console->register("ribbit:create-role")
        ->setDefinition(array(
            new InputOption("title", "t", InputOption::VALUE_REQUIRED, "role's title"),
        ))
        ->setDescription('create a new role')
        ->setCode(function(InputInterface $input, OutputInterface $output)use($em) {
                    $options = $input->getOptions();
                    $role = new Ribbit\Entity\Role();
                    if ($options["title"]) {
                        $role->setTitle($options["title"]);
                        $em->persist($role);
                        $em->flush();
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
        ->setCode(function(InputInterface $input, OutputInterface $output)use($em) {
                    /** @var $em \Doctrine\ORM\EntityManager */
                    $roles = $em->getRepository("Ribbit\Entity\Role")->findAll();
                    foreach ($roles as $role) {
                        $output->writeln($role->getTitle());
                    }
                }
);
// Configure Doctrine ORM tool for Console cli
$console->setHelperSet(new HelperSet(array(
    "em" => new EntityManagerHelper($em),
    "db" => new ConnectionHelper($em->getConnection()),
        )
        )
);
Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($console);
### ENDCUSTOMCODE
return $console;
