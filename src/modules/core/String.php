<?php
/**
* A PHP based simple OO string with convenience functions
*
* @author	Clinton Alexander
* @version	1
*/
class String {
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
	* @return	Whether the other value evaluates to the string value of this string
	*/
	public function equals($value) {
		return ((string) $value === $this->str); 	
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
		$tLen = strlen($text);
		$eLen = strlen($ending);
		
		if($eLen >= $limit) {
			$text = substr($text, 0, $limit);
		} else if($tLen > $limit) {
			$text = substr($text, 0, $limit - $eLen) . $ending;
		} 
		
		return new String($text);
	}

}
