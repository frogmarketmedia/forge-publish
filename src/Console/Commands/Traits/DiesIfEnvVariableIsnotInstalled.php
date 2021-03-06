<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait DiesIfEnvVariableIsnotInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait DiesIfEnvVariableIsnotInstalled
{
    /**
     * Skip if env var is not installed.
     */
    protected function dieIfEnvVarIsNotInstalled($env_var)
    {
        if (! $this->fp_env($env_var)) {
            $this->info("No $env_var key found in .env file.");
            $this->info('Please configure this .env variable manually or run php artisan publish:init. Skipping...');
            die();
        }
    }

    /**
     * Get Forge publish env.
     *
     * @param $env_var
     * @return null
     */
    public function fp_env($env_var) {
        return fp_env($env_var);
    }
}
