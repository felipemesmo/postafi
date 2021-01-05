<?php 
session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('location:index.html');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - POSTAFI</title>

    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/home.css">

</head>
<body>
    <header>
            <a href="index.html"><img src="./images/back-arrow.svg" alt="Voltar"></a>
            <a class="logo-postafi"><img src="./images/logo.svg" alt="Postafi"></a>
            <a class="btn-primary new-topic" href="topico.php"><img src="./images/new-topic.svg" alt="Criar Tópico de Discussão"></a>
    </header>
    <div class="container">
        <ul>
            <?php 
                include('conexao.php');
                $sql = "select 
                    topico.cod,
                    topico.assunto,
                    topico.texto,
                    u.email,
                    u.cod as 'cod_usuario' 
                from 
                    topico 
                left join 
                    usuarios as u on u.cod = cod_usuario 
                where 
                    assunto != '' 
                and
                    topico.status != 'Apagado'     
                    order by topico.cod desc";
                $result = $conn->query($sql);
	
                //Criamos uma variavel de controle com o valor 0 - equivalente a não tem cadastro
                $usuario_existe = 0;	   

                //Verifica o array linha por linha e adiciona a uma variável
                    foreach($result as $row){
            ?>
                    <li>
                        <div class="topic-content">
                            <h2><?php echo $row['assunto']." - <span class='escrito_por'> escrito por:".$row['email']."</span>"; ?></h2>
                            <p><?php echo $row['texto']; ?></p>
                        </div>
                        <div>
                            <ul>
                                <?php 
                                 include('conexao.php');
                                 $sql_resposta = "select 
                                    t.cod,    
                                    t.texto, 
                                    u.email as 'email_usuario',
                                    u.cod as 'cod_usuario' 
                                 from 
                                    conversa 
                                 left join topico as t on t.cod = conversa.cod_topico
                                 left join usuarios as u on u.cod = t.cod_usuario
                                 where 
                                    conversa.cod = ".$row['cod']." and
                                    t.status != 'Apagado'     
                                    order by t.cod desc"
                                    ;
                                 $result_resposta = $conn->query($sql_resposta);
                                 //echo $sql_resposta;
                                 //Verifica o array linha por linha e adiciona a uma variável
                                     foreach($result_resposta as $row_resposta){
                                
                                ?>
                                    <li>
                                            <?php echo $row_resposta['texto']." - <span class='escrito_por'> respondido por:".$row_resposta['email_usuario']."</span>"; ?>
                                            <?php if($_SESSION["cod_usuario"] == $row_resposta['cod_usuario']){ ?>
                                                <a class="button btn-primary" href="apagar_topico.php?cod=<?php echo $row_resposta['cod']; ?>">Apagar</a>
                                            <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="topic-action">
                            <a class="button btn-primary" href="topico.php?cod=<?php echo $row['cod']; ?>">Responder</a>
                            <?php if($_SESSION["cod_usuario"] == $row['cod_usuario']){ ?>
                                <a class="button btn-primary" href="apagar_topico.php?cod=<?php echo $row['cod']; ?>">Apagar</a>
                            <?php } ?>
                        </div>
                    </li>
            <?php 
                    }
            ?>
            

            

            
            
        </ul>
        
    </div>
</body>
</html>