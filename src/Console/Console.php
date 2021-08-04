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

use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Console\ConsoleCommandInterface;
use MagmaCore\Console\Exception\ConsoleCommandFileMissingException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Utility\Yaml;


class Console implements ConsoleInterface
{

    /* @var object */
    private $application;
    /* @var string console name */
    private string $consoleName = 'MagmaCore Console Application';
    /* @var string console version */
    private string $consoleVersion = '1.0.0 (beta)';

    /**
     * Create the bootstrap console application. By initializing symfony application object
     * and executing the run() method
     *
     * @throws \Exception
     * @return void
     */
    public function create()
    {
        $this->application = new Application($this->consoleName, $this->consoleVersion);
        $this->application->setCommandLoader($this->registerCommands());
        return $this->application->run();
    }

    /**
     * Returns the core config commands.yml file which defines all the core command
     * @return array
     */
    private function getCommands(): array
    {
        return Yaml::file('commands');
    }

    private function getCommandsValue()
    {
        foreach ($this->getCommands() as $name => $command) {
            if (isset($name)) {
                $commandName = $command['name'] ?? null;
                $commandClass = $command['class'] ?? null;
                (is_string($commandName) ?: throw new BaseInvalidArgumentException("Invalid {$commandName}. This should be a string"));
                (class_exists($commandClass) ?: throw new BaseLogicException("{$commandClass} is missing from the commander directory."));
                return [
                    $commandName,
                    $commandClass,
                    $command['description'] ?? null,
                    $command['help'] ?? null,
                    $command['stubs'] ?? null,
                    $command['argument'] ?? null,
                    $command['options'] ?? null
                ];
            }
        }
    }

    /**
     * Register our core command with the symfony application add method
     */
    public function registerCommands()
    {
        list(
            $commandName,
            $commandClass,
            $description,
            $help,
            $stubs,
            $arguments,
            $options) = $this->getCommandsValue();
        try {
            $initClass = new \MagmaCore\Console\Commands\MakeCommand();
            if ($initClass instanceof ConsoleCommand) {
                return new FactoryCommandLoader([
                    'magma:make' => function () use($initClass) { return $initClass; }
                ]);

            }
        } catch(BaseLogicException) {

        }

    }


}
