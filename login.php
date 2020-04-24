<?php
	header('Access-Control-Allow-Origin: *');
	$json = file_get_contents('php://input');
	//decode json e add em variavel $obj
	
	$obj = json_decode($json, true);
	
	$EmailOuSenha = $obj['usuario'];
	$Senha = $obj['senha'];

	if(isset($EmailOuSenha) && isset($Senha)){
		//tentar substituir pelo require depois
		$servidor = 'localhost';
		$usuario = 'gnew';
		$senha = '123'; 
		$banco = 'newdb';

		$mysqli = mysqli_connect($servidor, $usuario, $senha, $banco);
		
		
		$queryUser1 =  mysqli_query($mysqli, "SELECT * FROM tbl_usuario_app WHERE EMAIL = '$EmailOuSenha' AND SENHA = '$Senha' LIMIT 1");
		if(mysqli_num_rows($queryUser1) != 0){
			if($queryUser = mysqli_fetch_assoc($queryUser1)){
				echo json_encode(array(
					'message' => '1',
					
				));
			}else{
				echo json_encode(array(
					'message' => 'Falha na associacao dos dados',
				));
			}
		}else{
			echo json_encode(array(
					'message' => 'Nenhum usuario cadastrado com esses dados',
				));
		}
		
	}else{
		//return false;
		echo json_encode(array(
					'message' => 'Falha nos campos obrigatorios',
				));
	}

?>
