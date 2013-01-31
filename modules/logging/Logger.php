<?php
/**
* Logging Class. Creates regular logs
*
* @author		Clinton Alexander
* @version		v1.0
*/

/**
* A logger for creating logs
*
* @package	logging
*/
class Logger {
	/**
	* Name of the log, will be used in the file name
	*/
	protected $logName;
	/**
	* Directory to store this log
	*/
	protected $logDir;
	/**
	* Log location, depends on the logName and logDir
	*/
	protected $logLoc;
	/**
	* Maximum number of times to rotate the log file
	*/
	protected $rotateMax;
	/**
	* Maximum filesize of the log in kB
	*/
	protected $maxLogSize;
	/**
	* Current log text
	*/ 
	protected $log;
	
	/**
	* Default max log size in bytes
	*/
	protected static $defaultMaxSize		= 2048;
	/**
	* Default number of rotations before overwriting
	*/
	protected static $defaultMaxRotate 	= 5;
	
	/**
	* Maximum log size in kB
	*/
	const MAX_LOG_SIZE = 5000;
	
	/**
	* Creates a logger for logName
	*
	* @param	String		Logname
	* @param	String		Directory to save log file to
	*/
	public function __construct($logName, $logDir = "./") {
		$this->logName		= $logName;
		$this->logDir		= $logDir;
		$this->logLoc  		= $this->logDir . $this->logName;
		$this->rotateMax	= self::$defaultMaxRotate;
		$this->maxLogSize	= self::$defaultMaxSize;
	}
	
	//============================
	// Setters
	//============================
	
	/**
	* Change the current rotation size
	*
	* @param	String 	New rotation max size
	*/
	public function setRotationLength($length) {
		$this->rotateMax = (int) $length;	
	}
	
	/**
	* Change the current max log size
	*
	* @param	String	New maximum log file size
	*/
	public function setMaxLogSize($max) {
		$this->maxLogSize = (int) $max;	
	}
	
	/**
	* Change the default max size
	*
	* @param	String	Default max size
	*/
	public function setDefaultRotationLength($length) {
		self::$defaultMaxRotate = (int) $length;	
	}
	
	/**
	* Change the default max log size
	*
	* @param	String	Default max log size
	*/
	public function setDefaultMaxLogSize($max) {
		self::$defaultMaxSize = (int) $max;	
	}
	
	//============================
	// Interface
	//============================
	
	/**
	* Log data to EOF
	*
	* @param	string	text		Level of severity
	* @param	string	severity	The text for the severity of the log entry
	* @return	void
	*/
	public function log($text, $severity = '') {
		// [yyyy-mm-dd hh::mm:ss] - SEVERITY - TEXT \n
		$text = date('[Y-m-d H:i:s]', time()) . '-' . $severity . '-' . $text . "\n";
		
		$this->append($text);
	}
	
	//============================
	// Log File Manipulation
	//============================
	
	/**
	* Reads log file
	*
	* @return	string		text
	*/
	public function read() {
		if(!isset($this->log)) {
			if(false === ($log = file_get_contents($this->logLoc))) {
				throw new MissingResourceException($this->logLoc . ' log file is missing');
			} else {
				$this->log = $log;
			}
		} 
		
		return $this->log;
	}
	
	/**
	* Append to current log
	*
	* @param	string
	* @return	void
	*/
	public function append($text) {
		if(!is_file($this->logLoc)) {
			$this->create();
		}
		
		if(filesize($this->logLoc) > self::MAX_LOG_SIZE * 1024) {
			$this->rotate($text);
		} else {
			file_put_contents($this->logLoc, $text, FILE_APPEND);
		}
	}
	
	/**
	* Deletes current log
	*
	* @return	void
	*/
	public function delete() {
		if(is_file($this->logLoc)) {
			unlink($this->logLoc);
		}
	}
	
	/**
	* Creates log for current log name
	*
	* @param	String		Text to start with [default=Empty string]
	* @return	void
	*/
	protected function create($text = '') {
		if(!is_file($this->logLoc)) {
			file_put_contents($this->logLoc, $text);
		} else {
			throw new InvalidArgumentException('Log: ' . $this->logLoc
											   . ' already exists');
		}
	}
	
	/**
	* Rotates log
	*
	* @param	String		Text for new log	
	* @return	void
	*/
	protected function rotate($text) {
		$name = explode(".", $logName);
		if(count($name) == 1) {
			$ext = "";
			$name = $name[0];
		} else {
			$ext = end($name);
			array_unshift($name);
			
			$name = implode(".", $name);
		}
		
		// Look for empty log slot
		for($x = 1; $x < $this->rotateMax; $x++) {
			$bckLog = $this->logDir . $name . '.' . $x . $ext;
			if(!is_file($bckLog)) {
				// rename to backup, then create new
				rename($this->logLoc, $bckLog);
				$this->create($text);
				// NOTE THAT THIS RETURN IS HERE
				return;
			}
		}
		
		// No empty slot, rotate. Delete last log first
		$delLogName = $name . '.' . ($this->rotateMax - 1) . $ext;
		$delLog = new Logger($delLogName, $this->logDir);
		$delLog->delete();
		
		// Shift each log up
		for($x = $this->rotateMax - 2; $x > 1 ; $x--) {
			$bckLog = $this->logDir . $name . '.' . $x . $ext;
			$newLog = $this->logDir . $name . '.' . ($x + 1) . $ext;
			rename($bckLog, $newLog);
		}
		
		// Move this log
		$newLog = $this->logDir . $name . '.1' . $ext;
		rename($this->logLoc, $newLog);
		
		$this->create($text);
	}
}


?>
