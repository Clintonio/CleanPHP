<?php

CleanPHP::import('error.ErrorHandler');
CleanPHP::import('io.Folder');

/**
* An abstract representation of a temporarily uploaded
* file uploaded via the POST file upload mechanism
*
* @author	Clinton Alexander
*/
class FileUpload {
	private $name;
	
	/**
	* Create a new file upload object
	*
	* @param	string		Name of file upload in POST form
	*/
	public function __construct($name) {
		$this->name = (string) $name;	
	}
	
	/**
	* Check if this is a valid file upload
	*
	* @return	bool	True if the file exists
	*/
	public function exists() {
		return (isset($_FILES[$name]));
	}
	
	/**
	* Check if this file upload completed successfully
	*
	* @return	bool	True if the file was successful
	*/
	public function complete() {
		return ($this->exists() && $_FILES[$name]['error'] === UPLOAD_ERR_OK);
	}
	
	/**
	* Move this file to a new location, it will
	* be deleted after this script is executed otherwise.
	* Will overwrite existing files.
	*
	* @throws	FileNotFoundException	Thrown when file cannot be found
	* @throws	IOException				When the file cannot be moved
	* @param	Folder					Folder to move file to
	* @param	String					(Optional) Alternative file name
	* @return
	*/
	public function move(Folder $folder, String $name = NULL) {
		if($name != NULL) {
			$file = $folder->getFile($name);	
		} else {
			$file = $folder->getFile($_FILES[$this->name]['name']);	
		}
		
		ErrorHandler::emitExceptions();
		
		try {
			if(!move_uploaded_file($_FILES[$this->name]['tmp_name'], (string) $file)) {
				throw new FileNotFoundException('Could not move uploaded file, it does not exist');
			}
		} catch (ErrorException $e) {
			throw new IOException('Could not upload file, see cause exception', $e);
		}
		
		ErrorHandler::reset();
	}
}

?>