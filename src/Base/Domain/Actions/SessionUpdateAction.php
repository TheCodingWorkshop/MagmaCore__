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

use MagmaCore\Utility\Utilities;
use MagmaCore\Utility\Serializer;
use MagmaCore\Base\Domain\DomainTraits;
use MagmaCore\Base\Domain\DomainActionLogicInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will 
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class SessionUpdateAction implements DomainActionLogicInterface
{

    use DomainTraits;

    /** @var bool */
    protected bool $isRestFul = false;

    /**
     * execute logic for adding new items to the database(). Post data is returned as a collection
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return SettingsAction
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
        $formBuilder = $controller->formBuilder;


        $this->preExecute($controller, $formBuilder, $optional);
        
        if (isset($formBuilder) && $formBuilder?->isFormValid($this->getSubmitValue())) :
            if ($formBuilder?->csrfValidate()) {
                /* the data being submitted from the form which will become the new session data*/
                $formData = $formBuilder->getData();
                /* Get the old session data */
                $session = $controller->getSession();
                $sessionData = $session->get($channel = $controller->thisRouteController() . '_settings');

                if (is_array($formData) && count($formData) > 0) {
                    
                    /* Unset unwanted data */
                    unset(
                        $formData['_CSRF_INDEX'], 
                        $formData['_CSRF_TOKEN'], 
                        $formData[$this->getSubmitValue()]
                    );
                    /* Uncompress the old session data */
                    $oldSession = Serializer::unCompress($sessionData);
                    if ($oldSession['controller'] === $controller->thisRouteController()) {
                        $key = $controller->thisRouteController() . '_settings';
                        /* override the old session with new session form post data */
                        $newArray = $formData + $oldSession;
                        if (is_array($newArray) && count($newArray) > 0) {
                                
                            /* flush the old session data */
                            $session->delete($key);
                            /* generate the new session data */
                            $session->set($key, Serializer::compress($newArray));

                            $this->dispatchSingleActionEvent(
                                $controller,
                                $eventDispatcher,
                                $method,
                                ['sessionData' => $newArray],
                                $additionalContext
                            );

                        }
                    }
                }

                $controller->flashMessage('Settings Updated');
                $controller->redirect($controller->onSelf());
            }
        endif;
        return $this;
    }
}
