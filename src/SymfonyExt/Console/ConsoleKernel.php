<?php

namespace YusamHub\AppExt\SymfonyExt\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
class ConsoleKernel
{
    protected Application $application;

    protected string $rootDir;

    protected array $nameSpaceMap = [
        '/app' => '\\App'
    ];

    /**
     * @param string $rootDir
     * @param array $nameSpaceMap
     */
    public function __construct(string $rootDir = __DIR__, array $nameSpaceMap = [])
    {
        $this->nameSpaceMap = array_merge($this->nameSpaceMap, $nameSpaceMap);
        $this->rootDir = rtrim($rootDir, DIRECTORY_SEPARATOR);
        $this->application = new Application();
    }

    /**
     * @throws \Exception
     */
    function run(): void
    {
        $this->loadCommands(
            $this->rootDir . '/app/Console/Commands'
        );

        exit($this->application->run());
    }

    /**
     * @param array|string $paths
     * @return void
     */
    protected function loadCommands($paths): void
    {
        $paths = array_unique((array) $paths);

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });


        if (empty($paths)) {
            return;
        }

        foreach ((new Finder())->in($paths)->files() as $command) {

            $command = $command->getRealPath() === '' ? '' : array_reverse(explode($this->rootDir, $command->getRealPath(), 2))[0];

            foreach($this->nameSpaceMap as $folder => $namespace) {
                $command = str_replace(
                    [$folder],
                    [$namespace],
                    $command
                );
            }

            $command = str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                $command
                );

            try {
                if (is_subclass_of($command, \Symfony\Component\Console\Command\Command::class) && !(new \ReflectionClass($command))->isAbstract()) {
                    $this->application->add(new $command());
                }
            } catch (\Throwable $e) {

            }
        }
    }
}