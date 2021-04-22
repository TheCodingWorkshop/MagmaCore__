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

namespace MagmaCore\Fillable\Faker;

use MagmaCore\Fillable\Faker\Generator;

class Faker
{

    private string|null $capture = null;
    private $name;

    public function __construct()
    { }

    /**
     * Pick a possible firstname from the array list and return it
     *
     * @return string
     */
    public function firstname(): string
    {
        return Generator::FIRSTNAME[rand(0, count(Generator::FIRSTNAME) - 1)];
    }

    /**
     * Pick a possible lastname from the array list and return it
     *
     * @return string
     */
    public function lastname(): string
    {
        return Generator::LASTNAME[rand(0, count(Generator::LASTNAME) - 1)];
    }

    /**
     * Pick a possible firstname from the array list and return it
     *
     * @return string
     */
    public function name(): string
    {
        return $this->firstname() . ' ' . $this->lastname();

    }

    /**
     * Pick a possible firstname from the array list and return it
     *
     * @return string
     */
    public function email(): string
    {
        $email = Generator::EMAIL[rand(0, count(Generator::EMAIL) - 1)];
        if ($email) {
            $domain = str_replace(' ', $this->randomDelimiter(), $this->name() . $email);
            return $domain;
        }

    }

    private function randomDelimiter()
    {
        $array = ['_', '-', '.'];
        return $array[rand(0, count($array) - 1)];

    }


}
