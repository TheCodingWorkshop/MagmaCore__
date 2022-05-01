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

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Administrator\Support\SupportCommander;

class SupportController extends \MagmaCore\Administrator\Controller\AdminController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'commander' => SupportCommander::class,
                'repository' => SupportModel::class,
                'repo' => SupportRepository::class,

            ]
        );

    }

    protected function documentationAction()
    {
        $this->render('/admin/support/documentation.html', []);
    }

    protected function changelogAction()
    {
        $this->render(
            '/admin/support/changelog.html', 
            [
                'changelogs' => $this->repo->changelogData()
            ]
        );
    }


}

