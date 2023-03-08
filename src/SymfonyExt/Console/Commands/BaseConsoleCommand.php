<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;

abstract class BaseConsoleCommand extends \Symfony\Component\Console\Command\Command implements GetSetConsoleInterface, GetSetLoggerInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new OutputFormatterStyle('red', null, ['bold']);
        $output->getFormatter()->setStyle('red', $outputStyle);

        $outputStyle = new OutputFormatterStyle('yellow', null, ['bold']);
        $output->getFormatter()->setStyle('yellow', $outputStyle);

        $outputStyle = new OutputFormatterStyle('green', null, ['bold']);
        $output->getFormatter()->setStyle('green', $outputStyle);

        $this->setConsoleInput($input);
        $this->setConsoleOutput($output);
    }

    protected function tagRed(string $value): string
    {
        return sprintf('<red>%s</red>', $value);
    }

    protected function tagYellow(string $value): string
    {
        return sprintf('<yellow>%s</yellow>', $value);
    }

    protected function tagGreen(string $value): string
    {
        return sprintf('<green>%s</green>', $value);
    }
}