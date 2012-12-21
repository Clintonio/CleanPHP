<?php
/**
* A language identifier. Languages only "exist" if you create it
*
* @author	Clinton Alexander
* @version	1.0
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
	* @param	String		Language ID
	*/
	private function __construct(String $lang) {
		self::$languages[(string) $lang] = $this;
		
		$this->code = $lang;
	}
	
	/**
	* Get the ID of this language
	*
	* @return	String	ID of this language
	*/
	public function getLanguageCode() {
		return $this->code;
	}
	
	/**
	* Check if a language exists.
	*
	* @param	String		Language code
	* @return	True if the language is has been created
	*/
	public static function languageExists(String $lang) {
		return isset(self::$languages[(string) $lang]);	
	}
	
	/**
	* Get a language given by an ID
	*
	* @param	String		Language code
	* @return	The language object
	*/
	public static function getLanguage(String $lang) {
		if(self::languageExists($lang)) {
			return self::$languages[(string) $lang];
		} else {
			return new Language($lang);	
		}
	}
	
	/**
	* Create a given language
	*
	* @param	String		Language code
	* @return	void
	*/
	public static function createLanguage(String $lang) {
		if(!self::languageExists($lang)) {
			new Language($lang);	
		}
	}
}