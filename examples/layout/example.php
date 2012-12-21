<?php
/**
* An example file for the Layout class
*
* @author	Clinton Alexander
* @version	1
*/

require_once('../../CleanPHP.php');

CleanPHP::import('layout.Layout');

$layout = new Layout(new File('layout.php'));
$layout->addVariable('src', htmlspecialchars(file_get_contents('example.php')));

$layout->display();

?>
