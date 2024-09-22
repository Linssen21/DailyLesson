<?php

declare(strict_types=1);

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\Scanner;
use Illuminate\Support\Facades\Log;
use Socket\Raw\Factory as SocketFactoy;
use Xenolope\Quahog\Client;

/**
 * [Concrete Scanner] Clam AV Scanner
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class ClamAvScanner implements Scanner
{
    private Client $client;

    public function __construct(string $socket = '')
    {
        $this->client = $this->createScannerClient($socket);
    }

    /**
     * Execute file scan for malware/virus
     *
     * @param string $fileName
     * @return boolean
     */
    public function scan(string $fileName): bool
    {
        chmod($fileName, 0644);
        $result = $this->client->scanFile($fileName);

        Log::channel('applog')->info(
            '[Virus Scanner] Scanned file result',
            ['data' => [
                    'id'   => $result->getId(),
                    'filename' => $result->getFilename(),
                    'reason'   => $result->getReason(),
                    'status' => $result->isOk()
                ]
            ]
        );

        if (!$result->isOk()) {
            return false;
        }

        return true;
    }

    /**
     * Create client connection to ClamAv based on the set socket connection
     *
     * @param string $socket
     * @return Client
     */
    private function createScannerClient(string $socket): Client
    {
        if (empty($socket) || config('clamav.preferred_socket') == 'tcp_socket') {
            $socket = config('clamav.tcp_socket');
        } else {
            $socket = config('clamav.unix_socket');
        }

        $timeout = config('clamav.socket_connect_timeout');
        $client = (new SocketFactoy())->createClient($socket, $timeout);

        return new Client($client, $timeout, PHP_NORMAL_READ);
    }
}
