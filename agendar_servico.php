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


    $sql_servico = "SELECT preco, categoria, servico FROM servicos WHERE idServico = '$servicoId'";
    $result_servico = $mysqli->query($sql_servico);

    if ($result_servico && $result_servico->num_rows > 0) {
        $servico_data = $result_servico->fetch_assoc();
        $preco = $servico_data['preco'];
        $categoria = $servico_data['categoria'];
        $nomeServico = $servico_data['servico'];


        $sql_code = "INSERT INTO agendamento (data, hora, servico, idCliente, cliente, status, preco, categoria) 
                       VALUES ('$data', '$hora', '$nomeServico', '$idCliente', '$nomeCliente', 'aguardando aprovação', '$preco', '$categoria')";

        if ($mysqli->query($sql_code)) {

            echo "<script>
                      alert('Agendamento realizado com sucesso! Aguardando aprovação.');
                      window.location.href = 'pagina_cliente.php';
                    </script>";
        } else {

            echo "<script>
                      alert('Erro ao agendar serviço: " . $mysqli->error . "');
                    </script>";
        }
    }
}


$sql_servicos = "SELECT idServico, servico FROM servicos";
$result_servicos = $mysqli->query($sql_servicos);


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
                <a class="nav-link" href="agendar_servico.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Agendar</span></a>
            </li>
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
                <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
                    <div class="w-50">
                        <h2 class="text-center mb-4">Agendar Serviço</h2>
                        <form class="user" action="agendar_servico.php" method="POST">
                            <div class="form-group text-center">
                                <label for="data" class="font-weight-bold">Data:</label>
                                <input type="date" id="data" name="data" class="form-control w-50 mx-auto" required>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary text-center">Selecione a Hora</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group text-center">
                                        <label for="hora">Hora:</label>
                                        <select id="hora" name="hora" class="form-control w-50 mx-auto" required>
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary text-center">Selecione o Serviço
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group text-center">
                                        <label for="servico">Serviço:</label>
                                        <select id="servico" name="servico" class="form-control w-50 mx-auto" required>
                                            <option value="">Selecione um serviço</option>
                                            <?php
                                            if ($result_servicos && $result_servicos->num_rows > 0) {
                                                while ($servico = $result_servicos->fetch_assoc()) {
                                                    echo "<option value='{$servico['idServico']}'>{$servico['servico']}</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Nenhum serviço disponível</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="agendar_servico" class="btn btn-primary w-50">
                                    Agendar Serviço
                                </button>
                            </div>
                        </form>
                    </div>
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