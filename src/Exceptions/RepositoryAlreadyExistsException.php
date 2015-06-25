<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Exceptions;

use Exception;

/**
 * This is the repository already exists exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class RepositoryAlreadyExistsException extends Exception
{
    /**
     * Create a new repository already exists exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('You cannot clone a repository that already exists on the local filesystem.');
    }
}
