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

namespace MagmaCore\Twig\Extensions;

use MagmaCore\Session\SessionTrait;
use MagmaCore\Session\Flash\Flash;

class FlashMessageExtension
{

    /**
     * Get the session flash messages on the fly.
     *
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function flashMessages()
    {
        $html = '';
        $messages = (new Flash(SessionTrait::sessionFromGlobal()))->get();
        if (is_array($messages) && count($messages) > 0) {
            foreach ($messages as $message) {
                extract($message);
                $html .= '<div class="uk-alert-' . (isset($type) ? $type : '') . ' uk-animation-toggle uk-animation-shake fade-alert" uk-alert tabindex="0">
                        <a class="uk-alert-close" uk-close></a>
                        <p class="uk-text-bolder">' . (isset($message) ? $message : '') . '</p>
                    </div>';
                    
            }
            return $html;
        }
        return false;
    }

}