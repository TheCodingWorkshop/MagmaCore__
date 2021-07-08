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

namespace MagmaCore\Ash;

use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Convert;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Ash\Traits\TemplateTraits;
use MagmaCore\Ash\Components\Uikit\UikitNavigationExtension;
use MagmaCore\Ash\Components\Uikit\UikitPaginationExtension;
use MagmaCore\Ash\Components\Bootstrap\BsNavigationExtension;
use MagmaCore\Ash\Components\Uikit\UikitCommanderBarExtension;
use MagmaCore\Ash\Exception\TemplateLocaleOutOfBoundException;
use MagmaCore\Ash\Components\Uikit\UikitFlashMessagesExtension;

class TemplateExtension
{

    /** @var TemplateTraits - holds common function used across template extensions */
    use TemplateTraits;

    /** @var array */
    protected mixed $js = null;
    /** @var array */
    protected mixed $css = null;
    /** @var string */
    protected string $string;

    private array $ext = [];
    private array $extensions;
    private object $controller;

    /**
     * Return an array of all the template extension class with the const extension 
     * name as the key which represent the extension logic
     *
     * @return void
     */
    public function __construct(object $controller)
    {
        $this->controller = $controller;
        $this->extensions = [

            UikitNavigationExtension::NAME => UikitNavigationExtension::class,
            UikitPaginationExtension::NAME => UikitPaginationExtension::class,
            UikitCommanderBarExtension::NAME => UikitCommanderBarExtension::class,
            UikitFlashMessagesExtension::NAME => UikitFlashMessagesExtension::class,
            BsNavigationExtension::NAME => BsNavigationExtension::class

        ];
    }

    /**
     * Return a formatted human readable date format
     *
     * @param mixed $time
     * @param boolean $short
     * @return string
     */
    public function formatDate(mixed $time, bool $short = false): string
    {
        return Convert::timeFormat($time, $short);
    }

    /**
     * Construct the user define path. Path must be specified based on the its 
     * controller ie. to access the index action within the dashboard controller this 
     * path must be declared as admin_dashboard_index which will simple return 
     * /admin/dashboard/index. An exception will be thrown if the current path doesn't 
     * match the current namespace controller and action 
     *
     * @param string $path
     * @param mixed $token
     * @return string
     */
    public function path(string $path, mixed $token = null): string
    {
        $string = explode('_', $path);
        $sep = '/';

        // if (isset($string[1]) && $string[1] === $this->controller->thisRouteController()) {
        //     if (isset($string[0]) && $string[0] !== $this->controller->thisRouteNamespace()) {
        //         throw new LoaderError('Invalid path namespace');
        //     }
        //     if (isset($string[1]) && $string[1] !== $this->controller->thisRouteController()) {
        //         throw new LoaderError('Invalid path controller');
        //     }
        //     if (isset($string[2]) && $string[2] !== $this->controller->thisRouteAction()) {
        //         throw new LoaderError('Invalid path action');
        //     }

        // }
        if ($token !==null) {
            $newArray = [$string[0], $string[1], $token, $string[2]];
            return $sep . implode('/', array_replace($string, $newArray));
        } else {
            return $sep . implode('/', $string);
        }
    }

    public function getRoleFromID(int $userID)
    {
        $role = PrivilegedUser::getUser($userID);
        return $role->getRole();
    }

    /**
     * Undocumented function
     *
     * @param string $string
     * @return string
     * @throws \Exception
     */
    public function locale(string $string): string
    {
        if (is_string($string)) {
            $locale = Yaml::file('locale')['en'];
            if (!in_array($string, array_keys($locale))) {
                throw new TemplateLocaleOutOfBoundException($string . ' is an invalid translation string.');
            }

            return $locale[$string];
        }
    }

    /**
     * Expose framework database configuration options to the template
     *
     * @param string $name
     * @return mixed
     */
    public function config(string $name): mixed
    {
        if (isset($this->controller->settingsRepository)) {
            return $this->controller->settingsRepository->get($name);
        }
    }

    /**
     * Undocumented function
     *
     * @param mixed $key
     * @param mixed $array
     * @return mixed
     */
    public function isSet(mixed $key, mixed $array): mixed
    {
        return array_key_exists($key, $array) ? (is_array($array) ? $array[$key] : $array->$key) : '';
    }

    /**
     * Return a registered extension
     *
     * @param string|null $extensionName
     * @param string|null $header
     * @param string|null $headerIcon
     * @return void
     */
    public function templateExtension(string|null $extensionName, ?string $header = null, ?string $headerIcon = null): mixed
    {
        if (count($this->extensions) > 0) {
            if (in_array($extensionName, array_keys($this->extensions))) {
                foreach ($this->extensions as $name => $extension) {
                    if ($extensionName === $name) {
                        $ext = BaseApplication::diGet($extension);
                        if ($ext) {
                            return call_user_func_array([$ext, 'register'], [$this->controller, $header, $headerIcon]);
                        }
                    }
                }
            }
        }
    }
}
