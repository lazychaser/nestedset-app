<?php namespace Kalnoy;

use Composer\Script\Event;

class ComposerScripts {
    
    /**
     * Run post install commands.
     *
     * @param  Event  $event
     *
     * @return void
     */
    public static function postInstall(Event $event)
    {
        if ($event->isDevMode()) 
        {
            self::generateIdeHelper();
        }
    }

    /**
     * Run post update commands.
     *
     * @param  Event  $event
     *
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        if ($event->isDevMode()) 
        {
            self::generateIdeHelper();

            passthru("php artisan debugbar:publish");
        }
        else
        {
            self::cleanup();
        }

    }

    /**
     * Generate IDE helper file.
     *
     * @return void
     */
    protected static function generateIdeHelper()
    {
        passthru("php artisan ide-helper:generate -M");
    }

    /**
     * Cleanup stuff.
     *
     * @return void
     */
    protected static function cleanup()
    {
        
    }
}