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
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Serializer;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
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

    private function radioOptions(string $key = null)
    {
        return [
            $key => [
                'true' => 'true',
                'false' => 'false'
            ]
        ];
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

        $data = $callingController->controllerSessionData($callingController);
        $sessionData = Serializer::unCompress($data);

        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "tableForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text(
                    'records_per_page',
                    ['uk-form-large', 'uk-form-width-small', 'uk-border-bottom', 'uk-form-blank'],
                    $sessionData['records_per_page'],
                    false,
                    'Records Per Page'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, null, 'Your data table pagination can be set here or on the actual index route from the dropdown option beside the lower paging links. <code>Your ' . $callingController->repository->getSchema() . ' table is currently displaying ' . $sessionData['records_per_page'] . ' records per page.</code>')
            )
            ->add($this->blueprint->text(
                'additional_conditions',
                ['uk-textarea', 'uk-form-width-large'],
                $sessionData['additional_conditions'],
                ),
                null,
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    'Additional Conditions', 
                    true, 
                    null, 
                    'Only use this option, if you fully understand how it works. Addition conditions allows you to add additional conditions (aka where clause) to the existing query. <code>current additional conditions running on this query is ' . $sessionData['additional_conditions'] . '</code>. Which means <code><a class="uk-text-danger" href="/admin/user/index">/admin/' . $callingController->thisRouteController() . '/index</a> is only display results based on the conditions set.</code>.'
                )
            )
            // ->add(
            //     $this->blueprint->multipleCheckbox('table_options', [], ['top_paging' => 'top_paging', 'trash_can' => 'trash_can']),
            //     $this->blueprint->choices(
            //         [
            //             'top_paging' => 'top_paging', 
            //             'bottom_paging' => 'bottom_paging',
            //             'bulk_trash' => 'bulk_trash',
            //             'bulk_clone' => 'bulk_clone',
            //             'trash_can' => 'trash_can'
            //         ],
            //         ['top_paging', 'trash_can']
            //     ),
            //     $this->blueprint->settings(false, null, true, 'Options', true, null, '')
            // )

            ->add(
                $this->blueprint->radio(
                    'trash_can_support', 
                    [], 
                    $sessionData['trash_can_support']
                ),
                $this->blueprint->choices(
                    $this->radioOptions('trash_can_support'), 
                    $sessionData['trash_can_support']
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    null, 
                    true, 
                    null, 
                    ''
                )
            )
            ->add(
                $this->blueprint->radio(
                    'paging_top', 
                    [], 
                    $sessionData['paging_top']
                ),
                $this->blueprint->choices(
                    $this->radioOptions('paging_top'), 
                    $sessionData['paging_top']
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    null, 
                    true, 
                    null, 
                    ''
                )
            )
            ->add(
                $this->blueprint->radio(
                    'paging_bottom', 
                    [], 
                    $sessionData['paging_bottom']
                ),
                $this->blueprint->choices(
                    $this->radioOptions('paging_bottom'), 
                    $sessionData['paging_bottom']
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    null, 
                    true, 
                    null, 
                    ''
                )
            )
            ->add(
                $this->blueprint->radio(
                    'bulk_trash', 
                    [], 
                    $sessionData['bulk_trash']
                ),
                $this->blueprint->choices(
                    $this->radioOptions('bulk_trash'), 
                    $sessionData['bulk_trash']
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    null, 
                    true, 
                    null, 
                    ''
                )
            )
            ->add(
                $this->blueprint->radio(
                    'bulk_clone', 
                    [], 
                    $sessionData['bulk_clone']
                ),
                $this->blueprint->choices(
                    $this->radioOptions('bulk_clone'), 
                    $sessionData['bulk_clone']
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    null, 
                    true, 
                    null, 
                    ''
                )
            )

            ->add(
                $this->blueprint->text(
                    'query',
                    ['uk-form-medium', 'uk-border-bottom', 'uk-form-width-medium', 'uk-form-blank'],
                    $sessionData['query'],
                    false,
                    'Query'
                ),
                null,
                $this->blueprint->settings(false, null, false, 'Query', true, null, 'Your table may or may not support this features. This is a special field within your database table. Which holds multiple value for which you can filter the table results by. This will look like this in your URI <code>?' . $sessionData['query'] . '=active</code> which will return results where the status is active')
            )
            ->add(
                $this->blueprint->text(
                    'filter_alias',
                    ['uk-form-large', 'uk-form-width-medium', 'uk-border-bottom', 'uk-form-blank'],
                    $sessionData['filter_alias'],
                    false,
                    'Filter Alias'
                ),
                null,
                $this->blueprint->settings(
                    false, 
                    null, 
                    false, 
                    'Filter Alias', 
                    true, 
                    null, 
                    'Filter alias is essentially the field name which $_GET query uses to fetch your search result it looks something like this. <code>&lt;input type="search" name="' . $sessionData['filter_alias'] . '" /&gt;. which internally looks like this $_GET[`' . $sessionData['filter_alias'] . '`]</code>'
                )
            )

            ->add($this->blueprint->select(
                'filter_by[]',
                ['uk-select', 'uk-form-width-large', 'uk-panel'],
                'filter_by_column',
                $sessionData['filter_by'],
                true,
                ),
                $this->blueprint->choices(
                    $callingController->repository->getColumns($callingController->schemaAsString()),
                    $sessionData['filter_by'],
                    $this
                ),
                $this->blueprint->settings(false, null, true, 'Filter By', true, null, 'description')
            )
            ->add($this->blueprint->select(
                'sort_columns[]',
                ['uk-select', 'uk-form-width-large', 'uk-panel'],
                'sort_column',
                $sessionData['sort_columns'],
                true,
                ),
                $this->blueprint->choices(
                    $callingController->repository->getColumns($callingController->schemaAsString()),
                    $sessionData['sort_columns'],
                    $this
                ),
                $this->blueprint->settings(
                    false, 
                    null, 
                    true, 
                    'Sort Columns', 
                    true, 
                    null, 
                    sprintf('Choose the columns you want to sort the table data by. Default sortable columns are defined within your controller.yml under [%s] array key', $callingController->thisRouteController())
                )
            )

            ->add($this->blueprint->select(
                'selectors[]',
                ['uk-select', 'uk-form-width-large', 'uk-panel'],
                'selectors',
                $sessionData['selectors'],
                true,
                ),
                $this->blueprint->choices(
                    $callingController->repository->getColumns($callingController->schemaAsString()),
                    $sessionData['selectors'],
                    $this
                ),
                $this->blueprint->settings(false, null, true, 'Selectors', true, '')
            )            
            ->add(
                $this->blueprint->submit(
                    'settings-' . $callingController->thisRouteController() . '',
                    ['uk-button', 'uk-button-primary'],
                    'Update'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )

            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>'], false);
    }
}