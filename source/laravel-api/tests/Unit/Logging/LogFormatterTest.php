<?php

declare(strict_types=1);

namespace Tests\Logging\Unit;

use App\Logging\LogFormatter;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * Testing Log Formatter Class
 *
 * Unit test for Log Formatter
 *
 * @ticket Feature/DL-1
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class LogFormatterTest extends TestCase
{
    private LogFormatter $formatter;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->formatter = new LogFormatter();
    }

    /**
     * Test log formatter method
     *
     * @return void
     */
    public function testSetLogFormatter(): void
    {
        // Arrange
        $this->formatter->setLogFormat(['[%datetime%]', '[%remote_addr%]', ' %message%']);

        // Act
        $expectedFormat = "[%datetime%][%remote_addr%] %message%" . PHP_EOL;
        $actualFormat = $this->formatter->getLogFormat();

        // Assert
        $this->assertSame($expectedFormat, $actualFormat);
    }

    /**
     * Test setting up remote address
     *
     * @return void
     */
    public function testSetRemoteAddr(): void
    {
        // Arrange
        $request = new Request();
        $request->server->set('REMOTE_ADDR', '192.168.1.1');

        // Act
        $this->formatter->setRemoteAddr($request);
        $actualRemoteAddr = $this->formatter->getRemoteAddr();

        // Assert
        $this->assertEquals('192.168.1.1', $actualRemoteAddr);
    }
}
