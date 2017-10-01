<?php
/**
* An abstract representation of a temporarily uploaded
* file uploaded via the POST file upload mechanism
*
* @author	Clinton Alexander
* @version	1
*/

CleanPHP::import('error.ErrorHandler');
CleanPHP::import('io.Folder');

/** 
* File upload representation
*
* @package	input
*/
class FileUpload {
	/**
	* Name of the file upload in the $_FILES array
	*/
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
		return (isset($_FILES[$this->name]));
	}
	
	//==================
	// Error checking
	//==================
	
	/**
	* Check if this file upload completed successfully
	*
	* @return	bool	True if the file was successful
	*/
	public function complete() {
		return ($this->exists() && $_FILES[$this->name]['error'] === UPLOAD_ERR_OK);
	}
	
	/**
	* Check if the file uploaded was too large
	*
	* @return	bool	True if the file uploaded failed and was too large
	*/
	public function errorFileTooLarge() {
		$error = $_FILES[$this->name]['error'];
		return (($error === UPLOAD_ERR_INI_SIZE) || ($error === UPLOAD_ERR_FORM_SIZE));
	}
	
	/**
	* Check if the file was uploaded fully
	*
	* @return	bool	True if a file was uploaded
	*/
	public function fileExists() {
		$error = $_FILES[$this->name]['error'];
		return (($error !== UPLOAD_ERR_PARTIAL) && ($error !== UPLOAD_ERR_NO_FILE));
	}
	
	/**
	* Check if the file failed due to not being able to write or the directory
	* not existing
	*
	* @return	bool	True if the file couldn't be written
	*/
	public function errorDiskWrite() {
		$error = $_FILES[$this->name]['error'];
		return (($error !== UPLOAD_ERR_CANT_WRITE) && ($error !== UPLOAD_ERR_NO_TMP_DIR));
	}
	
	//==================
	// Getters
	//==================
	/**
	* Get the original name of the file
	*
	* @return	string	Original name of file before upload
	*/
	public function getOriginalName() {
		return $_FILES[$this->name]['name'];
	}
	
	/**
	* Get the file upload failure code
	*
	* @return int	The file upload error code
	*/
	public function getErrorCode() {
		return $_FILES[$this->name]['error'];
	}
	
	/**
	* Get the MIME type of the file
	*
	* @return	string		MIME type of the image
	*/
	public function getType() {
		return ($this->exists() ? $_FILES[$this->name]['type'] : '');
	}
	
	/**
	* Get the temporary file location
	*
	* @return	File	The temporary file location
	*/
	public function getTempFile() {
		return new File($_FILES[$this->name]['tmp_name']);
	}
	
	//==================
	// Utilities
	//==================
	
	/**
	* Move this file to a new location, it will
	* be deleted after this script is executed otherwise.
	* Will overwrite existing files.
	*
	* @throws	FileNotFoundException	Thrown when file cannot be found
	* @throws	IOException				When the file cannot be moved
	* @param	\Folder		folder		Folder to move file to
	* @param	string		name		(Optional) Alternative file name
	* @return	File		The file object that resulted from this move
	*/
	public function move(Folder $folder, $name = NULL) {
		if($name !== NULL) {
			$file = $folder->getFile((string) $name);	
		} else {
			$file = $folder->getFile($_FILES[$this->name]['name']);	
		}
		
		ErrorHandler::throwExceptions();
		
		try {
			if(!move_uploaded_file($_FILES[$this->name]['tmp_name'], (string) $file)) {
				throw new FileNotFoundException(new CoreString('Could not move uploaded file, it does not exist'));
			}
		} catch (ErrorException $e) {
			throw new IOException('Could not upload file, see cause exception', $e);
		}
		
		ErrorHandler::reset();
		
		return $file;
	}
}

?>
