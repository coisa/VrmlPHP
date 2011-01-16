<em>
	<pre>
/**
 * Universidade do Vale do Rio dos Sinos
 * Realidade Virtual - 2010/1
 * Prof. Fernando Marson
 *
 * @author Felipe Sayao Lobato Abreu
 * @author Jonas Horst Regina
 *
 * @description Biblioteca PHP para geracao de VRML 3D
 *
 * @version 1.0b
 */
	</pre>
</em>
<?php
	if (!empty($_POST)):
		include_once("conf/core.conf.php");
		
		foreach($_POST['scene'] as $index => $params) {
			$name = "wrl/scene_{$index}.wrl";
			
			$scene = new Scene(array('width' => $params['size']['width'],'height' => $params['size']['height'],'depth' => $params['size']['depth']));
			$scene->texture("img/scene_{$index}.jpg", true);
			
			for($i = 0; $i < $params['sphere']['quantity']; $i++) {
				$object = $scene->children('Sphere');
				$object->texture('img/sphere.jpg', true);
				$object->radius(rand(1, $params['sphere']['max_side']));
			}
			
			for($i = 0; $i < $params['box']['quantity']; $i++) {
				$object = $scene->children('Box');
				$object->texture('img/box.jpg', true);
				$object->size(array('width' => rand(1, $params['box']['max_side']), 'height' => rand(1, $params['box']['max_side']), 'depth' => rand(1, $params['box']['max_side'])));
			}
			
			for($i = 0; $i < $params['cone']['quantity']; $i++) {
				$object = $scene->children('Cone');
				$object->texture('img/cone.jpg', true);
				$object->size(array('height' => rand(1, $params['cone']['max_side'])));
				$object->radius(rand(1, $params['sphere']['max_side']));
			}
			
			$portal = $scene->children($params['portal']['type']);
			$portal->color(array(1, 0, 0));
			$portal->link('scene_' . (isset($_POST['scene'][$index + 1]) ? $index + 1 : 0) . '.wrl', true);
			
			$scene->sortPositions();
			
			$output = $scene->output();
			
			$file = fopen($name, 'w');
			fwrite($file, $output);
			fclose($file);
		}
		
		$zip = new ZipArchive();
		
		if ($zip->open("wrl/vrml.zip", ZIPARCHIVE::CREATE) !== true) {
			exit("Nao foi possivel criar um arquivo zip");
		}
		
		$files = glob('wrl/*.wrl');
		
		foreach($files as $file) {
			$zip->addFile($file);
		}
		
		$files = glob('wrl/img/*.jpg');
		
		foreach($files as $file) {
			$zip->addFile($file);
		}
		
		
		echo '<p><a href="wrl/vrml.zip">Download Zip File with wrl\'s</a></p>';
		
		/*header("Content-type: application/x-vrml");
		include_once('wrl/scene_0.wrl');*/
	else:
?>

