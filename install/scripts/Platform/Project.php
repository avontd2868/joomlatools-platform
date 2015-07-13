<?php
namespace Platform;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use Joomlatools\Console\Application;

class Project
{
    public static function install()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return;
        }

        $cwd  = getcwd();
        $www  = dirname($cwd);
        $site = basename($cwd);

        $arguments = array(
            'site:install',
            'site'          => $site,
            '--www'         => $www,
            '--interactive' => true
        );

        self::logo();

        $output = new ConsoleOutput();
        $output->writeln("<info>Welcome to the Joomla Platform installer</info>");
        $output->writeln("Fill in the following details to configure your new application.");

        $application = new Application();
        $application->run(new ArrayInput($arguments));
    }

    public static function logo()
    {
        $output = new ConsoleOutput();

        $output->writeln("                                                                ");
        $output->writeln("    __               _        _____ _     _   ___               ");
        $output->writeln(" __|  |___ ___ _____| |___   |  _  | |___| |_|  _|___ ___ _____ ");
        $output->writeln("|  |  | . | . |     | | .'|  |   __| | .'|  _|  _| . |  _|     |");
        $output->writeln("|_____|___|___|_|_|_|_|__,|  |__|  |_|__,|_| |_| |___|_| |_|_|_|");
        $output->writeln("                                                                ");
    }
}