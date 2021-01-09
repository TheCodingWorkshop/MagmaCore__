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
use MagmaCore\Session\Flash\Flash;

use MagmaCore\Auth\Authorized;
use MagmaCore\Utility\Yaml;
use MagmaCore\Session\SessionTrait;

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
            //new TwigFunction('locale', [$this, 'locale']),
            new TwigFunction('varDump', [$this, 'varDump']),
            new TwigFunction('Config', [$this, 'Config']),
            new TwigFunction('flashMessages', [$this, 'flashMessages']),

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
       /* return (new Package(
            new StaticVersionStrategy(
                'v1', '%s?version=%s')))->getUrl($path);*/
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

    /**
     * Get the session flash messages on the fly.
     *
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function flashMessages()
    {
        $html = '';
        $messages = (new Flash(SessionTrait::sessionFromGlobal()))->get();
        if (is_array($messages) && count($messages) > 0) {
            foreach ($messages as $message) {
                extract($message);
                $html .= '<div class="uk-alert-' . (isset($type) ? $type : '') . ' uk-animation-toggle uk-animation-shake fade-alert" uk-alert tabindex="0">
                        <a class="uk-alert-close" uk-close></a>
                        <p class="uk-text-bolder">' . (isset($message) ? $message : '') . '</p>
                    </div>';
            }
            return $html;
        }
        return false;
    }

}