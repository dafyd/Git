<?php

/*
 * This file is part of StyleCI Git.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git;

use Illuminate\Support\ServiceProvider;

/**
 * This is the git service provider class.
 *
 * @author Graham Campbell <graham@cachethq.io>
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
        //
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
            $path = $app['path.storage'].'/repos';
            $user = 'git@github.com';

            return new RepositoryFactory($path, $user);
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
