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

namespace MagmaCore\DataObjectLayer\FileStorageRepository;

use Flatbase\Flatbase;
use Flatbase\Storage\Filesystem;

class FileStorage
{

    /**
     * @return Flatbase
     */
    public function flatDatabase()
    {
        $storage = new Filesystem(STORAGE_PATH . '/files');
        $flatbase = new Flatbase($storage);
        if ($flatbase) {
            return $flatbase;
        }
    }


}