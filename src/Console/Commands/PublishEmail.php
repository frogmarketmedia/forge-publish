<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\PossibleEmails;

/**
 * Class PublishEmail.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishEmail extends SaveEnvVariable
{
    use PossibleEmails;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge email';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_EMAIL';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'email';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge email?';
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default()
    {
        $default = ! empty($emails = $this->getPossibleEmails()) ? $emails[0]: '';
        return fp_env($this->envVar()) ? fp_env($this->envVar()) : $default;
    }

    /**
     * Value.
     */
    protected function value()
    {
        return $this->anticipate($this->questionText(), $this->getPossibleEmails(), $this->default());
    }
}
