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
		$this->path = new String($path);
	}
	
	/**
	* Get the name of this file
	*
	* @return	string		The file's name
	*/
	public function getName() {
		return basename($this->path);
	}
	
	/**
	* Check if this file exists
	*
	* @return	bool	True if this file exists (and is not a folder)
	*/
	public function exists() {
		return (is_file((string) $this->path));
	}
	
	/**
	* Delete this file if it exists and is a file
	* 
	* @throws	IOException		If the file cannot be erased or is a directory
	*/
	public function delete() {
		if(is_dir($this->path)) {
			throw new IOException('Target ' . $this->path . ' is a folder');
		} else if(!unlink($this->path)) {
			throw new IOException('File ' . $this->path . ' could not be deleted');
		}
	}
	
	/**
	* Tostring returns the current path value
	*
	* @return	string		Path
	*/
	public function __toString() {
		return (string) $this->path;	
	}
	
	/**
	* Write data to this file, overwritting any existing file, unless
	* the append flag is true
	*
	* @throws	IOException		When the file could not be written to
	* @param	mixed	data		The data to write
	* @param	bool	append		True if in append mode
	*/
	public function write($data, $append = false) {
		$flags = ($append ? FILE_APPEND : 0);
		if(!file_put_contents($this->path, $data, $flags)) {
			throw new IOException('Could not write to the file');
		}
	}
	
	/**
	* Append data to this file
	*
	* @throws	IOException		When the file could not be written to
	* @param	mixed	data		The data to write
	*/
	public function append($data) {
		$this->write($data, true);
	}
}

?>
