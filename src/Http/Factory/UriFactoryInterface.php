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

namespace MagmaCore\Http\Factory;

use MagmaCore\Http\Message\UriInterface;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

interface UriFactoryInterface
{
    /**
     * Create a new URI.
     *
     * @param string $uri The URI to parse.
     *
     * @throws BaseInvalidArgumentException If the given URI cannot be parsed.
     */
    public function createUri(string $uri = '') : UriInterface;
}