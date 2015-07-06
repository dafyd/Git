<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Exceptions\Persistence;

/**
 * This is the resetting repository exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ResettingRepositoryException extends AbstractPersistenceException
{
    /**
     * Create a new resetting repository exception instance.
     *
     * @param \Exception[] $exceptions
     *
     * @return void
     */
    public function __construct(array $exceptions)
    {
        parent::__construct($exceptions, 'Resetting the repository has failed.');
    }
}
