<?php


	namespace LiftKit\Command;



	class Command
	{
		protected $command;
		protected $resource;
		protected $pipes;

		protected $stdOut;
		protected $stdErr;


		public function __construct ($command)
		{
			$this->command = $command;

			$this->resource = proc_open(
				$this->command,
				$this->createDescriptor(),
				$this->pipes
			);
		}


		public function __destruct ()
		{
			$this->close();
		}


		public function execute ()
		{
			$this->stdErr = $this->readStdErr();
			$this->stdOut = $this->readStdOut();

			return $this->close();
		}


		public function close ()
		{
			if (is_resource($this->resource)) {
				return proc_close($this->resource);
			} else {
				return null;
			}
		}


		public function getStdOut ()
		{
			return $this->stdOut;
		}


		public function getStdErr ()
		{
			return $this->stdErr;
		}


		public function writeStdIn ($input)
		{
			fwrite($this->pipes[0], $input);
		}


		protected function readStdErr ()
		{
			$output = stream_get_contents($this->pipes[2]);
			fclose($this->pipes[2]);

			return $output;
		}


		protected function readStdOut ()
		{
			$output = stream_get_contents($this->pipes[1]);
			fclose($this->pipes[1]);

			return $output;
		}


		protected function createDescriptor ()
		{
			return array(
				0 => array("pipe", "r"),  // stdin
				1 => array("pipe", "w"),  // stdout
				2 => array("pipe", "w"),  // stderr
			);
		}


	}