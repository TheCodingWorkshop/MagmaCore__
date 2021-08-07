<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\Console;

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Base\Exception\BaseLogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

abstract class ConsoleCommand extends Command implements ConsoleCommandInterface
{

    protected string $name;
    protected string $description;
    protected string $help;
    protected array $args = []; /* refers to name, type, description argument */
    protected array $options = []; /* refers to name, shortcut, type, description and default */
    protected InputInterface $input;
    protected OutputInterface $output;

    /**
     * Set the default command name, description and help strings
     * ConsoleCommand constructor.
     * @param string|null $name
     */
    public function __construct()
    {
        $this->setName($this->name)
            ->setDescription($this->description)
            ->setHelp($this->help);

        parent::__construct();

    }

    /**
     * Configuret the command
     * @return void`
     * @throws BaseInvalidArgumentException
     * @throws BaseLogicException
     */
    public function configure(): void
    {
        try {
            $this->setArguments();
            $this->setOptions();
        } catch(BaseLogicException|BaseInvalidArgumentException) {
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        return $this->dispatch();
    }

    /**
     * Returns the argument value for the given argument name
     * @param string|null $key
     * @return mixed
     */
    protected function getArgument(?string $key = null): mixed
    {
        return $this->input->getArgument($key) ?? '';
    }

    /**
     * Returns the option value for the given option name
     * @param string|null $key
     * @return bool|string|string[]
     */
    protected function getOptions(?string $key = null): mixed
    {
        return $this->input->getOption($key) ?? '';
    }

    /**
     * Outputs the string to the console without any tag
     * @param string $string
     * @return mixed
     */
    protected function terminalRaw(string $string): mixed
    {
        return $this->output->writeln($string);
    }

    /**
     * output to the terminal wrap in info tags
     * @param string $string
     * @return string
     */
    protected function terminalInfo(string $string): mixed
    {
        return $this->output->writeln('<info>' . $string . '</info>');
    }

    /**
     * output to the terminal wrap in comment tags
     * @param string $string
     * @return string
     */
    protected function terminalComment(string $string): mixed
    {
        return $this->output->writeln('<comment>' . $string . '</comment>');
    }

    /**
     * output to the terminal wrap in question tags
     * @param string $string
     * @return string
     */
    protected function terminalQuestion(string $string): mixed
    {
        return $this->output->writeln('<question>' . $string . '</question>');
    }

    /**
     * output to the terminal wrap in error tags
     * @param string $string
     * @return string
     */
    protected function terminalError(string $string): mixed
    {
        return $this->output->writeln('<error>' . $string . '</error>');
    }

    /**
     * $arg[0] = argument name, $arg[1] = argument type and $arg[2] = argument description
     * @return ConsoleCommand
     * @throws BaseInvalidArgumentException
     */
    private function setArguments()
    {
        if (count($this->args) > 0) {
            foreach ($this->args as $arg) {
                return match($arg[1]) { /* match based on the argument type */
                    'required' => $this->addArgument($arg[0], InputArgument::REQUIRED, $arg[2]),
                    'optional' => $this->addArgument($arg[0], InputArgument::OPTIONAL, $arg[2]),
                    'array' => $this->addArgument($arg[0], InputArgument::IS_ARRAY, $arg[2]),
                    default => throw new BaseInvalidArgumentException('Invalid input argument passed')
                };
            }
        }
        return false;
    }

    /**
     * @return ConsoleCommand
     * @throws BaseInvalidArgumentException
     */
    private function setOptions()
    {
        if (count($this->options) > 0) {
            foreach ($this->options as $option) {
                return match($option[2]) {
                    'none' => $this->addOption($option[0], $option[1], InputOption::VALUE_NONE, $option[3]),
                    'required' => $this->addOption($option[0], $option[1], InputOption::VALUE_REQUIRED, $option[3]),
                    'optional' => $this->addOption($option[0], $option[1], InputOption::VALUE_OPTIONAL, $option[3]),
                    'array' => $this->addOption($option[0], $option[1], InputOption::VALUE_IS_ARRAY, $option[3]),
                    'negatable' => $this->addOption($option[0], $option[1], InputOption::VALUE_NEGATABLE, $option[3]),
                    default => throw new BaseInvalidArgumentException('Invalid input argument passed')
                };
            }
        }
        return false;
    }

}