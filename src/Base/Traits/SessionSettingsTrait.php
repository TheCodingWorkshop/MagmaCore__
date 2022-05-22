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

namespace MagmaCore\Base\Traits;

use MagmaCore\Utility\Serializer;

trait SessionSettingsTrait
{

    public function createSessionSettings(object $controller = null, string $key = null, mixed $data = null): void
    {
        $session = $controller->getSession();
        if (!$session->has($key)) {
            $session->set($key, Serializer::compress($data));
        }      
    }

    public function getSessionSettings(object $controller = null, string $key = null): mixed
    {
        $session = $controller->getSession();
        if ($session->has($key)) {
            $data = Serializer::unCompress($session->get($key));
        }      

        return $data;
    }

    public function flushSessionSettings(object $controller = null, string $key = null, mixed $data = null): void
    {
        $session = $controller->getSession();
        $session->delete($key);
        $session->set($key, Serializer::compress($data));
    }


}

