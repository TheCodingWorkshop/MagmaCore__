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
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\DateFormatter;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Ash\Traits\TemplateTraits;
use MagmaCore\Ash\Components\Uikit\UikitNavigationExtension;
use MagmaCore\Ash\Components\Uikit\UikitPaginationExtension;
use MagmaCore\Ash\Components\Uikit\UikitSimplePaginationExtension;
use MagmaCore\Ash\Components\Bootstrap\BsNavigationExtension;
use MagmaCore\Ash\Components\Uikit\UikitCommanderBarExtension;
use MagmaCore\Ash\Exception\TemplateLocaleOutOfBoundException;
use MagmaCore\Ash\Components\Uikit\UikitFlashMessagesExtension;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Setting\SettingModel;
use RuntimeException;

if (!class_exists(PermisisonModel::class) && !class_exists(UserModel::class)) {
    throw new RuntimeException('You are application is missing Permisson and User Models.');
}

class TemplateExtension
{

    /** @var TemplateTraits - holds common function used across template extensions */
    use TemplateTraits;
    use DataLayerTrait;
    use TemplateFunctionsTrait;

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
            UikitSimplePaginationExtension::NAME => UikitSimplePaginationExtension::class,
            UikitCommanderBarExtension::NAME => UikitCommanderBarExtension::class,
            UikitFlashMessagesExtension::NAME => UikitFlashMessagesExtension::class,
            //BsNavigationExtension::NAME => BsNavigationExtension::class

        ];
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