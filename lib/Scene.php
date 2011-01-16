<?php
	if (!defined('THICKNESS_WALLS')) {
		define('THICKNESS_WALLS', 0.03);
	}

	include_once('Object.php');
	
	class Scene extends Object {
		var $childrens = array();
		
		var $__grid = array();
		var $__walls = array();
		
		var $fragmentSize = null;
		
		function __construct($size = null) {
			self::size($size);
		}
		
		/**
		 * Seta e ou retorna o tamanho do cenario, criando suas paredes
		 * @param Array $size tamanho do cenario
		 * @return Array tamanho do cenario
		 */
		function size($size = null) {
			if ($size) {
				parent::size($size);
				
				$this->setWalls();
			}
			return $this->__size;
		}
		
		/**
		 * Seta posicao das paredes, teto e solo
		 * @return Array objetos de teto, piso, paredes (esquerda e direita respectivamente)
		 */
		function setWalls() {
			$bottom = new Box();
			$bottom->size(array_merge($this->__size, array('height' => THICKNESS_WALLS)));
			$bottom->position(array('y' => (-1) * THICKNESS_WALLS));
			
			$top = clone($bottom);
			$top->position(array('y' => $this->__size['height']));
			
			$left = new Box();
			$left->size(array_merge($this->__size, array('width' => THICKNESS_WALLS)));
			$left->position(array('y' => ($left->__size['height'] / 2), 'x' => (-1) * ($this->__size['width'] / 2) + (THICKNESS_WALLS / 2)));
			
			$right = clone($left);
			$right->position(array('x' => (-1) * $right->__position['x']));
			
			$front = new Box();
			$front->size(array_merge($this->__size, array('depth' => THICKNESS_WALLS)));
			$front->position(array('y' => ($front->__size['height'] / 2), 'z' => (-1) * ($this->__size['depth'] / 2) + (THICKNESS_WALLS / 2)));
			
			$back = clone($front);
			$back->position(array('z' => (-1) * $back->__position['z']));
			
			$light = new Object('Light');
			$light->position(array('y' => $top->__position['y']));
			
			$this->__walls = array($top, $bottom, $left, $right, $front, $back, $light);
			
			return $this->__walls;
		}
		
		/**
		 * Adiciona "filhos" ao cenario
		 * @param mixed $object Object ou string com o tipo do objeto
		 * @return Object filho inserido no cenario
		 */
		function children($object) {
			if (is_string($object)) {
				$object = new $object;
			}
			
			$object->parent = $this;
			
			array_push($this->childrens, &$object);
			
			$this->createGrid();
						
			return $object;
		}
		
		/**
		 * Exclui objeto do cenario
		 * @param Object $children objeto a ser deletado
		 * @return Bool se conseguiu ou nao excluir
		 */
		function delete($children = null) {
			if ($children) {			
				foreach($this->childrens as $index => $child) {
					if ($child === $object) {
						unset($this->childrens[$index]);
						return true;
					}
				}
			}
			return false;
		}
		
		/**
		 * Seta tamanho do fragmento da grade de posicoes de objetos
		 * @param Object $base 
		 * @return Numeric tamanho do fragmento
		 */
		function setFragmentSize($base = null) {
			$childs = $base ? array($base) : $this->childrens;
		
			foreach($childs as $child) {
				$this->fragmentSize = min(max($this->fragmentSize, $child->__size['width'], $child->__size['depth']), $child->__size['width'], $child->__size['depth']);
			}
			$this->fragmentSize = max($this->fragmentSize, 1);
			
			return $this->fragmentSize;
		}
		
		/**
		 * Cria grade para insercao de objetos no cenario
		 * @return Array array bidirecional com o grid para objetos
		 */
		function createGrid() {
			$this->setFragmentSize();
			
			$size = array(ceil($this->__size['width'] / $this->fragmentSize), ceil($this->__size['depth'] / $this->fragmentSize));
			
			for($i = 0; $i < $size[0]; $i++) {
				for($j = 0; $j < $size[1]; $j++) {
					$this->__grid[$i][$j] = true;
				}
			}
			
			return $this->__grid;
		}
		
		/**
		 * Verifica se o objeto serve na posicao apontada
		 * @param Array $position coordenadas para teste
		 * @param Array $size tamanho do objeto
		 * @return Boolean retorna se o objeto com essas dimensoes serve no ponto
		 */
		function checkPosition($position, $size) {
			$grid = $this->__grid;
			$found = true;
			
			for($i = $position['x']; $i < $size['width'] + $position['x']; $i++) {
				for($j = $position['z']; $j < $size['depth'] + $position['z']; $j++) {
					if (!$grid[$i][$j]) {
						$found = false;
						unset($grid[$i][$j]);
						break;
					}
				}
				if (!$found) break;
			}
			
			if ($found) {
				$this->__grid = $grid;
			}
			
			return $found;
		}
		
		/**
		 * Sortear posicao desocupada para o objeto
		 * @param Object $object objeto a ser sorteado a posicao
		 * @return Array posicao final do objeto
		 */
		function sortPosition($object = null) {
			if (!$object) {
				return false;
			}
			
			$i = 0;
			
			$size = array(
				'width' => ceil($object->__size['width'] / $this->fragmentSize),
				'depth' => ceil($object->__size['depth'] / $this->fragmentSize)
			);
			
			do {
				++$i;
			
				$position = array('x' => rand(0, sizeof($this->__grid) - 1));
				
				if (!$this->__grid[$position['x']]) {
					continue;
				}
				
				$position['z'] = rand(0, sizeof($this->__grid[$position['x']]) - 1);
				
				if (!$this->__grid[$position['x']][$position['z']]) {
					continue;
				}
				
				if ($this->checkPosition($position, $size)) {
					$found = true;
					break;
				}
				
				if ($this->checkPosition($position, array('width' => $size['depth'], 'depth' => $size['width']))) {
					$object->rotatation('y', 90);
					$found = true;
					break;
				}
				
				if ($found) {
					$check = $this->__grid;
				} else {
					unset($check[$position['x']][$position['z']]);
				}
			} while(!$found && !empty($check));
			
			if (!$found) {
				return !$this->delete($object);
			}
			
			$position['y'] = $object->__size['height'] / 2 + THICKNESS_WALLS;
			
			return $object->position($position);
		}
		
		/**
		 * Sortei posicao de todos os objetos do cenario
		 */
		function sortPositions() {
			foreach($this->childrens as $child) {
				$this->sortPosition($child);
			}
		}
		
		/**
		 * Cenario VRML em formato de String
		 * @return String objeto VRML
		 */
		function output($header = true) {
			$output = $header ? $this->header . "\n" : '';
			
			# Navigation e viewpoint fixo por enquanto
			$output .= "
				NavigationInfo {
					headlight TRUE
					type [\"WALK\", \"FLY\"]
				}
				Viewpoint {
					description \"Viewpoint\"
					jump TRUE
					position " . $this->__size['width'] / 2 ." 5 " . $this->__size['depth'] / 2 ."
				}
				Transform {
					translation " . $this->__size['width'] / 2 . " 0 " . $this->__size['depth'] / 2 . "
					
					children [
			";
			
			foreach($this->__walls as $wall) {
				if ($this->__texture) {
					$wall->texture($this->__texture, true);
				}
				
				$output .= $wall->output();
			}
			
			$output .= "
					]
				}
			";
			
			foreach($this->childrens as $child) {
				$output .= $child->output();
			}
			
			return $output;
		}
	}
?>