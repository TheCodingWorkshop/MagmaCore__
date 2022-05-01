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

namespace MagmaCore\Administrator\Support;

class SupportRepository
{

    public function changelogData(): array
    {
        $versions = [
            'v_100' => [
                'id' => '1',
                'version' => '1.0.0',
                'created_at' => '27/04/2022',
                'type' => 'release' /** release, bug fix, new features, security patch, deprecation */,
                'icon' => 'checkmark',
                'label' => 'success', /** success, warning, primary, danger, secondary  */
                'desc' => 'New release of Magmacore framework on Github. Please enjoy using this framework to build your next project',
                'lists' => [
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> transform generated shims (in Ivy) with tsickle (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> transform generated shims (in Ivy) with tsickle (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> transform generated shims (in Ivy) with tsickle (#1)',
                    '<span class="uk-text-bolder uk-text-secondary">bezel</span> enable ivy template type-checking in g3 (#1)',

                ]
            ],
            'v_101' => [
                'id' => '2',
                'version' => '1.0.1',
                'created_at' => '27/05/2022',
                'type' => 'bug fix' /** release, bug fix, new features, security patch */,
                'icon' => 'bug',
                'label' => 'warning',
                'desc' => 'New release of Magmacore framework on Github. Please enjoy using this framework to build your next project',
                'lists' => [
                ]
            ],
            'v_102' => [
                'id' => '3',
                'version' => '1.0.2',
                'created_at' => '27/05/2022',
                'type' => 'new features' /** release, bug fix, new features, security patch */,
                'icon' => 'bag-handle',
                'label' => 'primary',
                'desc' => 'New features',
                'lists' => [
                ]
            ],
            'v_103' => [
                'id' => '4',
                'version' => '1.0.3',
                'created_at' => '27/05/2022',
                'type' => 'security patch' /** release, bug fix, new features, security patch */,
                'icon' => 'lock-closed',
                'label' => 'danger',
                'desc' => 'security updates ',
                'lists' => [
                ]
            ],
            'v_104' => [
                'id' => '5',
                'version' => '1.0.4',
                'created_at' => '27/05/2022',
                'type' => 'deprecated' /** release, bug fix, new features, security patch */,
                'icon' => 'trash',
                'label' => 'secondary',
                'desc' => 'function has been deprecated',
                'lists' => [
                ]
            ],
            'v_105' => [
                'id' => '6',
                'version' => '1.0.5',
                'created_at' => '27/05/2022',
                'type' => 'bug fix' /** release, bug fix, new features, security patch */,
                'icon' => 'bug',
                'label' => 'warning',
                'desc' => 'New release of Magmacore framework on Github. Please enjoy using this framework to build your next project',
                'lists' => [
                ]
            ],




        ];

        return $versions;
    }
}
