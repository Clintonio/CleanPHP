<?php
/**
* Cookie class for accessing cookies in an object oriented manner
*
* @author	Clinton Alexander
* @version	1
*/

/**
* Object oriented cookie class
*/
class Cookie {
	/**
	* Name of cookie in the $_COOKIE array
	*/
	private $name;
	/**
	* Value of the cookie
	*/
	private $value;
	/**
	* When the cookie is to expire in UTC time
	*/
	private $expire		= 0;
	/**
	* Path on which the cookie is valid
	*/
	private $path		= "/";
	/**
	* Domain on which the cookie is valid, default HTTP_HOST
	*/
	private $domain;
	/**
	* Whether the cookie is a HTTPS cookie
	*/
	private $secure		= false;
	/**
	* Whether the cookie can be edited by Javascript or HTTP only
	*/
	private $httpOnly	= false;
	
	/**
	* Create a new cookie
	*
	* @param	String		Name of cookie
	*/
	public function __construct($name) {
		$this->name 		= $name;	
		$this->domain		= $_SERVER['HTTP_HOST'];
	}
	
	/**
	* Get the value of this cookie
	*
	* @return	String 		Cookie value
	*/
	public function getValue() {
		$value = "";
		if((!isset($this->value)) && (isset($_COOKIE[$this->name]))) {
			$value = $_COOKIE[$this->name]; 
		} else if(isset($this->value)) {
			$value = $this->value; 	
		}
		
		return new String($value);
	}
	
	/**
	* Set the value for this cookie
	*
	* @param	String		New value
	*/
	public function setValue($value) {
		$value = (string) $value;
		
		$host = $_SERVER['HTTP_HOST'];
		if(($host == 'localhost') || filter_var($host, FILTER_VALIDATE_IP)) {
			setcookie($this->name, $value, false, $this->path, false, $this->secure, $this->httpOnly);
		} else {
			setcookie($this->name, $value, $this->expire, $this->path, 
					  $this->domain, $this->secure, $this->httpOnly);
		} 
		$this->value = $value;	
	}
	
	/**
	* Set the expire time for this cookie
	*
	* @param	Expire time					[default=0]
	* @return	This object for building
	*/
	public function setExpire($expire = 0) {
		$this->expire = (int) $expire;
		
		return $this;
	}
	
	/**
	* Set the path for this cookie
	*
	* @param	Path for this cookie		[default="/"]
	* @return	This object for building
	*/
	public function setPath($path = "/") {
		$this->path = (string) $path;
		
		return $this;	
	}
	
	/**
	* Set the domain for this cookie
	*
	* @param	Domain for this cookie
	* @return	This object for building
	*/
	public function setDomain($domain) {
		$this->domain = (string) $domain;
		
		return $this;	
	}
	
	/**
	* Set whether this cookie is secure or not
	*
	* @param	Boolean		True if this cookie is secure
	* @return	This object for building
	*/
	public function setSecure($secure) {
		$this->secure = (bool) $secure;
		
		return $this;	
	}
	
	/**
	* Set the HTTP only status of this cookie
	*
	* @param	Boolean		True if HTTP only/ no javascript for this cookie
	* @return	This object for building
	*/
	public function setHttpOnly($httpOnly) {
		$this->httpOnly = (bool) $httpOnly;
		
		return $this;	
	}
}

?>
