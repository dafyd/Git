<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Repositories;

use Gitonomy\Git\Repository as GitRepo;
use GitWrapper\GitWrapper;
use StyleCI\Git\Exceptions\RepositoryAlreadyExistsException;
use StyleCI\Git\Exceptions\RepositoryDoesNotExistException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * This is the basic repository class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BasicRepository implements RepositoryInterface
{
    /**
     * The local storage path.
     *
     * @var string
     */
    protected $path;

    /**
     * The remote repository location.
     *
     * @var string
     */
    protected $location;

    /**
     * The symfony filesystem instance.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The git wrapper instance.
     *
     * @var \GitWrapper\GitWrapper
     */
    protected $wrapper;

    /**
     * Create a new basic repository instance.
     *
     * @param string                                        $name
     * @param string                                        $user
     * @param string                                        $path
     * @param string|null                                   $key
     * @param \Symfony\Component\Filesystem\Filesystem|null $filesystem
     * @param \GitWrapper\GitWrapper|null                   $wrapper
     *
     * @return void
     */
    public function __construct($name, $user, $path, $key = null, Filesystem $filesystem = null, GitWrapper $wrapper = null)
    {
        $this->path = $path;
        $this->location = "$user:$name.git";
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->wrapper = $wrapper ?: new GitWrapper();

        if ($key) {
            $this->wrapper->setPrivateKey($key);
        }
    }

    /**
     * Return the repository path on the local filesystem.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Does this repository exist on the local filesystem?
     *
     * @return bool
     */
    public function exists()
    {
        return $this->filesystem->exists($this->path);
    }

    /**
     * Clone the repository to the local filesystem.
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryAlreadyExistsException
     *
     * @return void
     */
    public function get()
    {
        if ($this->exists()) {
            throw new RepositoryAlreadyExistsException();
        }

        $this->filesystem->mkdir($this->path);

        $this->wrapper->clone($this->location, $this->path);
    }

    /**
     * Fetch the latest changes to our repository from the interwebs.
     *
     * @param string|null
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function fetch($name = null)
    {
        if (!$this->exists()) {
            throw new RepositoryDoesNotExistException();
        }

        $git = $this->wrapper->workingCopy($this->path);

        if ($name) {
            $git->fetch('origin', $name);
        } else {
            $git->fetchAll();
        }
    }

    /**
     * Reset our local repository to a specific commit.
     *
     * @param string $commit
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function reset($commit)
    {
        if (!$this->exists()) {
            throw new RepositoryDoesNotExistException();
        }

        $git = $this->wrapper->workingCopy($this->path);

        $git->reset($commit, ['hard' => true]);
    }

    /**
     * Get the diff for the uncommitted modifications.
     *
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return \Gitonomy\Git\Diff\Diff
     */
    public function diff()
    {
        if (!$this->exists()) {
            throw new RepositoryDoesNotExistException();
        }

        $git = new GitRepo($this->path);

        return $git->getDiff('HEAD');
    }

    /**
     * Delete our local git repository from the local filesystem.
     *
     * Only do this if you really don't need it again because cloning it is
     * resource intensive, and can take a long time vs simply fetching the
     * latest changes in the future.
     *
     * @return void
     */
    public function delete()
    {
        if ($this->exists()) {
            $this->filesystem->remove($this->path);
        }
    }
}
