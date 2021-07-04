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

use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

trait ModelCastingTrait
{

    /**
     * two way casting cast a data type back and fourth
     *
     * @param array $casters
     * @return void
     */
    public function casting(array $casters = [])
    {
        if (isset($this->cast)) {
            if (is_array($this->cast) && count($this->cast) > 0) {
                foreach ($this->cast as $key => $value) {
                    if (!in_array($value, $casters)) {
                        throw new BaseInvalidArgumentException($value . ' casting type is not supported.');
                    }
                    $this->resolveCast($key, $value);
                }
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws BaseException
     */
    private function resolveCast(string $key, mixed $value)
    {
        if (empty($key)) {
            throw new BaseException('');
        }
        switch ($value) {
            case 'array_json':
                if (isset($this->getEntity()->$key) && $this->getEntity()->$key !== '') {
                    $this->getEntity()->$key = json_encode($value);
                }
                break;
        }
    }
}
