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
     * Create a new repository factory instance.
     *
     * @param string $user
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Make a new git repository object.
     *
     * @param string      $name
     * @param string      $path
     * @param string|null $key
     *
     * @return \StyleCI\Git\Repository
     */
    public function make($name, $path, $key = null)
    {
        $repository = new Repository($name, $this->user, $path, $key);

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
