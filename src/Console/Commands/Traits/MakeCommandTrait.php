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

namespace MagmaCore\Console\Commands\Traits;

use MagmaCore\Console\Exception\MakeCommandFileAlreadyExistException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\Exception\BaseRuntimeException;
use MagmaCore\Utility\Files;
use MagmaCore\Utility\Stringify;
use MagmaCore\Utility\Utilities;

trait MakeCommandTrait
{
    /**
     * @param string $resource
     * @param mixed $options
     */
    private function resolveResource(string $resource, mixed $options)
    {
        if (is_string($resource) && !empty($resource)) {
            $elements = explode('_', $resource);
            $classNamePrefix = array_shift($elements) ?? null; // class file
            $classNameSuffix = array_pop($elements); // stub file

            /* Lets the resolve the suffix of the class aka stub */
            return $this->resolveClassNameSuffix($classNameSuffix, $classNamePrefix, $options);

        }
    }

    /**
     * @param string $classNameSuffix
     * @param string $classNamePrefix
     */
    private function resolveClassNameSuffix(string $classNameSuffix, string $classNamePrefix, mixed $options = null)
    {
        /* throw an exception if the argument is empty */
        (!empty($classNameSuffix) ?: throw new BaseInvalidArgumentException('Your stub file is invalid or no argument is supplied.'));
        (in_array($classNameSuffix, array_keys(self::STUBS)) ?: throw new BaseInvalidArgumentException('Your stub is an invalid stub. Please refer to the allowable stubs your can create. ' . implode(', ', array_keys(self::STUBS))));

        $file = $this->getStubFiles($classNameSuffix);
        /* replace the placeholder variables with valid strings */
        list(
            $newContentStream,
            $qualifiedClass,
            $qualifiedNamespace) = $this->resolveStubContentPlaceholders($file, $classNameSuffix, $classNamePrefix);

        return $this->createClassFromStub($qualifiedClass, $newContentStream, $classNameSuffix, $options, $qualifiedNamespace);
    }

    /**
     * Create the class file based on the stub file. Once the file is resolved and have a valid directory path
     * and the stub content is properly filtered and change to reflect. Then and only then we will
     * generate the actual usable class file.
     *
     * Note. realpath will return false if the file or directory does not exists
     *
     * @param string $qualifedClass
     * @param string|null $contentStream
     * @param string|null $classNameSuffix
     * @param mixed|null $options
     * @param string|null $qualifiedNamespaces - will return the namespace for the stub command
     * @return void
     * @throws \Exception
     */
    public function createClassFromStub(string $qualifiedClass, ?string $contentStream = null, ?string $classNameSuffix = null, mixed $options = null, ?string $qualifiedNamespaces = null)
    {
        if ($classNameSuffix === null || $contentStream === null) {
            throw new BaseRuntimeException('Directory could not be created because the 3rd argument returned null');
        }
        $filePath = ROOT_PATH . DIRECTORY_SEPARATOR . $qualifiedNamespaces . $this->addOptionalDirFlag($options);
        if (!is_dir($filePath)) {
            Files::createDirectoryRecursively($filePath);
        }
        $realFilepath = realpath($filePath);
        $className = $qualifiedClass . self::FILE_EXTENSION;
        $newClassFileAndPath = $realFilepath . DIRECTORY_SEPARATOR . $className;

        /* We will need to check $newClassFileAndPath doesn't already exist else this will wipe the content */
        if (file_exists($newClassFileAndPath)) {
            throw new MakeCommandFileAlreadyExistException(sprintf('%s file already exists. To recreate you will first need to delete the existing file.', $className));
        }
        file_put_contents($newClassFileAndPath, $contentStream, LOCK_EX);


    }

    /**
     * console command option flag. Use --dir={directory_name} to add a directory to the end
     * of the filepath to create a sub directory within a main directory
     *
     * @param mixed $options
     * @return string
     */
    private function addOptionalDirFlag(mixed $options): string
    {
        return (isset($options) && $options !=='' || $options !==null)
            ? DIRECTORY_SEPARATOR . Stringify::capitalize($options) :
            '';
    }

    /**
     * Uses the php glob to retrieve all stub files form the relevant directory. Which will return
     * an array of files within the specified directory with the [.stub] extension.
     * We then iterate over that array and uses php str_contain function to match a file from
     * the array with the classNameSuffix one we have a match then return the matching file string
     *
     * @param string $classNameSuffix
     * @return string|false
     */
    private function getStubFiles(string $classNameSuffix): string|false
    {
        $files = glob(ROOT_PATH . '/vendor/magmacore/magmacore/src/Stubs/*.stub');
        if (is_array($files) && count($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    if (str_contains($file, ucwords($classNameSuffix))) {
                        /* return the matching file bases on the class name suffix */
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
    private function resolveStubContentPlaceholders(string $file, string $classNameSuffix, string $classNamePrefix): array
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
                            $newContentStream = str_replace($patterns,
                                [$qualifiedClass, $_namespace . ';', $property, $tableName, $modelName, $modelVar],
                                $contentStream
                            );

                            return [
                                $newContentStream,
                                $qualifiedClass,
                                $_namespace
                            ];
                        }

                    }
                }
            }
        }
        return false;
    }

    /**
     * Resolve the model dependency by specifing which stubs class will require a model
     * @param string $classNamePrefix
     * @param string $classNameSuffix
     * @return array
     */
    private function resolveModelDependency(string $classNamePrefix, string $classNameSuffix): mixed
    {
        if ($classNameSuffix === 'fillable' || $classNameSuffix === 'schema' || $classNameSuffix === 'repository') {
            $model = Stringify::studlyCaps($classNamePrefix . 'Model');
            $property = Stringify::camelCase($classNamePrefix . 'Model') ?? '';
            return [
                $model,
                $property
            ];
        }
        return false;

    }

    private function resolveMigrationFromOptions(mixed $options)
    {
        var_dump($options);
        die;
    }

}