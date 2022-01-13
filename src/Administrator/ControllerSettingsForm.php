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

namespace MagmaCore\Administrator;

use Exception;
use MagmaCore\Base\BaseController;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class ControllerSettingsForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @return void
     */
    public function __construct(FormBuilderBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        parent::__construct();
    }

    /**
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return string
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        $controller = new BaseController([]);
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "tableForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text(
                    'records_per_page',
                    ['uk-form-width-small', 'uk-form-small', 'uk-form-blank', 'border-bottom'],
                    $this->hasValue('records_per_page')
                ),
                null,
                $this->blueprint->settings(false, null, false)
            )
            ->add(
                $this->blueprint->text(
                    'query',
                    ['uk-form-width-medium', 'uk-form-small', 'uk-form-blank', 'border-bottom'],
                    $this->hasValue('query'),
                    false,
                    'Query'
                ),
                null,
                $this->blueprint->settings(false, null, false)
            )
            ->add(
                $this->blueprint->text(
                    'alias',
                    ['uk-form-width-medium', 'uk-form-small', 'uk-form-blank', 'border-bottom', 'uk-margin-small-bottom'],
                    $this->hasValue('alias'),
                    false,
                    'Filter Alias'
                ),
                null,
                $this->blueprint->settings(false, null, false)
            )
//            ->add(
//                $this->blueprint->radio(
//                    'searchable',
//                    ['uk-radio'],
//                    $this->hasValue('searchable'),
//                ),
//                $this->blueprint->choices(
//                    array_reverse($searchable = $controller->getSearchableColumns($callingController->column)),
//                    $searchable['1']
//                ),
//                $this->blueprint->settings(false, null, true)
//            )
            ->add(
                $this->blueprint->submit(
                    'settings-' . $callingController->thisRouteController() . '',
                    ['uk-button', 'uk-button-primary', 'uk-button-small'],
                    'Save'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
