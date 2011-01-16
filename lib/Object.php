<?php
	class Object {
		var $header = "#VRML V2.0 utf8";
	
		var $type = 'Object';
	
		var $__output = '';
		
		var $__size = array('width' => 5, 'height' => 5, 'depth' => 5);
		var $__position = array('x' => 0, 'y' => 0, 'z' => 0);
		var $__rotation = array('x' => 0, 'y' => 0, 'z' => 0, 'r' => 0);
		
		var $__color = null;
		var $__texture = null;
		var $__link = null;
		
		var $parent = null;
		
		static $__indent = 0;
		
		/**
		 * Construtor que gera um objeto especifico caso passado um tipo
		 * @return mixed objeto derivado de Object
		 */
		function __construct($type = null) {
			if ($type) {
				return new $type;
			}
		}
		
		/**
		 * Seta e ou retorna tamanho do objeto
		 * @param Array $size tamanhos de width, height, depth
		 * @return Array array com indices width, height, depth
		 */
		function size($size = array()) {
			if (!empty($size)) {
				$this->__size = array_merge($this->__size, $size);
			}
			
			return $this->__size;
		}
		
		/**
		 * Seta e ou retorna posicao do objeto
		 * @param Array $position posicao de x, y, z
		 * @return Array array com indices x, y, z
		 */
		function position($position = array()) {
			if (!empty($position)) {
				$this->__position = array_merge($this->__position, $position);
			}
			
			return $this->__position;
		}
		
		/**
		 * Seta e ou retorna a rotacao do objeto
		 * @param String $axis eixo pelo qual ele vai rotacionar
		 * @param Numeric $degrees valor em graus que o eixo deve rotacionar
		 * @return Array retorna a rotacao atual do objeto
		 */
		function rotatation($axis = null, $degrees = 90) {
			if ($axis) {
				$radian = 0.0174532925;
			
				$this->__rotation = array_merge($this->__rotation, array(strtolower($axis) => 1, 'r' => $radian * $degrees));
			}
			
			return $this->__rotation;
		}
		
		/**
		 * Seta e ou retorna cor do objeto
		 * @param Array $color array contendo valores de red, green e blue (ex.: array(0, 1, 0) para verde)
		 * @return Array valores red, green e blue do objeto
		 */
		function color($color = array()) {
			if (!empty($color)) {
				$this->__color = $color;
			}
			
			return $this->__color;
		}
		
		/**
		 * Seta e ou retorna textura do objeto
		 * @param String $path caminho para a textura do objeto
		 * @return String caminho da textura do objeto
		 */
		function texture($path = null, $force = false) {
			if ($path && ($force || file_exists($path))) {
				$this->__texture = $path;
			}
			
			return $this->__texture;
		}
		
		/**
		 * Seta e ou retorna ancora do objeto
		 * @param String $path caminho para o wrl de ancora
		 * @return String caminho do wrl
		 */
		function link($path = null, $force = false) {
			if ($path && ($force || file_exists($path))) {
				$this->__link = $path;
			}
			
			return $this->__link;
		}
		
		/**
		 * Altera e retorna a indentacao para o output
		 * @param Integer $increment Incremento da identacao
		 * @return Integer indentacao da saida
		 */
		function indent($increment = 0) {
			$this->__indent += $increment;
			
			return $this->__indent;
		}
		
		/**
		 * Objeto VRML em formato de String
		 * @return String objeto VRML
		 */
		function output($header = false) {
			$output = $header ? $this->header . "\n" : '';
		
			$output .= "
				Transform {
					translation {$this->__position['x']} {$this->__position['y']} {$this->__position['z']}
					rotation {$this->__rotation['x']} {$this->__rotation['y']} {$this->__rotation['z']} {$this->__rotation['r']}
					
					children [
			";
			
			if ($this->__link) {
				$output .= "
						Anchor {
							url [\"{$this->__link}\"]
							
							children [
								Shape {
				";
			} else {
				$output .= "
						Shape {
				";
			}
			
			$indent = $this->__link ? "\t\t" : '';
			
			if (!empty($this->__color) || !empty($this->__texture)) {
				$output .= "
				{$indent}			appearance Appearance {
				";
			
				if (!empty($this->__color)) {
					$output .= "
					{$indent}			material Material {
					{$indent}				diffuseColor {$this->__color[0]} {$this->__color[1]} {$this->__color[2]}
					{$indent}			}
					";
				}
				
				if (!empty($this->__texture)) {
					$output .= "
					{$indent}			texture ImageTexture {
					{$indent}				url [\"{$this->__texture}\"]
					{$indent}			}
					";
				}
					
				$output .= "
				{$indent}			}
				";
			}
			
			$output .= str_replace("\n", "\n{$indent}", "
							{$this->__output}
			");
			
			if ($this->__link) {
				$output .= "
								}
							]
				";
			}
			
			$output .= "
						}
					]
				}
			";
			
			return str_replace(array("\n\t\t\t\t", "\n\n"), "\n", $output);
		}
		
	}
?>