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

use Gitonomy\Git\Repository as GitRepo;
use Gitonomy\Git\RevisionList;
use GitWrapper\GitWrapper;
use StyleCI\Git\Exceptions\RepositoryAlreadyExistsException;
use StyleCI\Git\Exceptions\RepositoryDoesNotExistException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * This is the repository class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Repository
{
    /**
     * The git config.
     *
     * @var string[]
     */
    protected $config;

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
     * Create a new repository instance.
     *
     * @param string[]                                      $config
     * @param string                                        $name
     * @param string                                        $path
     * @param string|null                                   $key
     * @param \Symfony\Component\Filesystem\Filesystem|null $filesystem
     * @param \GitWrapper\GitWrapper|null                   $wrapper
     *
     * @return void
     */
    public function __construct(array $config, $name, $path, $key = null, Filesystem $filesystem = null, GitWrapper $wrapper = null)
    {
        $this->config = $config;
        $this->path = $path;
        $this->location = $config['remote'].':'.$name.'.git';
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->wrapper = $wrapper ?: new GitWrapper();

        if ($key) {
            $path = "{$path}-key";
            file_put_contents($path, $key);
            chmod($path, 0600);
            $this->wrapper->setPrivateKey($path);
        }
    }

    /**
     * Destroy a repository instance.
     *
     * @return void
     */
    public function __destruct()
    {
        @unlink("{$this->path}-key");
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
     * Check if the repo exists on the local filesystem.
     *
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    protected function guard()
    {
        if (!$this->exists()) {
            throw new RepositoryDoesNotExistException();
        }
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
        $this->guard();

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
     * @throws \Gitonomy\Git\Exception\GitExceptionInterface
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function reset($commit)
    {
        $this->guard();

        $git = new GitRepo($this->path);

        $git->run('reset', ['--hard', $commit]);
    }

    /**
     * Get the diff for the uncommitted local modifications.
     *
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return string
     */
    public function diff()
    {
        $this->guard();

        $git = new GitRepo($this->path);

        $revisions = new RevisionList($git, 'HEAD');

        $args = array_merge(['-r', '-p', '-m', '-M', '--no-commit-id', '--full-index', '--binary'], $revisions->getAsTextArray());

        return $this->run('diff', $args);
    }

    /**
     * Checkout a new branch on the local repository.
     *
     * @param string $branch
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function checkout($branch)
    {
        $this->guard();

        $this->wrapper->workingCopy($this->path)->checkoutNewBranch($branch);
    }

    /**
     * Apply a diff to the local repository.
     *
     * @param string $diff
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function apply($diff)
    {
        $this->guard();

        $file = $this->path.'/../styleci-git.diff';

        file_put_contents($file, $diff);

        $this->wrapper->workingCopy($this->path)->apply($file, ['whitespace' => 'nowarn', '3way' => true]);

        unlink($file);
    }

    /**
     * Commit all changes on the local repository.
     *
     * @param string      $message
     * @param string|null $author
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function commit($message, $author = null)
    {
        $this->guard();

        $git = $this->wrapper->workingCopy($this->path);

        $git->config('user.name', $this->config['name']);
        $git->config('user.email', $this->config['email']);

        $args = ['m' => $message, 'a' => true];

        if ($author) {
            $args['author'] = $author;
        }

        $git->commit($args);
    }

    /**
     * Publish a branch to the remote repository.
     *
     * @param string $branch
     *
     * @throws \GitWrapper\GitException
     * @throws \StyleCI\Git\Exceptions\RepositoryDoesNotExistException
     *
     * @return void
     */
    public function publish($branch)
    {
        $this->guard();

        $this->wrapper->workingCopy($this->path)->push('origin', $branch);
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
