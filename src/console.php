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
/**
 * FR : efface le fichier de logs
 * EN : Erase log file
 */
$console->register("ribbit:log-delete")
        ->setDefinition(array())
        ->setDescription("Delete log file")
        ->setCode(function(InputInterface $input, OutputInterface $output)use($app) {
                    if (isset($app["monolog.logfile"])) {
                        if (unlink($app["monolog.logfile"])) {
                            $output->writeln("file {$app["monolog.logfile"]} removed successfully.");
                        } else {
                            $output->writeln("file {$app["monolog.logfile"]} not removed.");
                        }
                    } else {
                        $output->writeln("no log file defined.");
                    }
                }
);
/**
 * FR : créer un nouvel utilisateur
 * EN : create a new user
 */
$console->register("ribbit:user-create")
        ->setDefinition(array(
            new InputOption("username", "u", InputOption::VALUE_REQUIRED, "username"),
            new InputOption("email", "e", InputOption::VALUE_REQUIRED, "email"),
            new InputOption("realname", "r", InputOption::VALUE_REQUIRED, "real name"),
            new InputOption("password", "p", InputOption::VALUE_REQUIRED, "password"),
        ))
        ->setDescription("create a new user")
        ->setCode(function(InputInterface $in, OutputInterface $out)use($app) {
                    $user = new \Ribbit\Entity\User;
                    $user->setEmail($in->getOption("email"));
                    $user->setPassword($in->getOption("password"));
                    $user->setUsername($in->getOption("username"));
                    $user->setName($in->getOption("realname"));
                    try {
                        $app["user_manager"]->register($user);
                        if ($user->getId()) {
                            $out->writeln("User {$user->getUsername()} created with ID {$user->getId()}.");
                            return 0;
                        }
                    } catch (Exception $e) {
                        $out->writeln("Error creating user {$e->getMessage()}.");
                        return 1;
                    }
                    $out->writeln("No user created.");
                    return 0;
                });
$console->register('ribbit:user-password-change')
        ->setDefinition(array(
            new InputOption('username', "u", InputOption::VALUE_REQUIRED, 'user username'),
            new InputOption('password', "p", InputOption::VALUE_REQUIRED, 'user new password'),
        ))
        ->setDescription('change the user password')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
                    $user = $app["user_manager"]->setNewPasswordByUsername($input->getOption("username"), $input->getOption("password"));
                    if ($user == null) {
                        $output->writeln("User with username '" . $input->getOption("username") . "' not found");
                        return 1;
                    } else {
                        $output->writeln("User with username '" . $user->getUsername() . "' password's updated ");
                        return 0;
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
