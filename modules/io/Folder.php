<?php
/**
* A representation of a folder as an extention of a file
*
* @author	Clinton Alexander
* @version	1
*/

CleanPHP::import('io.File');
CleanPHP::import('io.FileNotFoundException');
CleanPHP::import('io.IOException');

/**
* Representation of a folder
*
* @package	io
*/
class Folder extends File {
	/**
	* Folder mask
	*/
	const FOLDER = 0x1;
	/**
	* File mask
	*/
	const FILE   = 0x2;
	/**
	* Include ., .. folders, does not include hidden files/folders
	*/
	const INCLUDE_DOT_FOLDERS = 0x4;
	
	/**
	* Create this representation of a folder
	*
	* @param	string		folder		Folder path
	*/
	public function __construct($folder) {
		$folder = new CoreString($folder);
		if(!$folder->substring($folder->length() - 1, 1)->equals('/')) {
			$folder = $folder->append('/');	
		}
		
		parent::__construct($folder);
	}
	
	/**
	* Get a file from within this folder
	*
	* @param	string		filename	File name
	* @return	File		File from within this folder
	*/
	public function getFile($filename) {
		return new File($this->path->append((string) $filename));	
	}
	
	/**
	* Get a folder from within this folder
	*
	* @param	string		folderName	Folder name
	* @return	Folder		The folder from within this folder
	*/
	public function getFolder($folderName) {
		return new Folder($this->path->append((string) $folderName));
	}
	
	/**
	* Get the listing of all folders in this directory
	*
	* @throws	FileNotFoundException	When the directory doesn't exist
	* @throws	IOException				When an error reading occurs
	* @return	array	An array of folder objects
	*/
	public function getFolderList() {
		return $this->getListing(self::FOLDER);
	}
	
	/**
	* Get the listing of all files in this directory
	*
	* @throws	FileNotFoundException	When the directory doesn't exist
	* @throws	IOException				When an error reading occurs
	* @return	array	An array of File objects
	*/
	public function getFileList() {
		return $this->getListing(self::FILE);
	}
	
	/**
	* Get the listing of objects in this folder based on a mask
	*
	* @throws	FileNotFoundException	When the directory doesn't exist
	* @throws	IOException				When an error reading occurs
	* @param	mask	The mask of which objects to list, default is everything
	*					except . and ..
	*/
	public function getListing($mask = 0x3) {
		if($this->exists()) {
			$dirHandle = opendir((string) $this->path);
			if($dirHandle === false) {
				throw new IOException('Could not open directory for reading');
			} else {
				$listing = array();
				while(($entry = readdir($dirHandle)) !== false) {
					$entry = $this->path . $entry;
					$isDir = is_dir($entry);
					if($isDir && ($mask & self::FOLDER)) {
						$folder = new Folder($entry);
						if(!$folder->isDotFolder() || ($mask & self::INCLUDE_DOT_FOLDERS)) {
							$listing[] = $folder;
						}
					} else if(!$isDir && ($mask & self::FILE)) {
						$listing[] = new File($entry);
					}
				}
				
				closedir($dirHandle);
				
				return $listing;
			}
		} else {
			throw new FileNotFoundException('The directory does not exist');
		}
	}
	
	/**
	* Check if the given folder is a dot folder, . or .., which represent the
	* current folder and the parent folders respectively. Does not match
	* Unix hidden folders (.foldername)
	*
	* @return	True if the folder location is  a . or .. folder
	*/
	public function isDotFolder() {
		return (($this->path->substring(-2, 2)->equals('./')) 
			 || ($this->path->substring(-3, 3)->equals('../'))); 
	}
	
	/**
	* Check if this folder exists
	*
	* @return	bool	True if exists
	*/
	public function exists() {
		return is_dir((string) $this->path);	
	}
	
	/**
	* Create the directory and any other directories that may be required to
	* create it
	*
	* @throws	IOException		If the directory could not be created
	*/
	public function mkdir() {
		if(!mkdir((string) $this->path, 0777, true)) {
			throw new IOException('Could not create directory at ' . $this->path);
		}
	}
	
	/**
	* Create the directory and any other directories that may be required to
	* create it
	*
	* @throws	IOException		If the directory could not be created
	*/
	public function create() {
		$this->mkdir();	
	}
	
	/**
	* Delete this directory and all subfiles
	*
	* @throws	IOException		If the directory could not be removed or is not a directory
	*/
	public function delete() {
		if(is_dir($this->path)) {
			$fileList = $this->getFileList();
			foreach($fileList as $file) {
				$file->delete();
			}
			
			$folderList = $this->getFolderList();
			foreach($folderList as $folder) {
				$folder->delete();
			}
			
			if(!rmdir((string) $this->path)) {
				throw new IOException('Could not delete folder at ' . $this->path);
			}
		} else if(is_file($this->path)) {
			throw new IOException('Target folder ' . $this->path . ' for deletion is not a folder');
		}
	}
}
