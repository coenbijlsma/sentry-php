<?php
namespace Sentry;

interface IPluginHandler {

    /**
     * @param Plugin $plugin
     * @return bool
     */ 
    public function attachPluginCommands( Plugin $plugin );
}