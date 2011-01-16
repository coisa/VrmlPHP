<?php
	include_once('Object.php');
	
	class Box extends Object {
		var $type = 'Box';
		
		function __construct() {
		
		}
		
		/**
		 * Objeto VRML em formato de String
		 * @return String objeto VRML
		 */
		function output() {
			$this->__output = "
							geometry Box {
								size {$this->__size['width']} {$this->__size['height']} {$this->__size['depth']}
							}
			";
						
			return parent::output();
		}
	}
?>