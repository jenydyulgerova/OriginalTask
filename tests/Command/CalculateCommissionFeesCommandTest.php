<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateCommissionFeesCommandTest extends KernelTestCase
{
    public function testExecute():void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:calculate-commission-fees');
        $commandTester = new CommandTester($command);
        $commandTester->execute([

            'dataFile' => 'data/input.csv',
        ]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = explode("\n", trim($commandTester->getDisplay(), "\n"));
        $this->assertSame(
            ['0.60', '3.00', '0.00', '0.06', '1.50', '0', '0.70', '0.30', '0.30', '3.00', '0.00', '0.00', '8612'],
            $output
        );
    }
}
