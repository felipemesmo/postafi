<?php 
session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('location:index.html');
}
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
	$assunto = "";
	$tipo = anti_injection($_POST['tipo']);
	if($tipo == 0){
		$assunto = anti_injection($_POST['assunto']);
	}	
	$texto = anti_injection($_POST['texto']);
	
	
	
	
	 			//Criamos o comando para inserção do usuário
			$sql = "insert into topico (
			assunto, 
			texto,
			cod_usuario,
			status
			) values (
			'".$assunto."',
			'".$texto."',
			".$_SESSION["cod_usuario"].",
			'Ativo'
			)";

			
			//Prepara e Executa a instrução SQL
			
			$stmt = $conn->prepare($sql);
			$result = $stmt->execute(); 
			$ultimo_topico = $conn->lastInsertId();
			/*Verifica o tipo de ação
				0 - Inclusão de Tópico
				outros - Resposta para tópicos
			*/
			
			if($tipo != 0){
				$Sql_resposta = "insert into conversa(cod, cod_topico) values(".$tipo.",".$ultimo_topico.")";
				$stmt = $conn->prepare($Sql_resposta);
				$result = $stmt->execute(); 
			}
			//echo $Sql_resposta;
			//Pega o cod do usuário recém cadastrado e coloca em uma varável de sessão para estar acessível em outras telas	
			
			header("location:home.php");
			


?>