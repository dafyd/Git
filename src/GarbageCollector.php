<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * This is the garbage collector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class GarbageCollector
{
    /**
     * The local storage path.
     *
     * @var string
     */
    protected $path;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new garbage collector instance.
     *
     * @param string                                 $path
     * @param \Illuminate\Filesystem\Filesystem|null $filesystem
     *
     * @return void
     */
    public function __construct($path, Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * Run the garbage collector.
     *
     * This deletes all repos not modified recently.
     *
     * @param int $days
     *
     * @return int
     */
    public function collect($days = 14)
    {
        $finder = new Finder();

        $finder->in($this->path)->directories()->date("< $days days ago");

        $count = 0;

        foreach ($finder as $dir) {
            $count++;
            $this->filesystem->deleteDirectory($dir->getRealPath());
        }

        return $count;
    }
}
