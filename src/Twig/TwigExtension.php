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

namespace MagmaCore\Twig;

use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\Package;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use MagmaCore\Twig\Extensions\FlashMessageExtension;
use MagmaCore\Twig\Extensions\IconNavExtension;
use MagmaCore\Twig\Extensions\NavBarExtension;

use MagmaCore\Auth\Authorized;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Singleton;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Translation\Translation;

use Throwable;
use Exception;
use InvalidArgumentException;

/**
 * Class TwigExtension
 * @package Core\Twig
 */
class TwigExtension extends AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    use SessionTrait;

    public function getFilters(): array
    {
        return [
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('locale', [$this, 'locale']),
            new TwigFunction('varDump', [$this, 'varDump']),
            new TwigFunction('Config', [$this, 'Config']),
            new TwigFunction('assetPath', [$this, 'assetPath']),
            new TwigFunction('flashMessages', [new FlashMessageExtension(), 'flashMessages']),
            new TwigFunction('navMenu', [new NavBarExtension(), 'navMenu']),
            new TwigFunction('iconNav', [new IconNavExtension(), 'iconNav']),

        ];
    }

    /**
     * @return array
     * @throws GlobalManagerException
     * @throws Throwable
     */
    public function getGlobals(): array
    {
        return [
            'current_user' => Authorized::grantedUser(),
            'app' => Yaml::file('app'),
            'asset' => Yaml::file('asset'),
            'items' => Yaml::file('menu')
        ];
    }

    /**
     * Return the relative path for our resources
     *
     * @param string $path
     * @return string
     */
    public function asset($path)
    {
        return (new Package(
            new StaticVersionStrategy(
                'v1', '%s?version=%s')))->getUrl($path);
    }

    public function locale(string $string)
    {
        //return Translation::getInstance()->$string;
        return $string;
    }

    /**
     * @param $var
     * @return bool
     *
     */
    public function varDump($var)
    {
        if (!empty($var)) {
            var_dump($var);
        }
        return false;
    }

    /**
     * @param $file
     * @return mixed
     * @throws Exception
     */
    public function Config($file)
    {
        return Yaml::file($file);
    }

    public function assetPath(string $path)
    {
        return $this->asset(RESOURCES . $path);
    }


    /*=======================================================================================*/
    /* TWIG EXTENSIONS */
    /*=======================================================================================*/

    /**
     * @inheritdoc
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function flashMessages()
    {
        return $this->flashMessages();
    }

    /**
     * @inheritdoc
     * @param array $icons
     * @param array|Object $row
     * @param string $controller
     * @param boolean $vertical
     * @return void
     */
    public function iconNav(array $icons, $row = null, string $controller, bool $vertical = false)
    {
        return (new IconNavExtension())->iconNav(
            $icons,
            $row,
            $controller,
            $vertical
        );
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function navMenu(array $items)
    {
        return (new NavBarExtension())->navMenu($items);
    }


}