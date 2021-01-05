<?php

try {
    $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=postafi', 'root', ''); 
} catch (Exception $e) {
    echo 'Não foi possível conectar ao Banco de Dados.';
}

?>

