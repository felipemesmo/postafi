<?php 
session_start();
//Define a TimeZone para ajustar o horário de verão
date_default_timezone_set("Brazil/East");

//Importa o arquivo de conexão com o banco de dados
include('conexao.php');

/*
Função Anti Injection
---------------------
Essa função retira dos textos enviados através dos formulários as palavras-chaves que possam ser utilizadas a fim de ter acesso irrestrito a outras informações
por meio de injeção de instruções de SQL
*/
	function anti_injection($sql){
	// remove palavras que contenham sintaxe sql
		//$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
		$sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i","",$sql);
		$sql = trim($sql);//limpa espaços vazio
		$sql = strip_tags($sql);//tira tags html e php
		$sql = addslashes($sql);//Adiciona barras invertidas a uma string
		return $sql;
	}
	
	//Como utilizar a função
	$email = anti_injection($_POST['email']);
	$senha = anti_injection($_POST['senha']);
	
	//Executa um Select para verificar se o usuário existe na base de dados verificando se o usuário e a senha conferem		
	$sql = "Select * from usuarios where email = '".$email."' and senha='".$senha."'";
	/*
		Select traz uma lista de registros em varias linhas que são agrupadas em um array. Exemplo Abaixo.
		0 linha - {
			usuario = Nome
			Senha = Senha
		},
		1 Linha - {
			usuario = Nome
			Senha = Senha
		}
		
	*/
	echo $sql;
	$result = $conn->query($sql);
	
	//Criamos uma variavel de controle com o valor 0 - equivalente a não tem cadastro
	$usuario_existe = 0;	   

	//Verifica o array linha por linha e adiciona a uma variável
		foreach($result as $row){
			//Se o select foi válido e encontrou linhas de registro com e-mail e a senha digitados, significa que o usuário e a senha estão corretos e logo ele existe.
			$usuario_existe = 1;	   
			//Criamos uma variável de sessão para armazenar o código do usuário logado e em todas as páginas podermos identifica-lo
			$_SESSION["cod_usuario"] = $row['cod'];
		}
		
	/*
		Verifica se o usuário existe ou não	
		caso exista:
			- O codigo do usuário é armazenado em uma variável de sessão
			- o usuário é levado para a tela principal do sistema
		caso não exista:
			- é mostrado um erro de usuário ou senha inválidos com um link de retorno para a tela de login

	*/
	if($usuario_existe != 0){
		header("location:home.php");
	}else{
		//$_SESSION["erro"] = "Usuário e/ou senha estão incorretos!";
		echo "<p>Usuário e/ou senha estão incorretos!</p>";
		echo "<p><a href='login.html'>Voltar</a></p>";
	}	
?>