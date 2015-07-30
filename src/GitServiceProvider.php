<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git;

use Illuminate\Support\ServiceProvider;

/**
 * This is the git service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class GitServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/git.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('git.php')]);
        }

        $this->mergeConfigFrom($source, 'git');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositoryFactory();
    }

    /**
     * Register the repository factory class.
     *
     * @return void
     */
    protected function registerRepositoryFactory()
    {
        $this->app->singleton('git.factory', function ($app) {
            $config = $app->config->get('git');

            return new RepositoryFactory($config);
        });

        $this->app->alias('git.factory', RepositoryFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'git.factory',
        ];
    }
}
