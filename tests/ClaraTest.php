<?php

namespace Shalvah\Clara\Tests;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;
use Shalvah\Clara\Clara;
use Symfony\Component\Console\Output\NullOutput;

class ClaraTest extends TestCase
{

    protected function tearDown(): void
    {
        Clara::reset();
    }

    public function test_mute_without_args_mutes_all()
    {
        $handle = Phony::mock(NullOutput::class);
        $nullOutput = $handle->get();
        $app1 = new Clara('app1', $nullOutput);
        $app2 = new Clara('app2', $nullOutput);

        $app1->line("App 1 - Output 1");

        Clara::mute();

        $app1->line("App 1 - Output 2");
        $app2->line("App 2 - Output 1");

        $handle->writeln->once()->called();
        $handle->writeln->firstCall()->calledWith("App 1 - Output 1");
    }

    public function test_mute_with_arg_mutes_only_app()
    {
        $handle = Phony::mock(NullOutput::class);
        $nullOutput = $handle->get();

        $app1 = new Clara('app1', $nullOutput);
        $app2 = new Clara('app2', $nullOutput);

        $app1->line("App 1 - Output 1");

        Clara::mute('app1');
        $app1->line("App 1 - Output 2");

        $app2->line("App 2 - Output 1");

        $handle->writeln->twice()->called();
        $handle->writeln->firstCall()->calledWith("App 1 - Output 1");
        $handle->writeln->lastCall()->calledWith("App 2 - Output 1");
    }

    public function test_unmute_without_args_unmutes_all()
    {
        $handle = Phony::mock(NullOutput::class);
        $nullOutput = $handle->get();
        $app1 = new Clara('app1', $nullOutput);
        $app2 = new Clara('app2', $nullOutput);

        Clara::mute();
        $app1->line("App 1 - Output 1");
        $app2->line("App 2 - Output 1");

        Clara::unmute();
        $app1->line("App 1 - Output 2");
        $app2->line("App 2 - Output 2");

        $handle->writeln->twice()->called();
        $handle->writeln->firstCall()->calledWith("App 1 - Output 2");
        $handle->writeln->lastCall()->calledWith("App 2 - Output 2");

        Clara::mute('app1');
        $app1->line("App 1 - Output 3");

        Clara::unmute();
        $app1->line("App 1 - Output 4");

        $handle->writeln->thrice()->called();
        $handle->writeln->lastCall()->calledWith("App 1 - Output 4");
    }

    public function test_unmute_with_arg_unmutes_only_app()
    {
        $handle = Phony::mock(NullOutput::class);
        $nullOutput = $handle->get();
        $app1 = new Clara('app1', $nullOutput);
        $app2 = new Clara('app2', $nullOutput);

        Clara::mute();
        $app1->line("App 1 - Output 1");
        $app2->line("App 2 - Output 1");

        Clara::unmute('app2');
        $app1->line("App 1 - Output 2");
        $app2->line("App 2 - Output 2");

        $handle->writeln->once()->called();
        $handle->writeln->lastCall()->calledWith("App 2 - Output 2");
    }
}