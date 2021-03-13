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

use MagmaCore\Collection\Collection;

interface UserProfileInterface
{

    /**
     * Verify the user password before making changes. Ensuring the correct user 
     * is making changes.
     *
     * @param Object $object - the current object to which the method is called from
     * @param int $id - the users ID
     * @param Null|string $fieldName
     * @return bool
     */
    public function verifyPassword(Object $object, int $id, ?string $fieldName = null) : bool;

    /**
     * Update the user first and lastname from their profile accounts page. Users name
     * will be subject to the same validation as registering a new account meaning 
     * users can only use valid and allowed characters
     *
     * @param Collection $entityCollection
     * @param object|null $repository
     * @return array
     */
    public function updateProfileAfterValidation(Collection $entityCollection, Object $repository) : array;

    /**
     * delete the user profile account
     *
     * @param Collection $entityCollection
     * @param object|null $repository
     * @return array
     */
    public function deleteAccountOnceValidated(Collection $entityCollection, ?Object $repository) : array;

    /**
     * Return an array of user profile errors
     *
     * @return array
     */
    public function getProfileErrors(): array;

}