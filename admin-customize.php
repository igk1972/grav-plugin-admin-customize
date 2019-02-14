<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

class AdminCustomizePlugin extends Plugin
{

    /**
     * Initialize plugin and subsequent events
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onGetPageBlueprints'  => ['onGetPageBlueprints', 0],
            'onGetPageTemplates'   => ['onGetPageTemplates', 0],
        ];
    }

    /**
     * Register events with Grav
     * @return void
     */
    public function onPluginsInitialized()
    {
        /* Check if Admin-interface */
        if (!$this->isAdmin()) {
            return;
        }

        require_once __DIR__ . '/vendor/autoload.php';

        $this->enable([
            'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0]
        ]);
    }

    /**
     * Register template overrides
     * @param RocketTheme\Toolbox\Event\Event $event
     * @return void
     */
    public function onAdminTwigTemplatePaths($event)
    {
        $event['paths'] = array_merge($event['paths'], [__DIR__ . '/admin/themes/grav/templates']);
        $event['paths'] = array_merge($event['paths'], Grav::instance()['locator']->findResources('user://templates/pages'));
        //$this->grav['twig']->twig_paths[] = __DIR__ . '/admin/themes/grav/templates';
    }

    /**
     * Add page blueprints
     *
     * @param Event $event
     */
    public function onGetPageBlueprints(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->scanBlueprints('user://blueprints/pages/');
    }

    /**
     * Add page template types.
     *
     * @param Event $event
     */
    public function onGetPageTemplates(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->scanTemplates('user://templates/pages/');
    }

}
