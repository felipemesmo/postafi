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
    <title>Cadastro - POSTAFI</title>

    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/cadastro.css">

</head>
<body>
    <header>
        <a href="#"><img src="./images/back-arrow.svg" alt="Voltar"></a>
        <a class="logo-postafi"><img src="./images/logo.svg" alt="Postafi"></a>
        <span class="new-topic"></span>
</header>
    <div class="container">
       <form action="cadastro_topico.php" method="POST">
           <fieldset>
                
                <?php 
                    if(!isset($_GET['cod'])){
                ?>
                <input type="hidden" name="tipo" value="0">
                <h2>Editar Tópico</h2>
                <label for="assunto">Título</label>
                <input type="text" name="assunto">
                <br>
                <?php } else{?>
                    <input type="hidden" name="tipo" value="<?php echo $_GET['cod']; ?>">
                    <h2>Responder Tópico: <?php 
                        include('conexao.php');
                         $sql = "select * from topico where cod = ".$_GET['cod'];
                         $result = $conn->query($sql);
             
                         //Criamos uma variavel de controle com o valor 0 - equivalente a não tem cadastro
                         $usuario_existe = 0;	   
                         
                         //Verifica o array linha por linha e adiciona a uma variável
                             foreach($result as $row){
                                 echo $row['assunto'];   
                             }
                        ?></h2>
                <?php } ?>    
                <label for="texto">Texto</label>
                <textarea name="texto" rows="5"></textarea>
           </fieldset>
           <fieldset>
                <button class="button btn-primary" type="submit">Cadastrar</button>
           </fieldset>
       </form>
    </div>
</body>
</html>