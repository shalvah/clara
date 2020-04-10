<?php

namespace Shalvah\Clara\Tests;

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
        $nullOutput = $this->createMock(NullOutput::class);
        $output = new Clara('app1', $nullOutput);

        $nullOutput->expects($this->once())
            ->method('writeln')
            ->with("App 1 - Output 1");
        $output->line("App 1 - Output 1");

        Clara::mute();

        $output->line("App 1 - Output 2");
        (new Clara('app1', $nullOutput))->line("App 2 - Output 1");
    }

    public function test_mute_with_arg_mutes_only_app()
    {
        $nullOutput = $this->createMock(NullOutput::class);
        $output = new Clara('app1', $nullOutput);

        $nullOutput->expects($this->once())
            ->method('writeln')
            ->with("App 1 - Output 1");
        $output->line("App 1 - Output 1");

        Clara::mute('app1');
        $output->line("App 1 - Output 2");

        $nullOutput2 = $this->createMock(NullOutput::class);
        $nullOutput2->expects($this->once())
            ->method('writeln')
            ->with("App 2 - Output 1");
        (new Clara('app2', $nullOutput2))->line("App 2 - Output 1");
    }

    public function test_unmute_without_args_unmutes_all()
    {
        $nullOutput = $this->createMock(NullOutput::class);
        $output = new Clara('app1', $nullOutput);

        Clara::mute();
        $output->line("App 1 - Output 1");
        (new Clara('app1', $nullOutput))->line("App 2 - Output 1");

        Clara::unmute();
        $output->line("App 1 - Output 2");
        (new Clara('app1', $nullOutput))->line("App 2 - Output 2");

        Clara::mute('app1');
        $output->line("App 1 - Output 3");

        Clara::unmute();
        $output->line("App 1 - Output 4");
    }

    public function test_unmute_with_arg_unmutes_only_app()
    {
        $nullOutput = $this->createMock(NullOutput::class);
        $output = new Clara('app1', $nullOutput);

        Clara::mute();
        $output->line("App 1 - Output 1");
        (new Clara('app1', $nullOutput))->line("App 2 - Output 1");

        Clara::unmute('app2');
        $output->line("App 1 - Output 2");
        (new Clara('app1', $nullOutput))->line("App 2 - Output 2");
    }
}