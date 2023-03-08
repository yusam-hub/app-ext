<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

class ConsoleKernel
{
    public static bool $isDebugging = false;

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
        $this->rootDir = realpath(rtrim($rootDir, DIRECTORY_SEPARATOR));
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
            $replacedFolder = str_replace($this->rootDir, '', $packageRoot) . '/src/SymfonyExt/Console/Commands';
            $namespace = '\\YusamHub\\AppExt\\SymfonyExt\\Console\\Commands';

            if (self::$isDebugging) {
                print_r([
                    'rootDir' => $this->rootDir,
                    'packageRoot' => $packageRoot,
                    'replacedFolder' => $replacedFolder,
                    'namespace' => $namespace,
                ]);
            }

            $this->nameSpaceMap[$replacedFolder] = $namespace;
        }

        if (self::$isDebugging) {
            print_r([
                'nameSpaceMap' => $this->nameSpaceMap
            ]);
        }

        foreach($this->nameSpaceMap as $folder => $namespace) {
            $generatedPaths[] = $this->rootDir . $folder;
        }

        if (self::$isDebugging) {
            print_r([
                'generatedPaths' => $generatedPaths
            ]);
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

        foreach ((new Finder())->in($paths)->files() as $finder) {

            $foundFile = $finder->getRealPath() === '' ? '' : array_reverse(explode($this->rootDir, $finder->getRealPath(), 2))[0];

            if (self::$isDebugging) {
                print_r([
                    'foundFile' => $foundFile
                ]);
            }

            foreach($this->nameSpaceMap as $folder => $namespace) {
                $foundFile = str_replace(
                    $folder,
                    $namespace,
                    $foundFile
                );
            }

            if (self::$isDebugging) {
                print_r([
                    'mappedFoundFile' => $foundFile
                ]);
            }

            $class = str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                $foundFile
                );

            if (self::$isDebugging) {
                print_r([
                    'class' => $class
                ]);
            }

            try {
                if (is_subclass_of($class, \Symfony\Component\Console\Command\Command::class) && !(new \ReflectionClass($class))->isAbstract()) {
                    $this->application->add(new $class());
                }
            } catch (\Throwable $e) {

            }
        }
    }
}