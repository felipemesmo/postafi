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
	
	
	$cod_topico = anti_injection($_GET['cod']);
	
	
	
	
	 			//Criamos o comando para inserção do usuário
			$sql = "update topico set status = 'Apagado' where cod = ".$cod_topico.";";

			
			//Prepara e Executa a instrução SQL
			//echo $sql;
			$stmt = $conn->prepare($sql);
			$result = $stmt->execute(); 
			
			header("location:home.php");


?>