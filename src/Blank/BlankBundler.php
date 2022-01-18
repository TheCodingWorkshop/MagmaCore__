<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Blank;

use MagmaCore\Bundler\Bundler;
use MagmaCore\Bundler\BundlerInterface;

class BlankBundler extends Bundler implements BundlerInterface
{

    public static function register(): array
    {
        return BlankConfigurations::configurations()['bundler'];
    }

    public static function unregister(): void
    {
        $bundle = BlankConfigurations::configurations()['bundler'];
        unset($bundle);
    }
}