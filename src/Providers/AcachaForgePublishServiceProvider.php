<?php

namespace Acacha\ForgePublish\Providers;

use Acacha\ForgePublish\Commands\PublishConnect;
use Acacha\ForgePublish\Commands\PublishCreateSite;
use Acacha\ForgePublish\Commands\PublishDNS;
use Acacha\ForgePublish\Commands\PublishDomain;
use Acacha\ForgePublish\Commands\PublishEmail;
use Acacha\ForgePublish\Commands\PublishGit;
use Acacha\ForgePublish\Commands\PublishInfo;
use Acacha\ForgePublish\Commands\PublishInit;
use Acacha\ForgePublish\Commands\PublishInstall;
use Acacha\ForgePublish\Commands\PublishIp;
use Acacha\ForgePublish\Commands\PublishLog;
use Acacha\ForgePublish\Commands\PublishLogin;
use Acacha\ForgePublish\Commands\PublishOpen;
use Acacha\ForgePublish\Commands\PublishPush;
use Acacha\ForgePublish\Commands\PublishRc;
use Acacha\ForgePublish\Commands\PublishServer;
use Acacha\ForgePublish\Commands\PublishSite;
use Acacha\ForgePublish\Commands\PublishSsh;
use Acacha\ForgePublish\Commands\PublishToken;
use Illuminate\Support\ServiceProvider;

/**
 * Class AcachaForgePublishServiceProvider.
 */
class AcachaForgePublishServiceProvider extends ServiceProvider
{

    public function register()
    {
        if (!defined('ACACHA_FORGE_PUBLISH_PATH')) {
            define('ACACHA_FORGE_PUBLISH_PATH', realpath(__DIR__.'/../../'));
        }

        $this->mergeConfigFrom(
            ACACHA_FORGE_PUBLISH_PATH.'/config/forge-publish.php', 'forge-publish'
        );
    }

    /**
     * Boot
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishInit::class,
                PublishPush::class,
                PublishLogin::class,
                PublishToken::class,
                PublishEmail::class,
                PublishDomain::class,
                PublishIp::class,
                PublishServer::class,
                PublishSite::class,
                PublishInfo::class,
                PublishCreateSite::class,
                PublishSsh::class,
                PublishRc::class,
                PublishGit::class,
                PublishInstall::class,
                PublishConnect::class,
                PublishDNS::class,
                PublishOpen::class,
                PublishLog::class,
            ]);
        }
        
        $this->publishConfig();
    }

    protected function publishConfig()
    {
        $this->publishes([
            ACACHA_FORGE_PUBLISH_PATH .'/config/forge-publish.php' => config_path('forge-publish.php'),
        ]);
    }

}