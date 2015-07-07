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

use StyleCI\Git\Repositories\BasicRepository;
use StyleCI\Git\Repositories\PersistentRepository;

/**
 * This is the repository factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class RepositoryFactory
{
    /**
     * The remote git user.
     *
     * @var string
     */
    protected $user;

    /**
     * Are we using the persistent repository decorator?
     *
     * @var bool
     */
    protected $persistent;

    /**
     * Create a new repository factory instance.
     *
     * @param string $user
     * @param bool   $persistent
     *
     * @return void
     */
    public function __construct($user = 'git@github.com', $persistent = true)
    {
        $this->user = $user;
        $this->persistent = $persistent;
    }

    /**
     * Make a new git repository object.
     *
     * @param string      $name
     * @param string      $path
     * @param string|null $key
     *
     * @return \StyleCI\Git\Repositories\RepositoryInterface
     */
    public function make($name, $path, $key = null)
    {
        $repository = new BasicRepository($name, $this->user, $path, $key);

        if ($this->persistent) {
            $repository = new PersistentRepository($repository);
        }

        return $repository;
    }

    /**
     * Run the garbage collector.
     *
     * This deletes all repos not modified recently.
     *
     * @param string $path
     * @param int    $days
     *
     * @return int
     */
    public function gc($path, $days = 14)
    {
        $collector = new GarbageCollector($path);

        return $collector->collect($days);
    }
}
