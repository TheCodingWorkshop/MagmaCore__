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

namespace MagmaCore\Administrator\Forms;

use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\FormBuilder\ClientFormBuilder;
use MagmaCore\Base\Traits\SessionSettingsTrait;
use MagmaCore\FormBuilder\FormBuilderBlueprint;
use MagmaCore\FormBuilder\ClientFormBuilderInterface;
use MagmaCore\FormBuilder\FormBuilderBlueprintInterface;

class ImportForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    use SessionSettingsTrait;

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
                'csv' => 'true',
                'xml' => 'false'
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
        
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "tableForm", "enctype" => "multipart/form-data"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->upload('data_import', [], '', true),
                '<span class="ion-28">' . IconLibrary::getIcon('upload', 3.5) . '</span>',
                $this->blueprint->settings(false, null, false, null, true, null, 'Supported file types .csc .xml .xmls')
            )


            ->add(
                $this->blueprint->submit(
                    'import-' . $callingController->thisRouteController() . '',
                    ['uk-button', 'uk-button-secondary'],
                    'Choose a file to import'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, null, '')
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}