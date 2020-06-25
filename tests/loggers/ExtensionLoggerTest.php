<?php
namespace tests\loggers;

use extas\components\console\TSnuffConsole;
use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionLogger;
use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryDescription;
use extas\components\Item;
use extas\components\loggers\Logger;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\RepositoryDescription;
use extas\components\repositories\RepositoryDescriptionRepository;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\extensions\IExtensionRepositoryDescription;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use tests\BufferLogger;

/**
 * Class ExtensionLoggerTest
 *
 * @package tests\loggers
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExtensionLoggerTest extends TestCase
{
    use TSnuffConsole;
    use TSnuffRepository;
    use TSnuffPlugins;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->registerSnuffRepos([
            'extensionRepository' => ExtensionRepository::class,
            'repositories' => RepositoryDescriptionRepository::class
        ]);

        $this->createWithSnuffRepo('extensionRepository', new Extension([
            Extension::FIELD__CLASS => ExtensionLogger::class,
            Extension::FIELD__INTERFACE => LoggerInterface::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__METHODS => [
                'emergency', 'alert', 'critical', 'warning', 'error', 'notice', 'info', 'debug', 'log'
            ]
        ]));

        $this->createWithSnuffRepo('extensionRepository', new Extension([
            Extension::FIELD__CLASS => ExtensionRepositoryDescription::class,
            Extension::FIELD__INTERFACE => IExtensionRepositoryDescription::class,
            Extension::FIELD__SUBJECT => '*',
            Extension::FIELD__METHODS => ['loggers']
        ]));

        $this->createWithSnuffRepo('repositories', new RepositoryDescription([
            RepositoryDescription::FIELD__NAME => 'loggers',
            RepositoryDescription::FIELD__SCOPE => 'extas',
            RepositoryDescription::FIELD__PRIMARY_KEY => 'name',
            RepositoryDescription::FIELD__CLASS => Logger::class,
            RepositoryDescription::FIELD__ALIASES => ['loggers']
        ]));
    }

    protected function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testLevels()
    {
        /**
         * @var LoggerInterface $item
         */
        $item = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $levels = [
            'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'
        ];

        foreach ($levels as $level) {
            $item->$level($level);
            $this->assertEquals([$level], BufferLogger::$log[$level]);
        }

        $item->log('emergency', 'log');
        $this->assertEquals(['emergency', 'log'], BufferLogger::$log[$level]);
    }
}
