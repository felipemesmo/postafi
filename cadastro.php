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
	$confirmacao_senha = anti_injection($_POST['confirmacao_senha']);
	
	//Verifica se a senha confere com a confirmação.
	if($senha == $confirmacao_senha){
					
		//Executa um Select para verificar se o usuário já existe na base de dados verificando se o usuário e a senha conferem		
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
		$result = $conn->query($sql);
		
		//Criamos uma variavel de controle com o valor 0 - equivalente a não tem cadastro
		$usuario_existe = 0;	   

		//Verifica o array linha por linha e adiciona a uma variável
			foreach($result as $row){
				//Se o select foi válido e encontrou linhas de registro com e-mail e a senha digitados, significa que o usuário e a senha estão corretos e logo ele existe.
				$usuario_existe = 1;	   
			}
			
		/*
			Verifica se o usuário existe ou não	
			caso exista:
				- é mostrado uma mensagem de erro dizendo que o usuário já existe
				- o usuário é levado de volta para a tela de cadastro 
			caso não exista:
				- o usuário é levado para a tela principal

		*/

		if($usuario_existe == 0){
			//Criamos o comando para inserção do usuário
			$sql = "insert into usuarios (
			email, 
			senha
			) values (
			'".$email."',
			'".$senha."')";
			//Prepara e Executa a instrução SQL
			
			$stmt = $conn->prepare($sql);
			$result = $stmt->execute(); 
			//Pega o cod do usuário recém cadastrado e coloca em uma varável de sessão para estar acessível em outras telas	
			$_SESSION["cod_usuario"] = $conn->lastInsertId();
			if($conn->lastInsertId() != '0'){
				header("location:home.php");
			}
			
		}else{
			
			//$_SESSION["erro"] = "Usuário e/ou senha estão incorretos!";
			echo "<p>Usuário já esta cadastrado! Verifique o e-mail e tente novamente.</p>";
			echo "<p><a href='cadastro.html'>Voltar</a></p>";
		}	
	}else{
		echo "<p>Senha não confere com a confirmação!Volte e digite novamente.</p>";
		echo "<p><a href='cadastro.html'>Voltar</a></p>";
	}
?>