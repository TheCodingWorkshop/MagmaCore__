<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\CommanderBar;

trait CommanderUnsetterTrait
{
    /**
     * @return array
     */
    public function unsetNotification(): array
    {
        return $this->noNotification;
    }

    /**
     * @return array
     */
    public function unsetManager(): array
    {
        return $this->noManager;
    }

    /**
     * @return array
     */
    public function unsetCustomizer(): array
    {
        return $this->noCustomizer;
    }

    /**
     * @return array
     */
    public function unsetAction(): array
    {
        return $this->noAction;
    }

    /**
     * @return array
     */
    public function unsetFilter(): array
    {
        return $this->noFilter;
    }

}