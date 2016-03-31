<?php
namespace Gram\Core\Log;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return mixed
     */
    public function log($level, $message, array $context = [])
    {
        // TODO: Implement log() method.
    }

}