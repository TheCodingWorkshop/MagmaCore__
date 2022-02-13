<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Base\Traits;

use MagmaCore\Utility\Serializer;

trait ControllerSessionTrait
{


    public function getSessionData(?string $sessionKey = null, ?object $controller = null): array|bool
    {
        $data = $controller->getSession()->get($sessionKey);
        $uncompressSession = Serializer::unCompress($data);
        if (is_array($uncompressSession) && count($uncompressSession) > 0) {
            return $uncompressSession;
        }

        return false;
    }

    public function resolveAdditionalConditions(string $key = null, object $controller = null)
    {
        return $this->getSessionData($key, $controller)['additional_conditions'];
    }

}
