<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['cliente'])) {
    die("Erro: Cliente não está logado.");
}

if (isset($_POST['agendar_servico'])) {
    $idCliente = $_SESSION['cliente']['idUsuario'];
    $nomeCliente = $_SESSION['cliente']['nome'];
    $data = $mysqli->real_escape_string($_POST['data']);
    $hora = $mysqli->real_escape_string($_POST['hora']);
    $servicoId = $mysqli->real_escape_string($_POST['servico']);

    // Busca o preço, a categoria e o nome do serviço na tabela 'servicos'
    $sql_servico = "SELECT preco, categoria, servico FROM servicos WHERE idServico = '$servicoId'";
    $result_servico = $mysqli->query($sql_servico);

    if ($result_servico && $result_servico->num_rows > 0) {
        $servico_data = $result_servico->fetch_assoc();
        $preco = $servico_data['preco'];
        $categoria = $servico_data['categoria'];
        $nomeServico = $servico_data['servico']; // Adicionando a captura do nome do serviço

        // Insere o agendamento com o preço, a categoria e o nome do serviço
        $sql_code = "INSERT INTO agendamento (data, hora, servico, idCliente, cliente, status, preco, categoria) 
                     VALUES ('$data', '$hora', '$nomeServico', '$idCliente', '$nomeCliente', 'aguardando aprovação', '$preco', '$categoria')";

        if ($mysqli->query($sql_code)) {
            echo "Agendamento realizado com sucesso! Aguardando aprovação.";
        } else {
            echo "Erro ao agendar serviço: " . $mysqli->error . "<br>";
            echo "Código SQL: " . $sql_code;
        }
    } else {
        echo "Erro: Serviço não encontrado.";
    }
}

// Consulta para obter todos os serviços cadastrados
$sql_servicos = "SELECT idServico, servico FROM servicos"; // Altere 'nome' para o nome correto da coluna que contém o nome do serviço
$result_servicos = $mysqli->query($sql_servicos);
?>

<h2>Agendar Serviço</h2>
<form action="agendar_servico.php" method="POST">
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" required><br><br>

    <label for="hora">Hora:</label>
    <select id="hora" name="hora" required>
        <option value="">Selecione uma hora</option>
        <option value="08:00">08:00</option>
        <option value="09:00">09:00</option>
        <option value="10:00">10:00</option>
        <option value="11:00">11:00</option>
        <option value="12:00">12:00</option>
        <option value="13:00">13:00</option>
        <option value="14:00">14:00</option>
        <option value="15:00">15:00</option>
        <option value="16:00">16:00</option>
        <option value="17:00">17:00</option>
        <option value="18:00">18:00</option>
    </select><br><br>

    <label for="servico">Serviço:</label>
    <select id="servico" name="servico" required>
        <option value="">Selecione um serviço</option>
        <?php
        // Gera as opções do select com os serviços disponíveis
        if ($result_servicos && $result_servicos->num_rows > 0) {
            while ($servico = $result_servicos->fetch_assoc()) {
                echo "<option value='{$servico['idServico']}'>{$servico['servico']}</option>";
            }
        } else {
            echo "<option value=''>Nenhum serviço disponível</option>";
        }
        ?>
    </select><br><br>
    <input type="submit" name="agendar_servico" value="Agendar Serviço">
</form>