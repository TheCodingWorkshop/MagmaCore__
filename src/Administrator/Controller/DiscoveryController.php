<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\Administrator\Controller;

use MagmaCore\Utility\Stringify;
use MagmaCore\Utility\Serializer;
use MagmaCore\Utility\UtilityTrait;
use App\Commander\DiscoveryCommander;
use MagmaCore\Base\Domain\Actions\DiscoverAction;
use MagmaCore\Administrator\Model\ControllerDbModel;
use MagmaCore\Administrator\Forms\DiscoveryPermissionsForm;

class DiscoveryController extends AdminController
{

    use UtilityTrait;

    /**
     * @param array $routeParams
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'repository' => ControllerDbModel::class,
                'discoverAction' => DiscoverAction::class,
                'discoveryAction' => DiscoverAction::class,
                'commander' => DiscoveryCommander::class,
                'discoveryPermissionsForm' => DiscoveryPermissionsForm::class
            ]
        );
    }

    /**
     * Discover new controller through system sessions
     */
    protected function discoverAction()
    {
        $repository = $this->repository;
        $singular = $this->repository->getRepo()->findObjectBy(['id' => (int)$_GET['edit']]);
        $this->discoverAction
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'controllers' => $repository->getRepo()->findAll(),
                    'count_controller' => (int)$repository->getRepo()->count(),
                    'controller_singular' => $singular,
                    'methods' => Serializer::unCompress($singular->methods ?? []),
                    'new_methods' => Serializer::unCompress($singular->current_new_method ?? []),
                    'session' => $this->getSession(),
                    'session_discovery' => $this->getSession()->get('controller_discovery'),
                    'discoveries' => $this->showDiscoveries(),
                    'new_controller_discovery' => $this->getSession()->get('new_controller_discovered'),
                    'discovery_permission_form' => $this->discoveryPermissionsForm->createForm('', null, $this)
                ]
            )
            ->callback(fn($controller) => '')
            ->end();
    }

    protected function discoverControllerAction()
    {
        if (isset($_POST['discoverController-discovery'])) :
            $dbControllers = array_column($this->repository->getRepo()->findAll(), 'controller');
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

    }

    protected function installAction()
    {
        if ($this->formBuilder->isFormvalid('install-discovery')) {
            $formData = array_key_exists('controller_id', $this->formBuilder->getData()) ? $this->formBuilder->getData() : [];
            $controllerID = (int)$formData['controller_id'];

            /* Get the controller object which matches the current controller querie ID */
            $controller = $this->repository->getRepo()->findObjectBy(['id' => $controllerID], ['methods']);
            /* We only need the methods serialize string which we need to unserialize */
            $unSerializeMethods = Serializer::unCompress($controller->methods);

            /* for safety we will check to make sure we are not duplicating any method which might some how already exists */
            $discoveryMethods = $formData['methods'];
            $this->updateMethods($controllerID, $discoveryMethods, $unSerializeMethods);
            $this->flashMessage(sprintf('[%s] method added to the database methods list', count($discoveryMethods)));
            $this->redirect(sprintf('/admin/discovery/discover?edit=%s', $controllerID));

        }
    }


    private function updateMethods(int $controllerID = null, array $discoveryMethods = [], array $unSerializeMethods = []): bool
    {
        if ($this->isArrayCountable($discoveryMethods)) {
            foreach ($discoveryMethods as $discoveryMethod) {
                /* Just ensuring the new method doesn't already exists */
                if (!in_array($discoveryMethod, $unSerializeMethods)) {
                    array_push($unSerializeMethods, $discoveryMethod);

                    /* now lets update the column */
                    $this->repository
                        ->getRepo()
                        ->findByIdAndUpdate(
                            [
                                'methods' => Serializer::compress($unSerializeMethods),
                                'id' => $controllerID,
                                'current_new_method' => NULL, /* reset this field */
                                'current_method_count' => NULL, /* reset the count column as well */
                            ],
                            $controllerID
                        );
                }
            }

        }

        return false;

    }

}
