<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Illuminate\Http\Request;

/**
 * Log Formatter Class
 *
 * this is a custom log format for this app
 *
 * @ticket Feature/DL-1
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class LogFormatter
{
    /**
     * The Log format string
     *
     * @var string
     */
    private string $strLogFormat;

    /**
     * The client or visitor's ip or remote address
     *
     * @var string
     */
    private string $strRemoteAddr;

    /**
     * Setter method for the Log format property
     * Concatinate columns to a single string
     *
     * @ticket Feature/DL-1
     * @param array $aryColumns List of columns to be added in the format string
     * @return void
     */
    public function setLogFormat(array $aryColumns): void
    {
        $strLogFormat = implode("", $aryColumns) . PHP_EOL;
        $this->strLogFormat = $strLogFormat;
    }

    /**
     * Getter method for the Log Format property
     *
     * @ticket Feature/DL-1
     * @return string
     */
    public function getLogFormat(): string
    {
        return $this->strLogFormat;
    }

    /**
     * Set remote address of visitor check if request is available
     * request available if true: return ip. if false: return unknown
     *
     * @return void
     */
    public function setRemoteAddr(): void
    {
        $request = app(Request::class);
        $this->strRemoteAddr = $request ? $request->ip() : "unknown";
    }

    /**
     * Get the remote address or ip
     *
     * @return string
     */
    public function getRemoteAddr(): string
    {
        return $this->strRemoteAddr;
    }

    public function __invoke(Logger $logger): void
    {
        $this->setRemoteAddr();

        $aryCols = [
            "[%datetime%]",
            "[". $this->getRemoteAddr() ."]",
            " %channel%.%level_name%:",
            "%message%",
            " %context%",
            " %extra%"
        ];

        $this->setLogFormat($aryCols);

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                $this->getLogFormat(),
                null,
                true,
                true
            ));
        }
    }
}
