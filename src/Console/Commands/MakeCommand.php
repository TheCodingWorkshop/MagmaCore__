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

namespace MagmaCore\Console\Commands;

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\Exception\BaseUnexpectedValueException;
use MagmaCore\Console\ConsoleCommand;
use MagmaCore\Utility\Files;
use MagmaCore\Utility\Stringify;

class MakeCommand extends ConsoleCommand
{

    /* @var string comomand name */
    protected string $name = 'magma:make';
    /* @var string command description */
    protected string $description = 'Make command can make class controllers, models, entities, forms etc...';
    /* @var string command help */
    protected string $help = 'Command which can generate a class file from a set of predefined stub files';
    /* @var array stubs */
    private const STUBS = [
        'controller'    => 'App\Controller\Admin',
        'column'        => 'App\DataColumns',
        'repository'    => 'App\Repository',
        'fillable'      => 'App\Database\Fillable',
        'schema'        => 'App\Database\Schema',
        'form'          => 'App\Forms',
        'entity'        => 'App\Entity',
        'model'         => 'App\Model',
        'validate'      => 'App\Validate',
        'event'         => 'App\Event',
        'listener'      => 'App\EventListener',
        'subscriber'    => 'App\EventSubscriber',
        'middleware'    => 'App\Middleware'
    ];
    /* @var array command arguments */
    protected array $args = [
        [
            'resource',
            'required',
            'What do you want to make. You can make stuff like [controller, model, entity, form, schema etc..'
        ],
//        [
//            'name',
//            'required',
//            'The singular name of the class you are making. ie user, post, role. Note this is case in-sensitive'
//        ]
    ];

    protected array $options = [
        [
            'crud',
            '-c',
            'optional',
            'Create the crud resource for your generated class.'
        ]
    ];

    public function dispatch(): int
    {
        $stub = $this->getArgument('resource');
        $options = $this->getOptions('crud');

        $this->resolveResource($stub, $options);

        $this->terminalRaw(sprintf('File created %s and crud %s', $stub, $options));
        return ConsoleCommand::SUCCESS;
    }

    /**
     * @param string $resource
     * @param mixed $options
     */
    private function resolveResource(string $resource, mixed $options)
    {
        if (is_string($resource) && !empty($resource)) {
            /* we have a resource lets break it down first lets explode the strin buy the underscore delimiter */
            $elements = explode('_', $resource);
            /*
            if natural convention is followed we can determin that we will get back 2 element within the explode
            method which returns an array of thej strings
            the first element can be determine as the controller name and the second element as the stub file. Stubs
            files are used to generate the actual class file
            */
            /* user_controller */
            $classNamePrefix = array_shift($elements) ?? null; // class file
            $classNameSuffix = array_pop($elements); // stub file

            /* Lets the resolve the suffix of the class aka stub */
            return $this->resolveClassNameSuffix($classNameSuffix, $classNamePrefix);

        }
    }

    /**
     * @param string $classNameSuffix
     * @param string $classNamePrefix
     */
    private function resolveClassNameSuffix(string $classNameSuffix, string $classNamePrefix)
    {
        /* throw an exception if the argument is empty */
        (!empty($classNameSuffix) ?: throw new BaseInvalidArgumentException('Your stub file is invalid or no argument is supplied.'));
        (in_array($classNameSuffix, array_keys(self::STUBS)) ?: throw new BaseInvalidArgumentException('Your stub is an invalid stub. Please refer to the allowable stubs your can create. ' . implode(',', array_keys(self::STUBS))));

        $file = $this->getStubFiles($classNameSuffix);
        /* replace the placeholder variables with valid strings */
        $contents = $this->resolveStubContentPlaceholders($file, $classNameSuffix, $classNamePrefix);

        file_put_contents('', $contents);
        var_dump($contents);
        die;
    }

    /**
     * @param string $classNameSuffix
     * @return string|false
     */
    private function getStubFiles(string $classNameSuffix): string|false
    {
        $files = glob(ROOT_PATH . '/vendor/magmacore/magmacore/src/Stubs/*.stub');
        if (is_array($files) && count($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    /* pluck a stub file from the array of files and return the matching stub */
                    if (str_contains($file, ucwords($classNameSuffix))) {
                        return $file;
                    }
                }
            }
        }
        return false;

    }

    /**
     * @param string $file
     * @param string $classNameSuffix
     * @param string $classNamePrefix
     * @return false|string|void
     */
    private function resolveStubContentPlaceholders(string $file, string $classNameSuffix, string $classNamePrefix)
    {
        if ($file) {
            $contentStream = file_get_contents($file);
            if ($contentStream !='') {
                $patterns = ['{{ class }}', '{{ namespace }}', '{{ property }}', '{{ table_name }}', '{{ modelName }}', '{{ modelVar }}'];
                foreach ($patterns as $pattern) {
                    if (str_contains($contentStream, $pattern)) {
                        if (isset($pattern) && $pattern !='') {

                            $qualifiedClass = Stringify::studlyCaps($classNamePrefix . ucwords($classNameSuffix));
                            $qualifiedNamespace = array_filter(self::STUBS, fn($value, $key) => $value, ARRAY_FILTER_USE_BOTH);
                            $_namespace = '';

                            $stubFile = strrchr($file, '/');
                            $stubFile = str_replace(['/Example', '.stub'], '', $stubFile);
                            $_namespace = '';
                            foreach ($qualifiedNamespace as $namespace) {
                                if (str_contains($namespace, $stubFile)) {
                                    $_namespace = $namespace;
                                    continue;
                                }
                            }

                            /* resolve table_name placeholder for model class */
                            $tableName = Stringify::pluralize($classNamePrefix) ?? '';
                            /* fill the property placeholder */
                            $property = Stringify::camelCase($classNamePrefix . ucwords($classNameSuffix)) ?? '';
                            /* resolve class which uses a model as a dependency */
                            list($modelName, $modelVar) = $this->resolveModelDependency($classNamePrefix, $classNameSuffix);
                            return str_replace($patterns,
                                [$qualifiedClass, $_namespace . ';', $property, $tableName, $modelName, $modelVar],
                                $contentStream
                            );
                        }

                    }
                }
            }
        }
        return false;
    }

    /**
     * @param string $classNamePrefix
     * @param string $classNameSuffix
     * @return array
     */
    private function resolveModelDependency(string $classNamePrefix, string $classNameSuffix): array
    {
        if ($classNameSuffix === 'fillable' || $classNameSuffix === 'schema' || $classNameSuffix === 'repository') {
            $model = Stringify::studlyCaps($classNamePrefix . 'Model');
            $property = Stringify::camelCase($classNamePrefix . 'Model') ?? '';
            return [
                $model,
                $property
            ];
        }

        /* construct the model dependency */
    }


}