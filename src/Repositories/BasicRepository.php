<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Alt Three LTD <support@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Repositories;

use Gitonomy\Git\Admin as Git;
use Gitonomy\Git\Repository as GitRepo;
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
     * The gitlib repository instance.
     *
     * @var \Gitonomy\Git\Repository
     */
    protected $repo;

    /**
     * Create a new basic repository instance.
     *
     * @param string                                        $name
     * @param string                                        $user
     * @param string                                        $path
     * @param \Symfony\Component\Filesystem\Filesystem|null $filesystem
     *
     * @return void
     */
    public function __construct($name, $user, $path, Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->location = "$user:$name.git";
        $this->filesystem = $filesystem ?: new Filesystem();
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
     * @throws \Gitonomy\Git\Exception\GitExceptionInterface
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

        $this->repo = Git::cloneTo($this->path, $this->location, false);
    }

    /**
     * Get the gitlib repository instance.
     *
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return \Gitonomy\Git\Repository
     */
    public function repo()
    {
        if ($this->repo) {
            return $this->repo;
        }

        if (!$this->exists()) {
            throw new RepositoryDoesNotExistException();
        }

        return $this->repo = new GitRepo($this->path);
    }

    /**
     * Fetch the latest changes to our repository from the interwebs.
     *
     * @param array $params
     *
     * @throws \Gitonomy\Git\Exception\GitExceptionInterface
     *
     * @return void
     */
    public function fetch(array $params = ['--all'])
    {
        $this->repo()->run('fetch', $params);
    }

    /**
     * Reset our local repository to a specific commit.
     *
     * @param string $commit
     *
     * @throws \Gitonomy\Git\Exception\GitExceptionInterface
     *
     * @return void
     */
    public function reset($commit)
    {
        $this->repo()->run('reset', ['--hard', $commit]);
    }

    /**
     * Get the diff for the uncommitted modifications.
     *
     * @return \Gitonomy\Git\Diff\Diff
     */
    public function diff()
    {
        return $this->repo()->getDiff('HEAD');
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
        $this->repo = null;

        if ($this->exists()) {
            $this->filesystem->remove($this->path);
        }
    }
}
