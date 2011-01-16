<?php
	include_once('Object.php');
	
	class Light extends Object {
		var $__toggle = 'TRUE';
		var $__intensity = 1;
		var $__radius = 100;
		var $__color = array(1, 1, 1);
		
		function __construct() {
			
		}
		
		/**
		 * Liga/Desliga a luz
		 * @param Boolean $toggle valor para setar
		 * @return String valor atual em formato string
		 */
		function toggle($toggle = null) {
			if (is_null($toggle)) {
				$toggle = $this->__toggle !== 'TRUE';
			}
		
			$this->__toggle = $toggle ? 'TRUE' : 'FALSE';
			
			return $this->__toggle;
		}
		
		/**
		 * Seta e ou retorna raio da luz
		 * @param Numeric $radius raio da luz
		 * @return Numeric raio do objeto
		 */
		function radius($radius = null) {
			if ($radius) {
				$this->__radius = $radius;
			}
			
			return $this->__radius;
		}
		
		/**
		 * Seta e ou retorna intensidade da esfera
		 * @param Numeric $radius intensidade da esfera
		 * @return Numeric intensidade do objeto
		 */
		function intensity($intensity = null) {
			if ($intensity) {
				$this->__intensity = $intensity;
			}
			
			return $this->__intensity;
		}
		
		/**
		 * Objeto VRML em formato de String
		 * @return String objeto VRML
		 */
		function output() {
			$this->__output = "
							PointLight {
								intensity {$this->__intensity}
								radius {$this->__radius}
								color {$this->__color[0]} {$this->__color[1]} {$this->__color[2]}
								on {$this->__toggle}
							}
			";
						
			return parent::output();
		}
	}
?>