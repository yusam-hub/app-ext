<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

class ConsoleKernel
{
    protected Application $application;

    protected string $rootDir;

    protected array $nameSpaceMap = [
        '/app/Console/Commands' => '\\App\\Console\\Commands'
    ];

    protected bool $includePackageCommands;

    /**
     * @param string $rootDir
     * @param array $nameSpaceMap
     * @param bool $includePackageCommands
     */
    public function __construct(string $rootDir = __DIR__, array $nameSpaceMap = [], bool $includePackageCommands = true)
    {
        $this->nameSpaceMap = array_merge($this->nameSpaceMap, $nameSpaceMap);
        $this->rootDir = rtrim($rootDir, DIRECTORY_SEPARATOR);
        $this->includePackageCommands = $includePackageCommands;
        $this->application = new Application();
    }

    /**
     * @throws \Exception
     */
    function run(): void
    {
        $generatedPaths = [];

        if ($this->includePackageCommands) {
            $packageRoot = realpath(__DIR__ . '/../..');
            $folder = str_replace($packageRoot, '', __DIR__) . '/Console/Commands';
            $namespace = '\\YusamHub\\AppExt\\SymfonyExt\\Console\\Commands';
            $this->nameSpaceMap[$folder] = $namespace;
        }

        foreach($this->nameSpaceMap as $folder => $namespace) {
            $generatedPaths[] = $this->rootDir . $folder;
        }

        $this->loadCommands(
            $generatedPaths
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
                    $folder,
                    $namespace,
                    $command
                );
            }

            $class = str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                $command
                );

            try {
                if (is_subclass_of($class, \Symfony\Component\Console\Command\Command::class) && !(new \ReflectionClass($class))->isAbstract()) {
                    $this->application->add(new $class());
                }
            } catch (\Throwable $e) {

            }
        }
    }
}