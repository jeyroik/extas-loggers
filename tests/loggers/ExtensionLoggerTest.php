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
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\THasMagicClass;
use extas\interfaces\extensions\IExtensionLogger;
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
    use TSnuffRepositoryDynamic;
    use TSnuffPlugins;
    use THasMagicClass;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->createSnuffDynamicRepositories([
            ['loggers', 'name', Logger::class]
        ]);

        $this->createWithSnuffRepo('extensionRepository', new Extension([
            Extension::FIELD__CLASS => ExtensionLogger::class,
            Extension::FIELD__INTERFACE => LoggerInterface::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__METHODS => [
                'emergency', 'alert', 'critical', 'warning', 'error', 'notice', 'info', 'debug', 'log'
            ],
            /**
             * Проверка применяется ли фильтр к списку логгеров по параметрам расширения.
             */
            Extension::FIELD__PARAMETERS => [
                'tags' => [
                    'name' => 'tags',
                    'value' => 'test'
                ]
            ]
        ]));

        $this->getMagicClass('loggers')->create(new Logger([
            Logger::FIELD__NAME => 'buffered',
            Logger::FIELD__CLASS => BufferLogger::class,
            Logger::FIELD__TAGS => ['test']
        ]));
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testLevels()
    {
        /**
         * @var IExtensionLogger $item
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
            $item->$level($level, []);
            $this->assertArrayHasKey(
                $level,
                BufferLogger::$log,
                'Missed "' . $level . '": ' . print_r(BufferLogger::$log, true)
            );
            $this->assertEquals([$level], BufferLogger::$log[$level]);
        }

        $item->log('emergency', 'log', []);
        $this->assertEquals(['emergency', 'log'], BufferLogger::$log[$level]);
    }
}
