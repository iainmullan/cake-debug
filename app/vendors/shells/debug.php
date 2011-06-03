<?php
/**
 * A very simple Console task for CakePHP to assist with the mundane task of switching the debug level
 * in your app.
 *
 * When the task is run with no arguments, displays the current debug level.
 *
 * Supplying a value of 0, 1 or 2 as the first and only argument, will set the debug to that level
 *
 * 		usage: cake debug <debug level>
 *
 * Copyright (c) Iain Mullan 2010
 *
 * @author Iain Mullan
 * @created 13th May 2010
 * @modified 3rd June 2011 - More verbose output when using set function
 */
class DebugShell extends Shell {

	function main() {
		$this->out("");

		if (empty($this->args)) {
			$this->get();
		} else {
			$debug_level = $this->args[0];

			if (!is_numeric($debug_level) || !in_array($debug_level, array(0,1,2))) {
				$this->out("\tusage: cake debug [0,1,2] \n");
			} else {
				$current = $this->_get();
				$this->out("\tChanging debug from $current to $debug_level");
				$this->set($debug_level);
				$this->get(); // confirm the change
			}

		}

		$this->out("");
	}
	
	function _get() {
		$file = APP.'config/core.php';
		$lines = file($file);
		$line = preg_grep("/Configure::write\('debug'/", $lines);
		$line = array_pop($line);

		$matches = array();
		preg_match('/[0-2]/', $line, $matches);
		$val = $matches[0];	
		return $val;	
	}

	function get() {
		
		$val = $this->_get();

		$this->out("\tThe current debug value is $val");

	}

	function set($debug_level) {
		
		$file = APP.'config/core.php';

		$lines = file($file);

		foreach($lines as $i => $line) {
			if (strstr($line, "Configure::write('debug'") !== false) {
				$lines[$i] = "Configure::write('debug', $debug_level);\n";
				break;
			}
		}

		file_put_contents($file, $lines);
	}

}
?>