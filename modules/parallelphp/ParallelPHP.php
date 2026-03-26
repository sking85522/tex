<?php

namespace ParallelPHP;

use ParallelPHP\Core\Pool;

class ParallelPHP
{
    /**
     * Create a new process pool for parallel execution.
     *
     * @param int $concurrency Number of parallel workers (default is 4).
     * @return Pool
     */
    public static function Pool(int $concurrency = 4): Pool
    {
        return new Pool($concurrency);
    }
}
