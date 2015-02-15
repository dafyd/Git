<?php

/*
 * This file is part of StyleCI Git.
 *
 * (c) Graham Campbell <graham@mineuk.com>
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
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepositoryFactory
{
    /**
     * The local storage path.
     *
     * @var string
     */
    protected $path;

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
     * @param string $path
     * @param string $user
     * @param bool   $persistent
     *
     * @return void
     */
    public function __construct($path, $user, $persistent = true)
    {
        $this->path = $path;
        $this->user = $user;
        $this->persistent = $persistent;
    }

    /**
     * Get the a git repository object.
     *
     * @param string $repo
     *
     * @return \StyleCI\Git\Repositories\RepositoryInterface
     */
    public function make($repo)
    {
        $repository = new BasicRepository($repo, $this->user, $this->path);

        if (!$this->persistent) {
            return $repository;
        }

        return new PersistentRepository($repository);
    }
}