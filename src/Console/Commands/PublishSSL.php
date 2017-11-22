<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishSSL.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSSL extends Command
{

    use ChecksEnv;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

    /**
     * Domain
     *
     * @var String
     */
    protected $domain;

    /**
     * Forge site id.
     *
     * @var String
     */
    protected $site;

    /**
     * Token
     *
     * @var String
     */
    protected $token;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:ssl {--server=} {--domain=} {--site=} {--token=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtain lets encrypt certificate';

    /**
     * Guzzle http client.
     *
     * @var Client
     */
    protected $http;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $this->info("Obtaining Lets Encrypt Certificate on production...");

        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_lets_encrypt_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        $url = config('forge-publish.url') . $uri;
        $response = $this->http->post($url,
            [
                'form_params' => [
                    'domains' => [$this->domain]
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]
        );

        $result = json_decode($contents = $response->getBody()->getContents());

        //TODO
        dump($result);

    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->token = $this->checkEnv('token','ACACHA_FORGE_ACCESS_TOKEN');
    }

}