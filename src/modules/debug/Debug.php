<?php
/**
* Debugging Class for Framework. Contains
* common/useful debugging methods for aiding debugging
*
* @author		Clinton Alexander
* @version		v1.0
*/

// Begin tick methods
if(Debug::isLineMonitorEnabled()) {
	declare(ticks=1);
	Debug::monitorLinesExecuted();
}

class Debug {
	private static $timers 		= array();
	/* Number of lines executed */
	private static $lineCount 	= 0;
	/* Lines that have been executed with file*/
	private static $executedLines = array();
	
	// Cannot be initialised
	public function __construct() { 
		throw new BadMethodCallException('Do not initialise Debug class');
	}
		
	//============================
	// Timer
	//============================
	
	/**
	* A timer to track CPU time of parts of script
	*
	* @param	String	ID for the timer
	* @param	Bool	If true, it will work difference and output
	* @return	Int		NULL if output is false
	*/
	public static function timer($type, $output = false) {
		// If output is false, it will record time
		if(!$output) {
			self::$timers[$type] = microtime(true);
		// If output is true, it will echo the time difference
		} else {
			$end = microtime(true);
			$diff =  ($end - self::$timers[$type]);
			
			echo "Time difference: " . $diff . "s (".$type.")<br />";
			return $diff;
		}
		
		return NULL;
	}
	
	//============================
	// Variable Management
	//============================

	/**
	* Displays a formatted var_dump
	*
	* @param	Object 	To be dumped
	* @return	Void
	*/
	public static function fdump($variable) {
		if(Debug::enabled()) {
			if(func_num_args() >= 1) {
				echo "<pre>";
				$args = func_get_args();
				
				$count = count($args);
				for($x = 0; $x < $count; $x++) {
					var_dump($args[$x]);
				}
				echo Debug::getBackTrace('', 1, 'fdumped in');
				echo "\n\n";
				echo "</pre>"; 
			}
		}
	}
	
	/**
	* fdump with death/ deathdump
	*/
	public static function ddump($variable) {
		if(Debug::enabled()) {
			if(func_num_args() >= 1) {
				echo "<pre>";
				$args = func_get_args();
				
				$count = count($args);
				for($x = 0; $x < $count; $x++) {
					var_dump($args[$x]);
				}
				echo Debug::getBackTrace('', 1, 'fdumped in');
				echo "\n\n";
				echo "</pre>"; 
			}
		}
		die();
	}

	/**
	* Returns a formatted var_dump
	*
	* @param	Object 	To be dumped
	* @return	Void
	*/
	public static function vdump($variable) {
		$dump = '';
		if(Debug::enabled()) {
			if(func_num_args() >= 1) {
				ob_start();
				echo "<pre>";
				$args = func_get_args();
				
				$count = count($args);
				for($x = 0; $x < $count; $x++) {
					var_dump($variable);
				}
				echo Debug::getBackTrace('', 1, 'fdumped in');
				echo "\n\n";
				echo "</pre>"; 
				$dump = ob_get_clean();
			}
		}
		
		return $dump;
	}

	/** 
	* Alias to fdump
	* A leaf taken from JS
	*
	* @param	Object 	To be dumped
	* @return	Void
	*/
	public static function alert($variable) {
		fdump($variable);
	}	
	
	//============================
	// Stack Trace
	//============================

	/**
	* Prints a usable debug backtrace
	*
	* @param	String		New line type
	* @param	int			Max length of trace
	* @return	void
	*/
	public static function getBacktrace($NL = "<br />", $length = NULL, $dbgMsg = NULL) {
		$dbgTrace = debug_backtrace();
		
		if($length == NULL) {
			$length = count($dbgTrace);
		} else {
			$length = min($length + 1, count($dbgTrace));
		}
		
		if($dbgMsg == NULL) {
			$dbgMsg = 'Backtrace' . $NL;
		}
		
		$dbgMsg = $NL. $dbgMsg;
		for($dbgIndex = 1; $dbgIndex < $length; $dbgIndex++) {
			$dbgInfo = $dbgTrace[$dbgIndex];
			
			$args = array();
			foreach ($dbgInfo['args'] as $a) {
				switch (gettype($a)) {
				case 'integer':
				case 'double':
					$args[] = $a;
					break;
				case 'string':
					$a = htmlspecialchars(str_trunc($a, 64));
					$args[] ="\"$a\"";
					break;
				case 'array':
					$args[] = 'Array('.count($a).')';
					break;
				case 'object':
					$args[] = 'Object('.get_class($a).')';
					break;
				case 'resource':
					$args[] = 'Resource('.strstr($a, '#').')';
					break;
				case 'boolean':
					$args[] = $a ? 'true' : 'false';
					break;
				case 'NULL':
					$args[] = 'NULL';
					break;
				default:
					$args[] = 'Unknown';
				}
			}
			
			$dbgMsg .= " ".$dbgInfo['file'].
				" (line " . $dbgInfo['line'] . ") -> {" . 
				$dbgInfo['function'] . "}(".
				join(",",$args) . ") " . $NL;
		}
		return $dbgMsg;
	}
	
	/**
	* Gets most recently executed lines
	*
	* @param	int			Max number of lines	[default=NULL/ALL]
	* @return	array		Array of lines
	*/
	public static function getLastExecutedLines($length = NULL) {
		$dbgTrace = debug_backtrace();
		
		if($length == NULL) {
			$length = count($dbgTrace);
		} else {
			$length = min($length + 1, count($dbgTrace));
		}
		
		$lines = array();
		for($dbgIndex = 1; $dbgIndex < $length; $dbgIndex++) {
			$dbgInfo = $dbgTrace[$dbgIndex];
			
			$lines[] = array('line' => $dbgInfo['line'],
							 'file' => $dbgInfo['file']);
		}
		return $lines;
	}
	
	//============================
	// Line Counter and Monitor (Ticket)
	//============================
	
	/**
	* Monitor all lines executed
	*
	* @return	void
	*/
	public static function monitorLinesExecuted() {
		register_tick_function('Debug::tickLineMonitor');
	}
	
	/**
	* Tick line monitor
	*
	* @return	void
	*/
	public static function tickLineMonitor() {
		$lines = Debug::getLastExecutedLines(1);
		
		for($x = 0; $x < count($lines); $x++) {
			self::$executedLines[] = $lines[$x];
		}
		
		self::$lineCount += count($lines);
	}
	
	/**
	* Get executed lines
	*
	* @return	void
	*/
	public static function getExecutedLines() {
		return self::$executedLines;	
	}
	
	/**
	* Check if lines are being monitored
	*
	* @return	void
	*/
	public static function isLineMonitorEnabled() {
		return ((self::enabled()) && (false));
	}
	
	//============================
	// Status checkers
	//============================

	/**
	* Whether to output debugging text at current execution.
	*
	* @todo		Add permissions checking after moved into object
	* @return	Boolean
	*/
	public static function enabled() {
		return false;
	}
}

?>