<html>
	<head>
		<title>Realidade Virtual - VRML - Felipe Abreu + Jonas Horst</title>
	</head>
	<body>
		<form name="scenes" method="post">
			<fieldset>
				<legend>Parametros Mapa 1</legend>
				
				<div class="object">
					<label for="scene[0][size][width]">Largura</label>
					<input type="text" id="scene[0][size][width]" name="scene[0][size][width]" value="100" />
					
					<label for="scene[0][size][height]">Altura</label>
					<input type="text" id="scene[0][size][height]" name="scene[0][size][height]" value="20" />
					
					<label for="scene[0][size][depth">Profundidade</label>
					<input type="text" id="scene[0][size][depth]" name="scene[0][size][depth]" value="100" />
					
					<fieldset>
						<legend>Objetos</legend>
						<fieldset>
							<legend>Esferas</legend>
							<div class="spheres">
								<label for="scene[0][sphere][quantity]">Quantidade</label>
								<input type="text" id="scene[0][sphere][quantity]" name="scene[0][sphere][quantity]" value="3" />
								
								<label for="scene[0][sphere][max_side]">Raio Maximo</label>
								<input type="text" id="scene[0][sphere][max_side]" name="scene[0][sphere][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Caixas</legend>
							<div class="spheres">
								<label for="scene[0][box][quantity]">Quantidade</label>
								<input type="text" id="scene[0][box][quantity]" name="scene[0][box][quantity]" value="3" />
								
								<label for="scene[0][box][max_side]">Tamanho maximo de um lado (altura, largura ou profundidade)</label>
								<input type="text" id="scene[0][box][max_side]" name="scene[0][box][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Cones</legend>
							<div class="spheres">
								<label for="scene[0][cone][quantity]">Quantidade</label>
								<input type="text" id="scene[0][cone][quantity]" name="scene[0][cone][quantity]" value="3" />
								
								<label for="scene[0][cone][max_side]">Tamanho maximo de um lado (altura, raio)</label>
								<input type="text" id="scene[0][cone][max_side]" name="scene[0][cone][max_side]" value="3" />
							</div>
						</fieldset>
						
						<label for="scene[0][portal][type]">Objeto usado para o portal para o proximo arquivo <small>Este objeto tera cor vermelha</small></label>
						<select id="scene[0][portal][type]" name="scene[0][portal][type]">
							<option value="Sphere">Esfera</option>
							<option value="Box">Caixa</option>
							<option value="Cone">Cone</option>
						</select>
					</fieldset>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>Parametros Mapa 2</legend>
				
				<div class="object">
					<label for="scene[1][size][width]">Largura</label>
					<input type="text" id="scene[1][size][width]" name="scene[1][size][width]" value="100" />
					
					<label for="scene[1][size][height]">Altura</label>
					<input type="text" id="scene[1][size][height]" name="scene[1][size][height]" value="20" />
					
					<label for="scene[1][size][depth">Profundidade</label>
					<input type="text" id="scene[1][size][depth]" name="scene[1][size][depth]" value="100" />
					
					<fieldset>
						<legend>Objetos</legend>
						<fieldset>
							<legend>Esferas</legend>
							<div class="spheres">
								<label for="scene[1][sphere][quantity]">Quantidade</label>
								<input type="text" id="scene[1][sphere][quantity]" name="scene[1][sphere][quantity]" value="3" />
								
								<label for="scene[1][sphere][max_side]">Raio Maximo</label>
								<input type="text" id="scene[1][sphere][max_side]" name="scene[1][sphere][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Caixas</legend>
							<div class="spheres">
								<label for="scene[1][box][quantity]">Quantidade</label>
								<input type="text" id="scene[1][box][quantity]" name="scene[1][box][quantity]" value="3" />
								
								<label for="scene[1][box][max_side]">Tamanho maximo de um lado (altura, largura ou profundidade)</label>
								<input type="text" id="scene[1][box][max_side]" name="scene[1][box][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Cones</legend>
							<div class="spheres">
								<label for="scene[1][cone][quantity]">Quantidade</label>
								<input type="text" id="scene[1][cone][quantity]" name="scene[1][cone][quantity]" value="3" />
								
								<label for="scene[1][cone][max_side]">Tamanho maximo de um lado (altura, raio)</label>
								<input type="text" id="scene[1][cone][max_side]" name="scene[1][cone][max_side]" value="3" />
							</div>
						</fieldset>
						
						<label for="scene[1][portal][type]">Objeto usado para o portal para o proximo arquivo <small>Este objeto tera cor vermelha</small></label>
						<select id="scene[1][portal][type]" name="scene[1][portal][type]">
							<option value="Sphere">Esfera</option>
							<option value="Box">Caixa</option>
							<option value="Cone">Cone</option>
						</select>
					</fieldset>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>Parametros Mapa 3</legend>
				
				<div class="object">
					<label for="scene[2][size][width]">Largura</label>
					<input type="text" id="scene[2][size][width]" name="scene[2][size][width]" value="100" />
					
					<label for="scene[2][size][height]">Altura</label>
					<input type="text" id="scene[2][size][height]" name="scene[2][size][height]" value="20" />
					
					<label for="scene[2][size][depth">Profundidade</label>
					<input type="text" id="scene[2][size][depth]" name="scene[2][size][depth]" value="100" />
					
					<fieldset>
						<legend>Objetos</legend>
						<fieldset>
							<legend>Esferas</legend>
							<div class="spheres">
								<label for="scene[2][sphere][quantity]">Quantidade</label>
								<input type="text" id="scene[2][sphere][quantity]" name="scene[2][sphere][quantity]" value="3" />
								
								<label for="scene[2][sphere][max_side]">Raio Maximo</label>
								<input type="text" id="scene[2][sphere][max_side]" name="scene[2][sphere][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Caixas</legend>
							<div class="spheres">
								<label for="scene[2][box][quantity]">Quantidade</label>
								<input type="text" id="scene[2][box][quantity]" name="scene[2][box][quantity]" value="3" />
								
								<label for="scene[2][box][max_side]">Tamanho maximo de um lado (altura, largura ou profundidade)</label>
								<input type="text" id="scene[2][box][max_side]" name="scene[2][box][max_side]" value="3" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Cones</legend>
							<div class="spheres">
								<label for="scene[2][cone][quantity]">Quantidade</label>
								<input type="text" id="scene[2][cone][quantity]" name="scene[2][cone][quantity]" value="3" />
								
								<label for="scene[2][cone][max_side]">Tamanho maximo de um lado (altura, raio)</label>
								<input type="text" id="scene[2][cone][max_side]" name="scene[2][cone][max_side]" value="3" />
							</div>
						</fieldset>
						
						<label for="scene[2][portal][type]">Objeto usado para o portal para o proximo arquivo <small>Este objeto tera cor vermelha</small></label>
						<select id="scene[2][portal][type]" name="scene[2][portal][type]">
							<option value="Sphere">Esfera</option>
							<option value="Box">Caixa</option>
							<option value="Cone">Cone</option>
						</select>
					</fieldset>
				</div>
			</fieldset>
			
			<input type="submit" value="Gerar Arquivos" />
		</form>
	</body>
</html>

<?php endif; ?>