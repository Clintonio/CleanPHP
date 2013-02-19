<?php
/**
* A PHP template class that loads templates written in PHP in an object
* oriented manner. An example can be found in the examples folder.
*
* @author	Clinton Alexander
* @version	1
*/

CleanPHP::import('io.File');
CleanPHP::import('io.FileNotFoundException');

/**
* PHP Templating layout class
*
* @package	layout
*/
class Layout implements ArrayAccess {
	/**
	* The file for this layout
	*/
	private $file;
	/**
	* Variables for this layout
	*/
	private $variables = array();
	
	/**
	* Create a new layout with the given file
	*
	* @param	File	file	The layout file
	*/
	public function __construct(File $file) {
		$this->file = $file;
	}
	
	/**
	* Add a new variable to the layout symbol table. Reserved names:
	* _file, standard PHP reserved names.
	*
	* @param	string	name	Name of variable
	* @param	mixed	value	Value of the variable
	*/
	public function addVariable($name, $value) {
		$this->variables[(string) $name] = $value;
	}
	
	/**
	* Add an array of variables. Variables with the same key will use the newer value
	*
	* @param	array	variables	An array of key value pairs following addVariable parameters
	*/
	public function addVariables(array $variables) {
		$this->variables = array_merge($this->variables, $variables);
	}
	
	/**
	* Check if a variable has been set already
	*
	* @param	string	name	The name of the variable you wish to see if has been set
	* @return	boolean			True if the variable with the given name has been set
	*/
	public function isVariableSet($name) {
		return isset($this->variables[$name]);
	}
	
	/**
	* Output this template by including the template
	*
	* @throws	FileNotFoundException	If the file cannot be found
	*/
	public function display() {
		// This extracts the array into the current symbol table, meaning
		// that the layout doesn't have to refer to $variables.
		extract($this->variables, EXTR_SKIP);
		// To prevent bad practice, we erase the possibility of referring
		// to the variables array
		$this->variables = NULL;
		// Similarly we do the same with file
		$_file = (string) $this->file;
		$this->file = NULL;
		if((include($_file)) != 1) {
			throw new FileNotFoundException('Could not open layout');
		}
	}
	
	//================
	// Array Access Methods
	//================ 
	
	/**
	* Alias for isVariableSet, used in ArrayAccess
	*/
	public function offsetExists($offset) { 
		return $this->isVariableSet($offset);
	} 
	
	/**
	* Alias for addVariable, used in array access
	*/
	public function offsetSet($offset, $value) { 
		$this->addVariable($offset, $value);
	} 

	/**
	* Not supported, required for interface, this may change, do not rely on this behaviour
	*/
	public function offsetGet($offset) { 
		// Not supported
	} 

	/**
	* Not supported, required for interface, this may change, do not rely on this behaviour
	*/
	public function offsetUnset($offset) { 
		// Not supported
	}
}
