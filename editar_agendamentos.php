<?php
include('conexao.php');
session_start();


if (isset($_GET['idAgendamento'])) {
    $idAgendamento = $_GET['idAgendamento'];


    $sql_code = "SELECT * FROM agendamento WHERE idAgendamento = '$idAgendamento' AND idCliente = '" . $_SESSION['cliente']['idUsuario'] . "'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    if ($sql_query->num_rows > 0) {
        $agendamento = $sql_query->fetch_assoc();
    } else {
        die("Agendamento não encontrado.");
    }
} else {
    die("ID do agendamento não informado.");
}


if (isset($_POST['editar_agendamento'])) {
    $data = $mysqli->real_escape_string($_POST['data']);
    $servico = $mysqli->real_escape_string($_POST['servico']);
    $hora = $mysqli->real_escape_string($_POST['hora']);


    $sql_code = "UPDATE agendamento SET data = '$data', servico = '$servico', hora = '$hora', status = 'aguardando aprovação' 
                   WHERE idAgendamento = '$idAgendamento' AND idCliente = '" . $_SESSION['cliente']['idUsuario'] . "'";

    if ($mysqli->query($sql_code)) {
        echo "<div class='alert alert-success'>Agendamento atualizado com sucesso! Aguardando aprovação.</div>";
    } else {
        die("Erro ao atualizar agendamento: " . $mysqli->error);
    }
}


$servicos = [];
$sql_servicos = "SELECT * FROM servicos";
$result_servicos = $mysqli->query($sql_servicos) or die($mysqli->error);

if ($result_servicos->num_rows > 0) {
    while ($servico = $result_servicos->fetch_assoc()) {
        $servicos[] = $servico;
    }
}

if (isset($_SESSION['id_Usuario'])) {
    $id_Usuario = $_SESSION['id_Usuario'];


    $sql = "SELECT Nome, Sobrenome FROM usuarios WHERE idUsuario = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id_Usuario);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user) {

            $nome_completo = $user['Nome'] . ' ' . $user['Sobrenome'];
        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        echo "Erro ao executar a consulta.";
    }

    $stmt->close();
} else {
    echo "Nenhum usuário está logado.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Projeto Integrador</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="pagina_cliente.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Painel Cliente<sup></sup></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="pagina_cliente.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Agendametos</span></a>
            </li>
            <!-- Heading -->
            <div class="sidebar-heading">
                Opções
            </div>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="historico_servicos.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Histórico de serviços</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            <!-- Sidebar Message -->
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>Painel Pro!!</strong> Cobre da empresa
                    que atualize para versão PRO
                </p>
                <a class="btn btn-success btn-sm" href="#">Cobre
                    agora!</a>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Pesquisa... (Desativado)" aria-label="Search"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alertas
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">12 de agosto, 2024</div>
                                        <span class="font-weight-bold">Versão PRO!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">12 de agosto, 2024</div>
                                        Peça para a empresa que contratou atualizar para a versão PRO!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">12 de agosto, 2024</div>
                                        Você terá mais funcionalidades se a dona do salão atualizar para versão PRO.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Outros alertas(Versão
                                    PRO)</a>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($nome_completo); ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil(Desativado)
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configurações(Desativado)
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Conteúdo da página -->
                <div class="container-fluid">
                    <h2>Editar Agendamento</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="idAgendamento"
                            value="<?php echo htmlspecialchars($agendamento['idAgendamento']); ?>">
                        <div class="form-group">
                            <label for="servico">Serviço</label>
                            <select class="form-control" id="servico" name="servico" required>
                                <option value="" disabled>Selecione um serviço</option>
                                <?php foreach ($servicos as $s): ?>
                                    <option value="<?php echo htmlspecialchars($s['servico']); ?>"
                                        <?php echo ($s['servico'] == $agendamento['servico']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($s['servico']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data">Data</label>
                            <input type="date" class="form-control" id="data" name="data"
                                min="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo htmlspecialchars($agendamento['data']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <select class="form-control" id="hora" name="hora" required>
                                <option value="" disabled>Selecione uma hora</option>
                                <?php
                                for ($h = 8; $h <= 18; $h++) {
                                    $hora_formatada = sprintf('%02d:00', $h);
                                    $selected = ($hora_formatada == $agendamento['hora']) ? 'selected' : '';
                                    echo "<option value='$hora_formatada' $selected>$hora_formatada</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="editar_agendamento" class="btn btn-primary">Salvar
                            Alterações</button>
                        <a href="pagina_anterior.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
                <!-- /.fim do conteúdo da pagina-->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; A4S 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tem certeza que deseja sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione "Sair" para encerrar a sessão.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="login.php">Sair</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>