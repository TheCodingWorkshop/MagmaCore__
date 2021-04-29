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

namespace MagmaCore\Plugin\Test;

use MagmaCore\Plugin\PluginBuilderInterface;

class MyPlugin implements PluginBuilderInterface
{

    /** @var string - the database table name of the plugin */
    private const TABLESCHEMA = 'plugins';
    /** @var string */
    private const TABLESCHEMAID = 'id';
    /** @var array $services */
    private array $services;

    /**
     * Main plugin constructor method. This methods comes with a single $services 
     * argument which contains an array of object. These object are services
     * which the plugin requested from the plugin bootstrap class
     *
     * @param array $services an array of objects as services requested
     * @returns void
     * @throws PluginInvalidArgumentException
     */
    public function __construct(array $services = [])
    {
        $this->services = $services;
    }

    /**
     * Get the client repository object from the service array
     *
     * @return object|null
     */
    public function getClientRepo(): object|null
    {
        return (isset($this->services[1]) ? $this->services[1] : NULL);
    }

    /**
     * Return the schema name for this plugin
     *
     * @return string
     */
    public function getSchema(): string
    {
        return self::TABLESCHEMA;
    }

    /**
     * Return the schema primary key for this plugin
     *
     * @return string
     */
    public function getSchemaID(): string
    {
        return self::TABLESCHEMAID;
    }

    /**
     * Undocumented function
     *
     * @param object $blueprint
     * @param object $schema
     * @return void
     */
    public function schema(object $blueprint, object $schema): string
    {
        return $schema
            ->schema()
            ->table($this)
            ->row($blueprint->autoID())
            ->row($blueprint->varchar('name', 255))
            ->row($blueprint->longText('description'))
            ->row($blueprint->varchar('author', 255))
            ->row($blueprint->varchar('homepage', 65))
            ->row($blueprint->varchar('version', 4))
            ->build(function ($schema) use ($blueprint) {
                return $schema
                    ->addPrimaryKey($blueprint->getPrimaryKey())
                    ->setUniqueKey(['name'])
                    ->addKeys();
            });
    }

    /**
     * Dolly random quote strings
     *
     * @return string
     */
    private function quotes(): string
    {
        return "Hello, Dolly
        Well, hello, Dolly
        It's so nice to have you back where you belong
        You're lookin' swell, Dolly
        I can tell, Dolly
        You're still glowin', you're still crowin'
        You're still goin' strong
        I feel the room swayin'
        While the band's playin'
        One of our old favorite songs from way back when
        So, take her wrap, fellas
        Dolly, never go away again
        Hello, Dolly
        Well, hello, Dolly
        It's so nice to have you back where you belong
        You're lookin' swell, Dolly
        I can tell, Dolly
        You're still glowin', you're still crowin'
        You're still goin' strong
        I feel the room swayin'
        While the band's playin'
        One of our old favorite songs from way back when
        So, golly, gee, fellas
        Have a little faith in me, fellas
        Dolly, never go away
        Promise, you'll never go away
        Dolly'll never go away again";
    }

    /**
     * Execute the plugin
     *
     * @return string
     */
    public function pluginProcessor()
    {
        $lyrics = explode(', ', $this->quotes());
        $dolly = $lyrics[mt_rand(0, count($lyrics) - 1)];
        $lang = '';
        // if ('en_' !== substr(Locale::getLocale(), 0, 3)) {
        //     $lang = 'en';
        // }
        printf(
            '<p id="dolly"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
            'Quote from Hello Dolly song, by Jerry Herman:',
            $lang,
            $dolly
        );
    }
}
