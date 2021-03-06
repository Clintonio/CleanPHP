<?php
/**
* A map of phrases identified by an ID and parsed from a file
*
* @author		Clinton Alexander
* @version		2
*/

CleanPHP::import("locale.Language");
CleanPHP::import("locale.MissingPhraseMapException");
CleanPHP::import("locale.MissingPhraseException");

/**
* A map of phrases loaded from a given file
*
* @package	locale
*/
class PhraseMap implements ArrayAccess {
	/**
	* The current language 
	*/
	private $curLang;
	/**
	* The store of phrases for this map
	*/
	private $phrases = array();
	/**
	* Default phrase index 
	*/
	const DEFAULT_LANG_INDEX = '';
	
	/**
	* Create a phrase map from the given file
	*
	* @throws	MissingPhraseMapException	When a phrase map is missing or damaged
	* @param	String		File name, will be treated as XML
	* @param	Language	The language of this file
	*/
	public function __construct($file, Language $lang) {
		$this->loadFile($file);
		$this->curLang = $lang;
	}
	
	//============================
	// Getter Functions
	//============================
	
	/**
	* Return a phrase based on the current language or given alternate
	* language
	*
	* @param	String		phraseID	Text element ID
	* @param	Language	altLang		Alternate language to get phrase for (as opposed to current language) 
	* 									(use setLanguage instead if possible)
	* @return	String		The formatted output or null
	*/
	public function getPhrase($phraseID, Language $altLang = NULL) {
		$phraseID = (string) $phraseID;
		if(!isset($this->phrases[$phraseID])) {
			throw new MissingPhraseException("No such phrase " . $phraseID);	
		} else {
			// Pick our current language
			if(isset($altLang)) {	
				$langCode = (string) $altLang->getLanguageCode();
			} else {
				$langCode = (string) $this->curLang->getLanguageCode();
			}
			// Use default if none exists
			if(!isset($this->phrases[$phraseID][$langCode])) {
				$langCode = self::DEFAULT_LANG_INDEX;	
			}
			
			return $this->phrases[$phraseID][$langCode];	
		}
	}
	
	/**
	* Get the current language code
	*
	* @return	The language for this phrase map
	*/
	public function getLanguage() {
		return $this->curLang;
	}
	
	//============================
	// Setters
	//============================
	
	/**
	* Set the current language
	*
	* @param	Language	Current language
	*/
	public function setLanguage(Language $lang) {
		$this->curLang = $lang;	
	}
	
	//============================
	// Verification/ Validity
	//============================
	
	/**
	* Checks if an phrase exists
	*
	* @param	String		Phrase name
	* @return	Boolean		True if the phrase exists
	*/
	public function phraseExists($phraseID) {
		return isset($this->elements[$phraseID]);
	}
	
	//============================
	// File Processing
	//============================
		
	/**
	* Loads file into the language memory for the current language
	* 
	* @throws	MissingPhraseMapException	When a phrase map is missing or damaged
	* @param	String		File name
	* @return	void
	*/
	private function loadFile($filename) {
		if(!file_exists($filename)) {
			throw new MissingPhraseMapException(realpath($filename));
		} else {
			$xml = simplexml_load_file($filename);
			if(!$xml) {
				throw new MissingPhraseMapException("Could not load phrase map, parse error in " . $filename);
			} else {
				// The top level element of the lang library is <LangLibrary> then <Text name=...>.
				$children = $xml->children();
				foreach($children as $textElem) {
					$attributes = $textElem->attributes();
					
					$name = (string) $attributes['name'];
					if(count($textElem) == 0) {
						$this->phrases[$name][self::DEFAULT_LANG_INDEX] = $textElem->__toString();
					} else {
						$locales = $textElem->children();
						foreach($locales as $localeElem) {
							$this->parseLocaleElement($localeElem, $name);
						}
					}
				}
			}
		}
	}
	
	/**
	* Parse the phrases into the phrases list
	*
	* @param	SimpleXMLElement	The element to parse
	* @param	string				The name of this phrase
	*/
	private function parseLocaleElement(SimpleXMLElement $elem, $name) {
		// Whether this is a default element
		$default = false;
		
		$attributes = $elem->attributes();
		
		$locale = self::DEFAULT_LANG_INDEX;
		if(isset($attributes['code'])) {
			$locale = (string) $attributes['code'];
		}
		if(isset($attributes['default'])) {
			$default = (boolean) $attributes['default'];	
		}
		
		// In the case that there is no phrase for this language yet
		// or a default exists we will use that if no other does
		$patterns 		= array('/<locale[\s]+code=[\'"][A-Z\-0-9]+[\'"]([\s]+default=[\'"][A-Z\-0-9]+[\'"])?[\s]*>/i', '/<\/locale>/i');
		$replacements 	= array('','');
		$phrase 		= new CoreString(trim(preg_replace($patterns, $replacements, $elem->asXML())));
		
		$this->phrases[$name][$locale] = $phrase;
		
		// Ensure a default is set
		if(($default) || (!isset($this->phrases[$name][self::DEFAULT_LANG_INDEX]))) {
			$this->phrases[$name][self::DEFAULT_LANG_INDEX] = $phrase;
		}	
	}
	
	//============================
	// ArrayAccess methods
	//============================
    
    /**
    * Alias for phraseExists. Called when isset() is called on one of the elements
    * of this instance
    *
    * @param	string		phraseID		The ID of the phrase to check existance of
    * @return	bool		True if the phrase exists
    */
    public function offsetExists($phraseID) {
        return $this->phraseExists($phraseID);
    }
    
    /**
    * Alias for getPhrase. Is called when $instance[$phraseID] is called.
    * Calls without optional second parameter of getPhrase.
	*
	* @param	string		phraseID		Text element ID
	* @return	string		The formatted output or null;
	*/
    public function offsetGet($phraseID) {
        return $this->getPhrase($phraseID);
    }
	
	/**
	* This method is required by the array access interface, but will throw an
	* exception if accessed
	*
	* @throws	RuntimeException	If the user attempts to use this method
	*/
    public function offsetSet($offset, $value) {
        throw new RuntimeException("Cannot set indicies in the PhraseMap");
    }
    
    /**
    * This method is required by the array acces interface, but will throw an 
    * exception if used
	*
	* @throws	RuntimeException	If the user attempts to use this method
	*/
    public function offsetUnset($offset) {
        throw new RuntimeException("Cannot unset indicies in the PhraseMap");
    }
}
?>
