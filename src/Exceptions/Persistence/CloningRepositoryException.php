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
 * This is the cloning repository exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CloningRepositoryException extends AbstractPersistenceException
{
    /**
     * Create a new cloning repository exception instance.
     *
     * @param \Exception[] $exceptions
     *
     * @return void
     */
    public function __construct(array $exceptions)
    {
        parent::__construct($exceptions, 'Cloning the repository has failed.');
    }
}
