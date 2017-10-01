<?php
/**
* A PHP based simple OO string with convenience functions
* Moved to package due to String being reserved in PHP7.0
*
* @author	Clinton Alexander
* @version	2
*/

/**
* String class for object oriented string access
*
* @package	core
*/
class CoreString {
	/** 
	* The raw string this object represents
	*/
	private $str;
	
	/**
	* Construct a new String
	*
	* @param	String		The string to construct
	*/
	public function __construct($string = "") {
		$this->str = (string) $string;	
	}
	
	/**
	* On loading, set the internal encoding to UTF8
	*/
	public static function onLoad() {
		mb_internal_encoding("UTF-8");
	}
	
	//=======================
	// Fundemental Methods
	//=======================
	
	/**
	* To string
	*
	* @return	The value of this string
	*/
	public function __toString() {
		return $this->str;	
	}
	
	/**
	* Checking equality between this string and another object. 
	* For checking if the string *objects* are identical use ===
	*
	* @param	mixed	value	The value we are comparing
	* @return	Whether the other value evaluates to the string value of this string
	*/
	public function equals($value) {
		return ((string) $value === $this->str); 	
	}
	
	/**
	* Parse the integer value that this string represents
	*
	* @return	int		Value this string represents
	*/
	public function asInt() {
		return (int) $this->str;	
	}
	
	/**
	* Parse the double value that this string represents
	*
	* @return	double	Value this string represents
	*/
	public function asDouble() {
		return (double) $this->str;
	}
	
	/**
	* Parse the float value that this string represents
	*
	* @return	double	Value that this string represents
	*/
	public function asFloat() {
		return (float) $this->str;
	}
	
	/**
	* Parse the boolean value that this string represents
	*
	* @return	bool	Value this string represents
	*/
	public function asBoolean() {
		return (boolean) $this->str;	
	}
	
	/**
	* Parse the boolean value that this string represents
	*
	* @return	bool	Value this string represents
	*/
	public function asBool() {
		return (boolean) $this->str;	
	}
	
	//=======================
	// Basic String Methods
	//=======================
	
	/**
	* Get the length of the string
	*
	* @return	int		Length of the string
	*/
	public function length() {
		return mb_strlen($this->str);
	}
	
	/**
	* Append a string to this string
	*
	* @param	string		String to append
	* @return	String		Resulting new string
	*/
	public function append($string) {
		return new CoreString($this->str . $string);
	}
	
	/**
	* Prepend a string to this string
	*
	* @param	string		String to Prepend
	* @return	String		Resulting new string
	*/
	public function prepend($string) {
		return new CoreString($string . $this->str);
	}
	
	//=======================
	// String editing methods (methods already available in str_ library)
	//=======================
	
	/**
	* Replace one or more values with one or more others. Identical to
	* str_replace
	*
	* @param	Mixed	The elements to search for
	* @param	Mixed	The elements to replace
	* @param	int		Maximum number of replacements. 0 is unlimited [default=0]
	* @return	String	The new string value
	*/
	public function replace($find, $replace, $max = 0) {
		return new CoreString(str_replace($find, $replace, $this->str, $max));
	}
	 
	/**
	* Encodes the string to be HTML safe
	* Alias for htmlspecialchars
	*
	* @return	String	HTML safe string
	*/
	public function htmlEncode() {
		return new CoreString(htmlspecialchars($this->str));	
	}
	
	//=======================
	// String manipulation methods (methods already available in str_ library)
	//=======================
	
	/**
	* Returns a substring of this string in the same manner
	* to PHP's substring
	*
	* @param	int		Start position
	* @param	int		Length (default = 0/unlimited)
	* @return	String	Substring of this string
	*/
	public function substring($start, $length = 0) {
		return new CoreString(mb_substr($this->str, $start, $length));
	}
	
	/**
	* Returns a string split into pieces by a separator
	*
	* @param	string		Separator
	* @param	int			(Optional) Limit
	* @return	String		This string split by the given separator
	*/
	public function split($separator, $limit = 0) {
		if($limit > 0) {
			$out = explode($separator, $this->str, $limit);
		} else {
			$out = explode($separator, $this->str);
		}
		
		$len = count($out);
		for($x = 0; $x < $len; $x++) {
			$out[$x] = new CoreString($out[$x]);
		}
		
		return $out;
	}
	
	/**
	* Trim a string to remove whitepsace either side of string
	*/
	public function trim() {
		return new CoreString(trim($this->str));
	}
	
	//=======================
	// String editing methods (methods not available in str_ library)
	//=======================
	
	
	/**
	 * Truncates a string to a certain length
	 *
	 * @param 	Int 	Limit to truncase up to	[
	 * @param 	String 	The end to append 
	 * @return 	String	Truncated string
	 */
	public function trunc($limit, $ending = '') {
		$text = $this->str;
		$tLen = mb_strlen($text);
		$eLen = mb_strlen($ending);
		
		if($eLen >= $limit) {
			$text = mb_substr($text, 0, $limit);
		} else if($tLen > $limit) {
			$text = mb_substr($text, 0, $limit - $eLen) . $ending;
		} 
		
		return new CoreString($text);
	}
	
	/**
	 * Truncates a string to a certain length
	 *
	 * @param 	Int 	Limit to truncase up to	[
	 * @param 	String 	The end to append 
	 * @return 	String	Truncated string
	 */
	public function truncate($limit, $ending = '') {
		return $this->trunc($limit, $ending);
	}
}