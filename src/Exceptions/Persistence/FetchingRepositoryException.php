<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Git\Exceptions\Persistence;

/**
 * This is the fetching repository exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class FetchingRepositoryException extends AbstractPersistenceException
{
    /**
     * Create a new fetching repository exception instance.
     *
     * @param \Exception[] $exceptions
     *
     * @return void
     */
    public function __construct(array $exceptions)
    {
        parent::__construct($exceptions, 'Fetching the remote changes to the repository has failed.');
    }
}
