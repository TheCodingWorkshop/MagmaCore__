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

namespace MagmaCore\Auth\Contracts;

interface UserProfileInterface
{

    public function verifyPassword($id);

    /**
     * Update the user profile email
     *
     * @param Object $cleanData
     * @param Object|null $repository
     * @return array
     */
    public function updateProfileNameOnceValidated(Object $cleanData, ?Object $repository) : array;

    /**
     * Update the user profile email
     *
     * @param Object $cleanData
     * @param Object|null $repository
     * @return array
     */
    public function updateProfileEmailOnceValidated(Object $cleanData, ?Object $repository) : array;

    /**
     * Update the user profile password
     *
     * @param Object $clean
     * @param Object|null $repository
     * @return array|bool
     */

    public function updateProfilePasswordOnceValidated(Object $clean, ?Object $repository);

}