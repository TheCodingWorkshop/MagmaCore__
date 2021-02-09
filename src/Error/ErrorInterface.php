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

namespace MagmaCore\Error;

interface ErrorInterface
{

    public function addError($error, array $errorParams = []);
    public function getErrors() : array;
    public function getErrorParams() : array;
    public function dispatchError(Object $object);
    public function hasError(int $errorCode) : bool;
    public function getErrorCode() : string;

}