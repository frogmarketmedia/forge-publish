<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksForRootPermission;
use Acacha\ForgePublish\Commands\Traits\DNSisAlreadyConfigured;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishDNS.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDNS extends Command
{
    use ChecksForRootPermission, DNSisAlreadyConfigured, ChecksEnv;

    /**
     * The ip address.
     *
     * @var string
     */
    protected $ip;

    /**
     * Is DNS already resolved.
     *
     * @var string
     */
    protected $dnsAlreadyResolved = false;

    /**
     * The domain name.
     *
     * @var string
     */
    protected $domain;

    /**
     * Constant to /etc/hosts file
     */
    const ETC_HOSTS = '/etc/hosts';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:dns {ip?} {domain?} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check DNS configuration';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->info('Checking DNS configuration');
        $this->abortCommandExecution();

        if ($this->dnsAlreadyResolved) {
            $this->info("DNS resolution is ok. ");
            return;
        }

        $this->info("DNS resolution is not correct. Let me help you configure it...");

        $type = $this->option('type') ?
            $this->option('type') :
            $this->choice('Which system do you want to use?', ['hosts'], 0);

        if ($type != 'hosts') {
            //TODO Support Other services OpenDNS/Hover.com? DNS service wiht API
            // https://laracasts.com/series/server-management-with-forge/episodes/8
            $this->error('Type not supported');
            die();
        }

        $this->addEntryToEtcHostsFile($this->domain, $this->ip);
        $this->info('File ' . self::ETC_HOSTS . ' configured ok');
    }

    /**
     * Add entry to etc/hosts file.
     *
     * @param $domain
     * @param $ip
     */
    protected function addEntryToEtcHostsFile($domain, $ip)
    {
        $content = "\n# Forge server\n$ip $domain\n";
        File::append(self::ETC_HOSTS, $content);
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->domain = $this->checkEnv('domain', 'ACACHA_FORGE_DOMAIN', 'argument');
        $this->ip = $this->checkEnv('ip', 'ACACHA_FORGE_IP_ADDRESS', 'argument');

        if ($this->dnsResolutionIsOk()) {
            return ;
        }

        $this->checkForRootPermission();
    }
}
