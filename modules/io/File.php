<?php
/**
* Representation of a file in PHP
*
* @author	Clinton Alexander
* @version	1
*/

CleanPHP::import('io.FileNotFoundException');
CleanPHP::import('io.IOException');

/**
* File representation
*
* @package	io
*/
class File {
	/**
	* Path of the file
	*/
	protected $path;
	
	/**
	* Create a new file at a given path
	*
	* @param	string		path	Path of the file we are representing
	*/
	public function __construct($path) {
		$this->path = (string) $path;
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
