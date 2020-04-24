<?php
	header("Acess-Control-Allow-Origin: *");
	$json = file_get_contents('php://input');
	$obj = json_decode($json, true);
	

	//esta variavel representa o estado a ser retornado ao aplicativo com seu respectivo codigo
	// 1 = falha ao conectar ao banco 
	// 2 = conectado com sucesso ao banco,
	// 3 = 
	//

    $state = 0;
    $servidor = 'localhost';
    $usuario = 'gnew';
    $senha = '123';
    $banco = 'newdb';
    $mysqli = mysqli_connect($servidor, $usuario, $senha, $banco);
    if(!$mysqli){ $state = '1'; } else { $state = '2'; }
    //--------------------------------------------=----------------//

    //decode json e add em variavel $obj ou seja meu objeto json
    $json = file_get_contents("php://input");

    $obj = json_decode($json, true);
    //informações a serem inseridas do usuario durante o procedimento de cadastro
    $nome_usuario = $obj['nome'];
    $username = $obj['nome'];
    $tipo_usuario = 4;
    $usuario_senha = $obj['Senha'];
    $email = $obj['email'];
    $telefone = $obj['telefone'];
    $cpf = $obj['cpf'];
    $data_nascimento = $obj['dataNasc'];
    $cep = $obj['cep'];
    $cod_estado = 0;
    $cod_cidade = 0;
    $cod_bairro = 0;
    $cod_rua = 0;
    $num_residencia = $obj['numero'];
    //

    //usuario entrou com estas informações de endereço que serao usadas nos selects pra buscar os codigos das referencias
    $rua = $obj['rua'];
    $bairro = $obj['bairro'];
    $cidade = $obj['cidade'];
    $estado = $obj['uf'];
    //
    if(isset($nome_usuario) && isset($username) && isset($usuario_senha) && isset($email) && isset($telefone) 
	    && isset($cpf) && isset($data_nascimento) && isset($cep) && isset($rua) && isset($bairro) && isset($cidade) &&
	    isset($estado)) {
   		//parte do estado -- estou supondo que teremos no banco de dados todas as cidades ddo brasil cadastradas
    	$query_estados = mysqli_query($mysqli, "SELECT COD_ESTADO FROM tbl_estados WHERE SIGLA = '$estado'");
    	$informacoes_estados = mysqli_fetch_assoc($query_estados);
    	$cod_estado = $informacoes_estados['COD_ESTADO'];

   		//parte das cidades -- estou supondo que teremos no banco de dados todas as cidades ddo brasil cadastradas
    	$query_cidades = mysqli_query($mysqli, "SELECT COD_CIDADE FROM tbl_cidades WHERE NOME_CIDADE = '$cidade'");
    	$informacoes_cidades = mysqli_fetch_assoc($query_cidades);
    	$cod_cidade = $informacoes_cidades['COD_CIDADE'];
       	//para visualizar to o array echo implode(" ",$informacoes_cidades);


    	//bairros
    	$query_bairros = mysqli_query($mysqli, "SELECT COD_BAIRRO FROM tbl_bairros WHERE NOME_BAIRRO = '$bairro'");
    	$informacoes_bairros = mysqli_fetch_assoc($query_bairros);
    
    	//echo($informacoes_bairros['COD_BAIRRO']);
    	if($informacoes_bairros['COD_BAIRRO'] == null){
        	//echo("nao existe esse bairro na tabela<br>");
			//echo json_encode(array('message' => 'Nao existe este bairro na tabela',));
			$state = '3';
			if($validaInsert = mysqli_query($mysqli, "INSERT INTO tbl_bairros (NOME_BAIRRO) VALUES ('$bairro')")){

				//echo("gravado com sucesso o novo bairro<br>");
				//echo json_encode(array('message' => 'Gravado com sucesso o novo bairro',));
				$state = '4';
				$query_bairros = mysqli_query($mysqli, "SELECT COD_BAIRRO FROM tbl_bairros WHERE NOME_BAIRRO = '$bairro'");
				$informacoes_bairros = mysqli_fetch_assoc($query_bairros);
				$cod_bairro = $informacoes_bairros['COD_BAIRRO'];
			}else{
				//echo("falha na gravacao do bairro<br>");
				//echo json_encode(array('message' => 'Falha na grava��o do bairro',));
				$state = '5';
			}

    	}else{
			//echo("existe este bairro<br>");
			//echo json_encode(array('message' => 'Existe este bairro',));
			$state = '6';
			$cod_bairro = $informacoes_bairros['COD_BAIRRO'];
    	}

    	//ruas
    	$query_ruas = mysqli_query($mysqli, "SELECT COD_RUA FROM tbl_ruas WHERE NOME_RUA = '$rua'");
    	$informacoes_ruas = mysqli_fetch_assoc($query_ruas);

    	if($informacoes_ruas['COD_RUA'] == null){
			//echo("nao existe essa rua na tabela<br>");
			//echo json_encode(array('message' => 'Não existe essa rua na tabela',));
			$state = '7';
			if($validaInsert = mysqli_query($mysqli, "INSERT INTO tbl_ruas (NOME_RUA) VALUES ('$rua')")){
				//echo("gravado com sucesso a nova rua no banco<br>");
				//echo json_encode(array('message' => 'Gravado com sucesso a nova rua no banco',));
				$state = '8';
				$query_ruas = mysqli_query($mysqli, "SELECT COD_RUA FROM tbl_ruas WHERE NOME_RUA = '$rua'");
				$informacoes_ruas = mysqli_fetch_assoc($query_ruas);
				$cod_rua = $informacoes_ruas['COD_RUA'];

			}else{
				//echo("falha na gravacao da nova rua no banco<br>");
				//echo json_encode(array('message' => 'Falha na gravação da nova rua',));
				$state = '9';
			}
		

    	}else{
			//echo("existe esta rua<br>");
			//echo json_encode(array('message' => 'Existe essa tabela',));
			$state = '10';
			$cod_rua = $informacoes_ruas['COD_RUA'];
    	}


    	//verificar se usuario já existe
    	$verificarExistenciadeUsuario = mysqli_query($mysqli, "SELECT EMAIL, CPF FROM tbl_usuario_app WHERE EMAIL = '$email' OR CPF = '$cpf'");
    	if(mysqli_num_rows($verificarExistenciadeUsuario) != 0){
        	//echo json_encode(array('message' => 'ERRO: Usuario já existente no banco de dado',));
			$state = '11';
		}else{
        	//finalmente gravar novo usuario no banco de dados! (tbl_usuario_app)
        	$queryAddUsuarioApp = "INSERT INTO tbl_usuario_app 
        	(NOME_USUARIO, USERNAME, COD_TIPO_USUARIO, SENHA, EMAIL, TELEFONE, CPF, DATA_NASC, CEP, COD_ESTADO, COD_CIDADE, COD_BAIRRO, COD_RUA, NUM_RESIDENCIA) VALUES 
        	('$nome_usuario', '$username', '$tipo_usuario', '$usuario_senha', '$email', '$telefone', '$cpf', '$data_nascimento', '$cep', '$cod_estado', '$cod_cidade', '$cod_bairro', '$cod_rua', '$num_residencia')";
        
        
        	if($validarUsuario = mysqli_query($mysqli, $queryAddUsuarioApp)){
				$state = '12';
				//echo json_encode(array('message' => 'gravado com sucesso novo usuario do aplicativo !!',));
				//echo json_encode(array('message' => '$state',));
        	}else{
            	//echo json_encode(array('message' => 'falha na gravacao do novo usuario do aplicativo',));
				$state = '13';
			}
			
		}

	

	}
	else
	{
	    $state = '14';
    }
    echo json_encode(array('message' => $state));
    /*
    echo $obj['nome'];
    echo $obj['dataNasc'];
    echo $obj['cpf'];
    echo $obj['Senha'];
    echo $obj['email'];
    echo $obj['telefone'];
    echo $obj['cep'];
    echo $obj['rua'];
    echo $obj['numero'];
    echo $obj['bairro'];
    echo $obj['cidade'];
    echo $obj['uf'];
     */

?>
