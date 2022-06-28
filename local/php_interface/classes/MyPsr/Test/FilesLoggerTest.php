<?php
namespace MyPsr\Test;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use MyPsr\FilesLogge;

class FilesLoggerTest extends TestCase
{

    /** @var string */
    private $logfile;

    /** @var FilesLogger */
    private $filesLogger;

    const TEST_CHANNEL      = 'unittest';

    /**
     * Set up test by instantiating a logger writing to a temporary file.
     */
    public function setUp()
    {

        $this->logfile = tempnam('/tmp', 'SimpleLogUnitTest');

        if (file_exists($this->logfile)) {
            unlink($this->logfile);
        }
        $this->filesLogger = new FilesLogge($this->logfile, self::TEST_CHANNEL);
    }

    /**
     * @testCase Logger implements PSR-3 Psr\Log\LoggerInterface
     */
    public function testLoggerImplementsPRS3Interface()
    {
        $this->assertInstanceOf(\Psr\Log\LoggerInterface::class, $this->logger);
    }

    /**
     * @dataProvider provideLevelsAndMessages
     */
    public function testLogsAtAllLevels($level, $message)
    {

        $this->filesLogger->{$level}($message, array('user' => 'Bob'));
        $this->filesLogger->log($level, $message, array('user' => 'Bob'));

        $expected = array(
            $level.' message of level '.$level.' with context: Bob',
            $level.' message of level '.$level.' with context: Bob',
        );
        $this->assertEquals($expected, $this->getLogs());
    }

    public function dataProvideLevels()
    {
        return array(
            LogLevel::EMERGENCY => array(LogLevel::EMERGENCY, 'message of level emergency with context: {user}'),
            LogLevel::ALERT => array(LogLevel::ALERT, 'message of level alert with context: {user}'),
            LogLevel::CRITICAL => array(LogLevel::CRITICAL, 'message of level critical with context: {user}'),
            LogLevel::ERROR => array(LogLevel::ERROR, 'message of level error with context: {user}'),
            LogLevel::WARNING => array(LogLevel::WARNING, 'message of level warning with context: {user}'),
            LogLevel::NOTICE => array(LogLevel::NOTICE, 'message of level notice with context: {user}'),
            LogLevel::INFO => array(LogLevel::INFO, 'message of level info with context: {user}'),
            LogLevel::DEBUG => array(LogLevel::DEBUG, 'message of level debug with context: {user}'),
        );
    }
}