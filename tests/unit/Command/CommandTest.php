<?php


	namespace LiftKit\Tests\Unit\Command;

	use PHPUnit_Framework_TestCase as TestCase;
	use LiftKit\Command\Command;


	class CommandTest extends TestCase
	{
		/**
		 * @var Command
		 */
		protected $command;



		public function testStdOut ()
		{
			$this->command = new Command("echo 'test'");
			$responseCode = $this->command->execute();

			$this->assertEquals(0, $responseCode);
			$this->assertEquals("test\n", $this->command->getStdOut());
			$this->assertEquals('', $this->command->getStdErr());
		}


		public function testStdErr ()
		{
			$this->command = new Command('nocommand');
			$responseCode = $this->command->execute();

			$this->assertEquals(127, $responseCode);
			$this->assertEquals('', $this->command->getStdOut());
			$this->assertContains('command not found', $this->command->getStdErr());
		}



	}