<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Alt Three LTD <support@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Exceptions;

use Exception;

/**
 * This is the repository does not exist exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class RepositoryDoesNotExistException extends Exception implements GitExceptionInterface
{
    /**
     * Create a new repository does not exist exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('You need to clone the repository before you attempt to use it.');
    }
}
