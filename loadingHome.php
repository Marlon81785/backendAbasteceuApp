<?php
	$json = file_get_contents('php://input');
	
	$obj = json_decode($json, true);
	
	$servidor = 'localhost';
	$usuario = 'gnew';
	$senha = '123';
	$banco = 'newdb';

	$mysqli = mysqli_connect($servidor, $usuario, $senha, $banco);

	$queryListarPostos1 = mysqli_query($mysqli, "SELECT tbl_posto.NOME_POSTO, PRECO FROM tbl_posto, tbl_precos WHERE tbl_posto.COD_POSTO = tbl_precos.COD_POSTO");

	$lista = array();

	for($i=0;$i<20;$i++){
		if($queryUser = mysqli_fetch_assoc($queryListarPostos1)){
			$lista[] = $queryUser['NOME_POSTO'];
			$listaPreco[] = $queryUser['PRECO'];		
		}
	}

	echo json_encode(array(
		"p0" => [$lista[0], $listaPreco[0]],
		"p1" => [$lista[0], $listaPreco[0]],
		"p2" => [$lista[1], $listaPreco[1]],
		"p3" => [$lista[2], $listaPreco[2]],
		"p4" => [$lista[3], $listaPreco[3]],
		"p5" => [$lista[4], $listaPreco[4]],
		"p6" => [$lista[5], $listaPreco[5]],
		"p7" => [$lista[6], $listaPreco[6]],
		"p8" => [$lista[7], $listaPreco[7]],
		"p9" => [$lista[8], $listaPreco[8]],
		"p10" => [$lista[9], $listaPreco[9]],
		"p11" => [$lista[10], $listaPreco[10]],
		"p12" => [$lista[11], $listaPreco[11]],
		"p13" => [$lista[12], $listaPreco[12]],
		"p14" => [$lista[13], $listaPreco[13]],
		"p15" => [$lista[14], $listaPreco[14]],
		"p16" => [$lista[15], $listaPreco[15]],
		"p17" => [$lista[16], $listaPreco[16]],
		"p18" => [$lista[17], $listaPreco[17]],
		"p19" => [$lista[18], $listaPreco[18]],
		"p20" => [$lista[19], $listaPreco[19]],

	));
?>
