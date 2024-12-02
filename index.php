<?php
$dsn = "mysql:host=localhost;dbname=db_agenda";
$usuario = "root";
$senha = "";

try {
    $conexao = new PDO($dsn, $usuario, $senha);
    $query = '
        create table if not exists tb_agenda(
            id int not null primary key auto_increment,
            nome varchar(100) not null,
            telefone varchar(20) not null
        )
    ';
    $retorno = $conexao->exec($query);
    if (
        
        isset(  $_GET['nome'], 
                $_GET['telefone']) &&

        !empty($_GET['nome']) &&
        !empty($_GET['telefone'])
    ) {

        $nome = $_GET['nome'];
        $telefone = $_GET['telefone'];


        if (!empty($nome) && !empty($telefone)) {

            $query = '
        insert into tb_agenda(
            nome, telefone
        )values(
            "' . $nome . '", "' . $telefone . '"
        )
    ';
           $retorno = $conexao->exec($query);
        }

        header("Location: agenda.php");
        exit();
    }


    $query = '
        select * from tb_agenda
    ';
    $retorno = $conexao->query($query);

    $lista = $retorno->fetchAll(PDO::FETCH_NUM);
} catch (PDOException $e) {
    echo  "Erro: " . $e->getCode();
    echo "<br>";

    echo  "Mensagem: " . $e->getMessage();
}




?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Telefônica</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="style_agenda.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Sans:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Agenda Telefônica</h1>
        </div>
        <div class="form">
            <form action="agenda.php" method="get">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Digite o nome...">
                <label for="Telefone">Telefone:</label>
                <input type="text" name="telefone" placeholder="Digite o telefone">
                <button type="submit">Cadastrar</button>
            </form>
        </div>
        <div class="separador"></div>
        <div class="lista-contatos">
            <?php

            echo "<ul>";
            foreach ($lista as $contato) {

                echo "<li>
                " . $contato[1] . "<div class ='icones'> 
                
                <a" . $contato[0] . "'><i class='fas fa-solid fa-phone'></i></a>
                $contato[2]  
                <a href='agenda.php?delete_id=" . $contato[0] . "'><i class='fas fa-trash-alt icone-lixeira'></i></a>
                <a href='editar_contato.php?edit_id=".$contato[0]."'><i class='fas fa-edit icone-editar'> </i></a>
                    </div>
         
                     </li>";
            }
            echo "</ul>";

            // Verifica se há um parâmetro de exclusão na URL
            if (isset($_GET['delete_id'])) {
                // Obtém o ID da contato a ser excluída
                $delete_id = $_GET['delete_id'];


                // Prepara a consulta para excluir acontato
                $query = 'DELETE FROM tb_agenda WHERE id = :delete_id';

                // Prepara e executa a consulta usando prepared statements para evitar injeção de SQL
                $retorno = $conexao->prepare($query);
                $retorno->bindParam(':delete_id', $delete_id);
                $retorno->execute();

                // Redireciona de volta para a página principal após a exclusão
                header("Location: agenda.php");
                exit();
            }

            // Verifica se há um parâmetro de edição na URL
            if (isset($_GET['edit_id'])) {
                // Obtém o ID do contato a ser editado
                $edit_id = $_GET['edit_id'];

                // Redireciona para a página de edição com o ID do contato
                header("Location: editar_contato.php?id=$edit_id");
                exit();
            }


            ?>

        </div>
        <div class="footer">
            <p>Desenvolvido por Leandro Silva De Oliveira - ETC</p>
        </div>
</body>

</html>