<?php
include('conexao.php');
session_start();

$clientes = [];
$detalhesCliente = null;

if (isset($_POST['pesquisar_cliente'])) {
    $termo = $mysqli->real_escape_string($_POST['termo']);

    $sql_code = "SELECT * FROM usuarios WHERE (nome LIKE '%$termo%' OR sobrenome LIKE '%$termo%' OR email LIKE '%$termo%' OR telefone LIKE '%$termo%') AND tipo = 'cliente'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    if ($sql_query->num_rows > 0) {
        while ($cliente = $sql_query->fetch_assoc()) {
            $clientes[] = $cliente;
        }
    } else {
        $error_message = "Nenhum cliente encontrado.";
    }
}


if (isset($_POST['ver_detalhes'])) {
    $idCliente = $_POST['idCliente'];


    $sql_details_code = "SELECT * FROM usuarios WHERE idUsuario = '$idCliente'";
    $sql_details_query = $mysqli->query($sql_details_code) or die($mysqli->error);

    if ($sql_details_query->num_rows > 0) {
        $detalhesCliente = $sql_details_query->fetch_assoc();

        $sql_hist = "SELECT * FROM agendamento WHERE idCliente = '$idCliente'";
        $sql_hist_query = $mysqli->query($sql_hist) or die($mysqli->error);

        $agendamentos = [];
        if ($sql_hist_query->num_rows > 0) {
            while ($agendamento = $sql_hist_query->fetch_assoc()) {
                $agendamentos[] = $agendamento;
            }
        }
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Painel ADM <sup></sup></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Heading -->
            <div class="sidebar-heading">
                Opções
            </div>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="aprovar_agendamentos.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Aprovar agendamentos</span></a>
            </li>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="validar_servicos.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Validar serviços</span></a>
            </li>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="cadastrar_servicos.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Cadastrar serviços</span></a>
            </li>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="editar_servicos.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Editar serviços</span></a>
            </li>
            <!-- Nav Item -  -->
            <li class="nav-item">
                <a class="nav-link" href="pesquisar_clientes.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pesquisar clientes</span></a>
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
                <p class="text-center mb-2"><strong>Painel Pro!!</strong> Melhore as funcionalidades</p>
                <a class="btn btn-success btn-sm" href="#">Atualize
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
                                        <span class="font-weight-bold">Mensagem!</span>
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
                                        R$150,00 de desconto para atualizar para o pro!
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
                                        Mrelhore sua versão.
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
                    <h2>Pesquisar Clientes</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="termo">Pesquisar por Nome, Sobrenome, Email ou Telefone:</label>
                            <input type="text" class="form-control" id="termo" name="termo" required>
                        </div>
                        <button type="submit" name="pesquisar_cliente" class="btn btn-primary">Pesquisar</button>
                    </form>
                    <?php if (!empty($clientes)): ?>
                        <h3>Resultados da Pesquisa</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['nome'] . " " . $cliente['sobrenome']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="idCliente"
                                                    value="<?php echo htmlspecialchars($cliente['idUsuario']); ?>">
                                                <button type="submit" name="ver_detalhes" class="btn btn-info">Ver
                                                    Detalhes</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <?php if ($detalhesCliente): ?>
                        <h3>Detalhes do Cliente</h3>
                        <p>Nome:
                            <?php echo htmlspecialchars($detalhesCliente['nome'] . " " . $detalhesCliente['sobrenome']); ?>
                        </p>
                        <p>Email: <?php echo htmlspecialchars($detalhesCliente['email']); ?></p>
                        <p>Telefone: <?php echo htmlspecialchars($detalhesCliente['telefone']); ?></p>
                        <h4>Histórico de Agendamentos</h4>
                        <?php if (!empty($agendamentos)): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Serviço</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agendamentos as $agendamento): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($agendamento['servico']); ?></td>
                                            <td><?php echo htmlspecialchars($agendamento['data']); ?></td>
                                            <td><?php echo htmlspecialchars($agendamento['status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum histórico encontrado.</p>
                        <?php endif; ?>
                    <?php endif; ?>
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