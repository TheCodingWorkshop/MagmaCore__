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

namespace MagmaCore\Base;

interface ControllerDomainLogicInterface
{

    /**
     * Return the current repository object for the current controller object to 
     * ensure the data we get back is of the correct entity.
     *
     * @param Object $controller - the current controller object
     * @return Object
     */
    public function repository() : Object;

    /**
     * Returns the current entity object for the current object the method is called in. The 
     * entity object jobs is the sanitize the incoming post data and passed the clean sanitized
     * data to the repository for validation. Each entity is mapped to each datatable table
     *
     * @param Object $controller
     * @param string $entityString
     * @return Object
     */
    public function entity() : Object;
    public function findOr404() : Object;


}