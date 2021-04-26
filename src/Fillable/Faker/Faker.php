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

use MagmaCore\Utility\PasswordEncoder;
use MagmaCore\Fillable\Faker\Generator;
use MagmaCore\Utility\RandomCharGenerator;
use MagmaCore\Fillable\Exception\FillableNoValueException;
use MagmaCore\Fillable\Exception\FillableInvalidArgumentException;

class Faker
{

    /** @var string $_firstname - placeholder variable */
    private string $_firstname;
    /** @var string $_lastname - placeholder variable */
    private string $_lastname;
    /** @var string $_name - placeholder variable */
    private string $_name;
    /** @var string $_email - placeholder variable */
    private string $_email;

    /** @var string $firstname - generate a random firstname string */
    public string $firstname;
    /** @var string $lastname - generate a random lastname string */
    public string $lastname;
    /** @var string $name - generate a random name string */
    public string $name;
    /** @var string $fakePassword - generate a random fake password string */
    public string $fakePassword;
    /** @var string $email - generate a random email string */
    public string $email;
    /** @var string $phoneNumber - generate a random phone number string */
    public mixed $phoneNumber;
    /** @var string $address - generate a random address string */
    public string $address;
    /** @var string $domain - generate a random url domain string */
    public string $domain;
    /** @var string $remoteIP - generate a random ip address string */
    public string $remoteIP;
    /** @var array return an array of support faker badges */
    protected const SUPPORT_BADGE = [
        'firstname',
        'lastname',
        'name',
        'email',
        'status',
        'remoteIP',
        'phoneNumber',
        'fakePassword'
    ];

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->_init();
    }

    /**
     * Generate a random string based on the value passed to the switch
     *
     * @return void
     */
    private function _init(): void
    {
        $this->firstname = $this->string('firstname');
        $this->lastname = $this->string('lastname');
        $this->name = $this->string('name');
        $this->email = $this->string('email');
        $this->fakePassword = $this->fakePassword();
        $this->phoneNumber = $this->phoneNumber();
        $this->remoteIP = $this->string('remoteIP');
    }

    /**
     * Undocumented function
     *
     * @param array $mixer
     * @return integer
     */
    private function randomizer(array $mixer): int
    {
        return rand(0, count($mixer) - 1);
    }

    /**
     * Generate a random string based on the value passed to the switch. Will throw an
     * exception if the faker badge type is not supported
     *
     * @param string $type
     * @return mixed
     * @throws FillableInvalidArgumentException
     * @throws FillableNoValueException
     */
    public function string(string $type): mixed
    {
        if (!in_array($type, self::SUPPORT_BADGE)) {
            throw new FillableInvalidArgumentException('You have pass and invalid faker badge ' . $type . ' Please choose from ' . implode(',', self::SUPPORT_BADGE));
        }

        if ($type) {
            switch ($type) {
                case 'firstname':
                    return $this->_firstname = Generator::FIRSTNAME[$this->randomizer(Generator::FIRSTNAME)];
                    break;
                case 'lastname':
                    return $this->_lastname = Generator::LASTNAME[$this->randomizer(Generator::LASTNAME)];
                    break;
                case 'name':
                    /* 
                        if firstname and lastname is set we can concat both values togther to
                        get a full name string 
                    */
                    if (isset($this->_firstname) && isset($this->_lastname)) {
                        return $this->_name = $this->_firstname . ' ' . $this->_lastname;
                    } else {
                        /*
                            else if firstname and lastname was not set We will generate some
                            new strings for the name property
                        */
                        $this->_name = Generator::FIRSTNAME[$this->randomizer(Generator::FIRSTNAME)];
                        $this->_name .= Generator::LASTNAME[$this->randomizer(Generator::LASTNAME)];
                        return $this->_name;
                    }
                    break;
                case 'email':
                    if (!isset($this->_firstname) && !isset($this->_lastname) || !isset($this->_name)) {
                        $this->_email = Generator::FIRSTNAME[$this->randomizer(Generator::FIRSTNAME)];
                        $this->_email .= Generator::LASTNAME[$this->randomizer(Generator::LASTNAME)];

                        return str_replace(' ', $this->randomDelimiter(Generator::DELIMITER), $this->_email . Generator::EMAIL[rand(0, count(Generator::EMAIL) - 1)]);
                    } else {
                        return str_replace(' ', $this->randomDelimiter(Generator::DELIMITER), $this->_name . Generator::EMAIL[rand(0, count(Generator::EMAIL) - 1)]);
                    }
                    break;
                case 'remoteIP':
                    return mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
                    break;
            }
        }
    }

    /**
     * Generate status when working with creating fillables 
     *
     * @param mixed $value
     * @return string
     */
    public function status(array|string $value = null): mixed
    {
        if (empty($value)) {
            throw new FillableNoValueException('Please specify the argument for this method ' . __METHOD__ . ' You can specify a string value or an array which will be randomize.');
        }
        if (is_string($value) && !is_null($value)) {
            return $value;
        } else {
            if (is_array($value) && count($value) > 0) {
                return ($value) ? $value[$this->randomizer($value)] : Generator::STATUS[$this->randomizer(Generator::STATUS)];
            }
        }
    }

    /**
     * Generate a random fake password. Using the framework build in ramdom character 
     * generator class. Or a string can be pass to the method to generate. There is also
     * an optional flag for hashing the fake password before psersisting to the database
     * this flag can be set to false to insert a raw password. Password are hash by default
     *
     * @param string|null $password
     * @param integer $length
     * @param boolean $hash - defaults to true
     * @return string
     */
    public function fakePassword(string|null $password = null, int $length = 8, bool $hash = true): string
    {
        $pass = ($password !== null) ? $password : RandomCharGenerator::generate($length);
        if ($pass) {
            if ($hash) {
                $result = PasswordEncoder::encode($pass);
            } else {
                $result = $pass;
            }
        }
        return $result;
    }

    /**
     * Undocumented function
     *
     * @param integer $min
     * @param integer $max
     * @return void
     */
    public function phoneNumber(int $min = 7, int $max = 8)
    {
        return Generator::PHONE_NUMBER[$this->randomizer(Generator::PHONE_NUMBER)] . RandomCharGenerator::randomNumberGenerator($min, $max);
    }

    /**
     * Generate a random delimiter which is used when building and email address
     *
     * @return string
     */
    private function randomDelimiter(): string
    {
        return Generator::DELIMITER[$this->randomizer(Generator::DELIMITER)];
    }
}
