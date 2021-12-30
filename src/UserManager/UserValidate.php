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

namespace MagmaCore\UserManager;

use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\DataRepository\AbstractDataRepositoryValidation;
use MagmaCore\Utility\ClientIP;
use MagmaCore\Utility\GravatarGenerator;
use MagmaCore\Utility\HashGenerator;
use MagmaCore\Utility\RandomCharGenerator;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\UtilityTrait;
use MagmaCore\ValidationRule\ValidationRule;
use Exception;

class UserValidate extends AbstractDataRepositoryValidation
{

    use UtilityTrait;

    /** @var array $errors */
    protected array $errors = [];
    /** @var array $dataBag */
    protected array $dataBag = [];
    /** @var ValidationRule $rules */
    protected ValidationRule $rules;
    protected ?string $randomPassword = null;

    /** @var string - empty string will redirect on the same request */
    protected const REDIRECT_BACK_TO = '';

    /**
     * Main class constructor. Uses the ValidateRule class has a dependency
     * We are also declaring the $this->rules->addObject() method which takes two
     * argument. First is a qualified namespace of the controller class which
     * calls this validation class and $this keyword which represents this
     * current object. This way we can actually get access to the controller
     * class throw the ValidationRule object
     *
     * @param ValidationRule $rules
     * @return void
     */
    public function __construct(ValidationRule $rules)
    {
        $this->rules = $rules;
        $this->rules->addObject(UserController::class, $this);
        $this->randomPassword = RandomCharGenerator::generate();
    }

    /**
     * @inheritdoc
     * @param Collection $entityCollection
     * @param object|null $dataRepository - the repository for the entity
     * @return array
     * @throws Exception
     */
    public function validateBeforePersist(Collection $entityCollection, ?object $dataRepository = null): array
    {
        $newCleanData = [];
        $this->validate($entityCollection, $dataRepository);
        $dataCollection = $this->mergeWithFields((array)$entityCollection->all());
        if (null !== $dataCollection) {
            $email = $this->isSet('email', $dataCollection, $dataRepository);
            list($tokenHash, $activationHash) = (new HashGenerator())->hash();
            $newCleanData = [
                'firstname' => $this->isSet('firstname', $dataCollection, $dataRepository),
                'lastname' => $this->isSet('lastname', $dataCollection, $dataRepository),
                'email' => $email,
                'password_hash' => $this->userPassword($dataCollection, 'password_hash', $this->randomPassword),
                'activation_token' => $tokenHash,
                'status' => $this->isSet('status', $dataCollection, $dataRepository) ?? Yaml::file('app')['system']['default_status'],
                'created_byid' => $this->getCreator($dataCollection) ?? 0,
                'gravatar' => GravatarGenerator::setGravatar($email),
                'remote_addr' => ClientIP::getClientIp()
            ];

            /* Settings additional data which will get merge with the dataBag */
            $this->dataBag['activation_hash'] = $activationHash;

            if (array_key_exists('role_id', $dataCollection)) {
                $this->dataBag['role_id'] = intval($dataCollection['role_id']);
            }

            /**
             * When updating we want to unset some key from the $newCleanData array so we
             * are not overwriting key aspects of the user object. ie. We don't wanna mess
             * with the user password. And we don't wanna generate a new activation_token
             * on user update so we will remove these two keys from the array. And !is_null
             * is simple ensuring we have a user object that we are unsetting from.
             */
            if (!is_null($dataRepository)) {
                unset($newCleanData['activation_token'], $newCleanData['password_hash']);
            }

        }
        return [
            $newCleanData,
            $this->validatedDataBag(
                array_merge(
                    $newCleanData,
                    ['random_pass' => $this->randomPassword]
                )
            )
        ];
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function validatedDataBag($newCleanData): array
    {
        return array_merge($newCleanData, $this->dataBag);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getErrors(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function fields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function validationRedirect(): string
    {
        return sprintf('%s', self::REDIRECT_BACK_TO);
    }

    /**
     * @inheritdoc
     * @param Collection $entityCollection
     * @param Object|null $dataRepository
     * @return void
     */
    public function validate(Collection $entityCollection, ?object $dataRepository = null): void
    {

        $this->doValidation(
            $entityCollection,
            $dataRepository,
            function ($key) {
                if ($rules = $this->rules) {
                    return match ($key) {
                        'password_hash', 'client_password_hash' => $rules->addRule("required"),
                        'email' => $rules->addRule("required|email"),
                        'firstname', 'lastname' => $rules->addRule("required"),
                        'status' => $rules->addRule('string'),
                        default => NULL
                    };
                }
            }
        );
    }
}
