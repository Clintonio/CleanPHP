<?php
/**
* A class for loading layout objects from a common directory with language
* based directory support.
*
* @author	Clinton Alexander
*/

CleanPHP::import('layout.Layout');
CleanPHP::import('io.Folder');

/**
* A layoutloader which can locate layouts based on constructor variables
* abstracting the need to rewrite multiple paths if a layout directory moves
*/
class LayoutLoader {
	/**
	* The file extention we are loading layouts from
	*/
	private $ext;
	/**
	* The directory to search for layous in
	*/
	private $layoutDir;
	
	/**
	* Create a new layout loader, with the given layout extention, and an optional
	* layout directory (will default to current directory)
	*
	* @param	string	ext			The layout extention, default "php"
	* @param	Folder	layoutDir	The layout directory, default "."
	*/
	public function __construct($ext = 'php', Folder $layoutDir = NULL) {
		if($layoutDir === NULL) {
			$layoutDir = new Folder('.');
		}
		
		$this->layoutDir = $layoutDir;
		$this->ext       = $ext;
	}
	
	/**
	* Get a layout from this content with an optional language parameter
	* which will match a subdirectory of the language's code, ie;
	* <LAYOUT_DIR>/<LANG_CODE>/<FILE>.<EXT>
	*
	* @throws	FileNotFoundException
	* @param	string		layoutName	The name of the layout file to load
	* @param	Language	lang		The language directory, default: none
	* @return	Layout		A layout file corresponding to the parameters
	*/
	public function getLayout($layoutName, Language $lang = NULL) {
		$layoutFileName = $layoutName . '.' . $this->ext;
		if($lang instanceof Language) {
			$langDir = $lang->getLanguageCode();
			$layoutFile = $this->layoutDir->getFolder($langDir)->getFile($layoutFileName);
		} else {
			$layoutFile = $this->layoutDir->getFile($layoutFileName);
		}
		
		return new Layout($layoutFile);
	}
}
?>
