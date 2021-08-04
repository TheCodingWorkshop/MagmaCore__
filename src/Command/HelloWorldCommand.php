<?php

declare(strict_types=1);

namespace MagmaCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class HelloWorldCommand extends Command
{

    protected function configure(): void
    {
        $this->setName('magma:make')
            ->setDescription('Make command can make class controllers, models, entities, forms etc... ')
            ->setHelp('Command which can generate a class file from a set of predefined stub files')
            ->addArgument('stub', InputArgument::REQUIRED, 'What do you want to make. You can make stuff like [controller, model, entity, form, schema etc..]')
            ->addArgument('name', InputArgument::REQUIRED, 'The singular name of the class you are making. ie user, post, role. Note this is case in-sensitive')
            ->addOption('crud', '-c', InputOption::VALUE_OPTIONAL, 'Create the crud resource for your generated class.');

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $stub = $input->getArgument('stub');
        /* using the argument we can search for the correct stub file to load */
        $file = $input->getArgument('name');
        /* we can then format the name and prefix it with the value of the $stub variable */
        /* we will then call external methods to handle checks and validation */
        /* then output the command */
//        $output->writeln();
        $options = $input->getOption('crud');
        $output->writeln('<info>' . sprintf('File created %s %s and crud %s', $stub, $file, $options) . '</info>');
        return Command::SUCCESS;
    }

}
