<?php

CleanPHP::import('io.File');
CleanPHP::import('io.FileNotFoundException');
CleanPHP::import('io.IOException');

/**
* Representation of a folder
*
* @author	Clinton Alexander
*/
class Folder extends File {
	
	/**
	* Create this representation of a folder
	*
	* @param	String		Folder path
	*/
	public function __construct(String $folder) {
		if(!$folder->substring($folder->length() - 1, 1)->equals('/')) {
			$folder = $folder->append('/');	
		}
		
		parent::__construct($folder);
	}
	
	/**
	* Get a file from within this folder
	*
	* @param	String		File name
	* @return	File		File from within this folder
	*/
	public function getFile(String $filename) {
		return new File($this->path->append($filename));	
	}
	
	/**
	* Check if this folder exists
	*
	* @return	bool	True if exists
	*/
	public function exists() {
		return is_dir($this->path);	
	}
	
	/**
	* Create the directory
	*/
	public function mkdir() {
		mkdir($this->path);	
	}
	
	/**
	* Create the directory
	*/
	public function create() {
		mkdir($this->path);	
	}
}