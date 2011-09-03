<?php

CleanPHP::import('io.FileNotFoundException');
CleanPHP::import('io.IOException');

/**
* Representation of a file in PHP
*
* @author	Clinton Alexander
*/
class File {
	protected $path;
	
	/**
	* Create a new file at a given path
	*
	* @param	String		Path
	*/
	public function __construct(String $path) {
		$this->path = $path;
	}
	
	/**
	* Check if this file exists
	*
	* @return	bool	True if this file exists (and is not a folder)
	*/
	public function exists() {
		return (is_file($this->path));
	}
	
	/**
	* Tostring returns the current path value
	*
	* @return	string		Path
	*/
	public function __toString() {
		return (string) $this->path;	
	}
}

?>