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

namespace MagmaCore\FormBuilder;

interface FormExtensionTypeInterface
{
    /**
     * Options which are defined for this object type
     * Pass the default array to the parent::configureOptions to merge together
     *
     * @param array $extensionOptions
     * @return void
     */
    public function configureOptions(array $extensionOptions = []) : void;

    /**
     * Expose the default options to the public for this object type
     *
     * @return array
     */
    public function getExtensionDefaults() : array;

    /**
     * Publicize the default object options to the base class
     *
     * @return array
     */
    public function getOptions() : array;

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper
     *
     * @return array
     */
    public function getSettings() : array;

}