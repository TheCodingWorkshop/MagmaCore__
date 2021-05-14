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

namespace MagmaCore\Ash\Components\Uikit;

use MagmaCore\Session\Flash\Flash;

class UikitFlashMessagesExtension
{

    /** @var string */
    public const NAME = 'uikit_flash_message';

    /**
     * Get the session flash messages on the fly.
     *
     * @param object $controller - the current controller object
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function register(object $controller = null)
    {
        $html = '';
        $messages = (new Flash($controller->getSession()))->get();
        if (is_array($messages) && count($messages) > 0) {
            foreach ($messages as $message) {
                extract($message);
                $html .= '<div class="uk-alert-' . (isset($type) ? $type : '') . ' uk-animation-toggle uk-animation-fade" uk-alert tabindex="0">
                        <a class="uk-alert-close" uk-close></a>
                        <p class="uk-text-bolder">' . (isset($message) ? $message : '') . '</p>
                    </div>';
                    
            }
            return $html;
        }
        return false;
    }

}