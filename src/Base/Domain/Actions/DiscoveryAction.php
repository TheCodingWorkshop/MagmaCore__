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

namespace MagmaCore\Base\Domain\Actions;

use MagmaCore\Base\Domain\DomainActionLogicInterface;
use MagmaCore\Base\Domain\DomainTraits;
use MagmaCore\Utility\Stringify;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class DiscoveryAction implements DomainActionLogicInterface
{

    use DomainTraits;

    private object $controller;
    private string $method;
    private ?string $schema;


    /**
     * execute logic for adding new items to the database()
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return IndexAction
     */
    public function execute(
        object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = [],
        mixed $optional = null
    ): self {

        $this->controller = $controller;
        $this->method = $method;
        $this->schema = $objectSchema;

        if (isset($formBuilder) && $formBuilder->isFormValid($this->getSubmitValue())) :
            $dbControllers = array_column($controllerrepository->getRepo()->findAll(), 'controller');
            $dirPath = ROOT_PATH . '/App/Controller/Admin';
            $files = $this->dirToArray($dirPath);

            /* format the array to script away the controller suffix and the file extension */
            $fileArray = array_map(function($file) {
                $format = str_replace('Controller.php', '', $file);
                return strtolower($format);
            }, $files);

            $differences = array_diff($fileArray, $dbControllers);
            if (count($differences) > 0) {
                array_map(function($difference) {
                    $classNamespace = '\App\Controller\Admin\\' . Stringify::studlyCaps($difference . 'Controller');
                    return $this->pingMethods($difference, $classNamespace);
                }, $differences);
                $this->flashMessage(sprintf('%s controller was discovered. And successfully registered', count($differences)));
                $this->redirect('/admin/discovery/discover');

            } else {
                $this->flashMessage('No controller was discovered', $this->flashWarning());
                $this->redirect('/admin/discovery/discover');

            }

        endif;

        return $this;
    }

}
