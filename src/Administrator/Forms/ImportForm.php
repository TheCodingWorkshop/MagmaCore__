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
use MagmaCore\Base\Traits\SessionSettingsTrait;
use MagmaCore\FormBuilder\ClientFormBuilder;
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
        
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "tableForm"])
            ->addRepository($dataRepository)
            ->add(
                $this->blueprint->text(
                    'export_filename',
                    ['uk-form-large', 'uk-form-width-medium', 'uk-border-bottom', 'uk-form-blank'],
                    '', /* how much data to return */
                    false,
                    'Export filename'
                ),
                null,
                $this->blueprint->settings(false, null, true, null, true, null, 'Leaving this field blank will automatically revert the filename set internally. Which is <code>user-data_ follow by the current date.</code>')
            )


            ->add(
                $this->blueprint->submit(
                    'import-' . $callingController->thisRouteController() . '',
                    ['uk-button', 'uk-button-secondary'],
                    'Import'
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true, null, 'If you made any changes please save before exporting.')
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>'], false);
    }
}