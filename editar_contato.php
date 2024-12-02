<?php
$dsn = "mysql:host=localhost;dbname=db_agenda;charset=utf8";
$usuario = "root";
$senha = "";

try {
    $conexao = new PDO($dsn, $usuario, $senha);

    // Verifica se o id do contato foi passado pela URL

    if (isset($_GET['edit_id'])) {

        $edit_id = $_GET['edit_id'];



        // Consulta para obter os dados do contato pelo ID

        $query = 'SELECT * FROM tb_agenda WHERE id = :edit_id';

        $retorno = $conexao->prepare($query);

        $retorno->bindParam(':edit_id', $edit_id);

        $retorno->execute();



        // Busca os dados do contato

        $contato = $retorno->fetch(PDO::FETCH_ASSOC);



        // Verifica se encontrou o contato

        if ($contato) {

            $nome = $contato['nome'];

            $telefone = $contato['telefone'];

        } else {

            echo "Contato não encontrado!";

            exit();

        }

    }



    // Verifica se o formulário foi enviado para atualizar o contato

    if (isset($_GET['nome'], $_GET['telefone']) && !empty($_GET['nome'])

    && !empty($_GET['telefone'])) {

        $nome = $_GET['nome'];

        $telefone = $_GET['telefone'];



        // Atualiza o contato no banco de dados

        $query = 'UPDATE tb_agenda SET nome = :nome, telefone = :telefone WHERE id =:edit_id';

        $retorno = $conexao->prepare($query);

        $retorno->bindParam(':nome', $nome);

        $retorno->bindParam(':telefone', $telefone);

        $retorno->bindParam(':edit_id', $edit_id);

        $retorno->execute();



        // Redireciona para a página principal após a atualização

        header("Location: agenda.php");

        exit();

    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contato</title>
    <link rel="stylesheet" href="style_agenda.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Editar Contato</h1>
        </div>
        <div class="form">
            <form method="get" action="">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="">
                
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" value="" required>

                <button type="submit">Atualizar</button>
            </form>
        </div>
        <div class="footer">
            <p>Desenvolvido por Leandro Silva De Oliveira - ETC</p>
        </div>
    </div>
</body>

</html>