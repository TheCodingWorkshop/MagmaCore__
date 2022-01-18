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

abstract class AbstractBlank implements BlankInterface
{

    use BlankTrait;

    /* @var object $driver */
    private object $driver;
    /* @var mixed $optionalParameters */
    private mixed $optionalParameter = null;

    /**
     * @param object $driver
     * @param mixed $optionalParameters
     */
    public function __construct(object $driver, mixed $optionalParameters = null)
    {
        $this->driver = $driver;
        $this->optionalParameter = $optionalParameters;
    }

    /**
     * Returns the optional parameters as a separate method as this may contains already
     * instanitated objects for easy use.
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->optionalParameter;
    }

    /**
     * Get the selected driver object
     * @return object
     */
    public function getDriver(): object
    {
        return $this->driver;
    }

}