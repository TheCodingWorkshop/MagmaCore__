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

namespace MagmaCore\Service\Contracts;

use MagmaCore\Container\ContainerInterface;

interface ServiceProviderInterface extends ContainerInterface
{

    /**
     * Returns an associative array of service types
     * 
     * * ['logger' => 'PSR\Log\LoggerInterface'] - means the object provides a service named logger
     *                                              that implements the LoggerInterface
     * * ['foo' => '?'] - means teh container provides service name "foo" of unspecified type 
     * * ['bar' => '?Bar\Baz'] - means teh container provides service name "foo" of ?Bar\Baz|null 
     *
     * @return array
     */
    public function getProvidedServices() : array;

}