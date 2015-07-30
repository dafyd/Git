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
     * The git config.
     *
     * @var string[]
     */
    protected $config;

    /**
     * Create a new repository factory instance.
     *
     * @param string[] $config
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        $repository = new Repository($this->config, $path, $key);

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
