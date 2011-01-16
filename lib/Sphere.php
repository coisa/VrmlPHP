<?php
	include_once('Object.php');
	
	class Sphere extends Object {
		var $type = 'Sphere';
		
		var $__radius = 1;
		var $__size = array('width' => 1, 'height' => 1, 'depth' => 1);
		
		function __construct() {
			
		}
		
		/**
		 * Seta e ou retorna raio da esfera
		 * @param Numeric $radius raio da esfera
		 * @return Numeric raio do objeto
		 */
		function radius($radius = null) {
			if ($radius) {
				$this->__radius = $radius;
			}
			
			$this->size(array('width' => $this->__radius * 2, 'height' => $this->__radius * 2, 'depth' => $this->__radius * 2));
			
			return $this->__radius;
		}
		
		/**
		 * Objeto VRML em formato de String
		 * @return String objeto VRML
		 */
		function output() {
			$this->__output = "
							geometry Sphere {
								radius {$this->__radius}
							}
			";
						
			return parent::output();
		}
	}
?>