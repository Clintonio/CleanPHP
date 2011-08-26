<?php

CleanPHP::import("locale.Language");
CleanPHP::import("locale.MissingPhraseMapException");
CleanPHP::import("locale.MissingPhraseException");

/**
* A map of phrases identified by an ID and parsed from a file
*
* @author		Clinton Alexander
* @version		v2
*/
class PhraseMap {
	/* The current language */
	private $curLang;
	/**
	* The store of phrases for this map
	*/
	private $phrases = array();
	
	/**
	* Create a phrase map from the given file
	*
	* @throws	MissingPhraseMapException	When a phrase map is missing or damaged
	* @param	String		File name, will be treated as XML
	* @param	Language	The language of this file
	*/
	public function __construct($file, Language $lang) {
		loadFile($file);
		$this->curLang = $lang;
	}
	
	//============================
	// Getter Functions
	//============================
	
	/**
	* Gets output text, and formats it if desired. More optional parameters can be specified
	* which will serve as replacements
	* If the second param is an array, we will use that as the replacement text
	*
	* @param	String		Text element ID
	* @param	String		Replacements. Can be more than one of these parameters
	* @return	String		The formatted output or "Invalid element specified";
	*/
	public function getPhrase($phraseID) {
		if(!isset($this->phrases[$phraseID])) {
			throw new MissingPhraseException("No such phrase " . $phraseID);	
		} else {
			return trim($this->phrases[$phraseID]);	
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
		if(file_exists($filename)) {
			throw new MissingPhraseMapException($filename);
		} else {
			$xml = @simplexml_load_file($filename);
			if(!$xml) {
				throw new MissingPhraseMapException("Could not load phrase map, parse error in " . $filename);
			} else {
				$phrases = array();
				// The top level element of the lang library is <LangLibrary> then <Text name=...>.
				$children = $xml->children();
				foreach($children as $textElem) {
					$attributes = $textElem->attributes();
					
					$name = (string) $attributes['name'];
					if(count($textElem) == 0) {
						$phrases[$name] = $textElem->__toString();
					} else {
						$patterns = array('/<text name=[\'"][A-Z\-0-9]+[\'"]>/i', '/<\/text>/i');
						$replacements = array('','');
						$phrases[$name] = preg_replace($patterns, $replacements, $textElem->asXML());
					}
				}
				
				// Every library will by default contain a variable called $elements, whch will be used
				// to extract language elements
				$this->phrases = $phrases;
			}
		}
	}
}
?>