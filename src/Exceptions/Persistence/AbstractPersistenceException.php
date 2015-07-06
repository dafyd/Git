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

use Exception;
use StyleCI\Git\Exceptions\GitExceptionInterface;

/**
 * This is the abstract persistence exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractPersistenceException extends Exception implements GitExceptionInterface
{
    /**
     * The array of caught exceptions.
     *
     * @var \Exception[]
     */
    protected $exceptions;

    /**
     * Create a new persistence exception instance.
     *
     * @param \Exception[] $exceptions
     * @param string       $message
     *
     * @return void
     */
    public function __construct(array $exceptions, $message)
    {
        $this->exceptions = $exceptions;

        parent::__construct($message);
    }

    /**
     * Get the array of caught exceptions.
     *
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
