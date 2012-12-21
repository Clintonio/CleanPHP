<?php
/**
* A language identifier. Languages only "exist" if you create it
*
* @author	Clinton Alexander
* @version	1.0
*/

/**
* A representation of a language
*
* @package	locale
*/
class Language {
	/**
	* Languages that are known to be in use
	*/
	private static $languages = array();
	
	/**
	* Identifier of this language
	*/
	private $code;
	
	/**
	* Create a new language
	*
	* @param	string		lang	Language ID, preferably an ISO language code
	*/
	private function __construct($lang) {
		$lang = (string) $lang;
		self::$languages[$lang] = $this;
		
		$this->code = $lang;
	}
	
	/**
	* Get the ID of this language
	*
	* @return	string	ID of this language
	*/
	public function getLanguageCode() {
		return $this->code;
	}
	
	/**
	* Check if a language exists.
	*
	* @param	string		lang	Language code
	* @return	True if the language is has been created
	*/
	public static function languageExists($lang) {
		return isset(self::$languages[(string) $lang]);	
	}
	
	/**
	* Get a language given by an ID
	*
	* @param	string		lang	Language code
	* @return	The language object
	*/
	public static function getLanguage($lang) {
		$lang = (string) $lang;
		if(self::languageExists($lang)) {
			return self::$languages[$lang];
		} else {
			return new Language($lang);	
		}
	}
	
	/**
	* Create a given language
	*
	* @param	string		lang	Language code
	* @return	void
	*/
	public static function createLanguage($lang) {
		$lang = (string) $lang;
		if(!self::languageExists($lang)) {
			new Language($lang);	
		}
	}
}
