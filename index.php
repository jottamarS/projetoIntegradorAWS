<?php
  include('conexao.php');
  session_start();
  
  $sql_servicos_realizados = "
        SELECT s.servico, COUNT(a.idAgendamento) AS quantidade 
        FROM agendamento a 
        JOIN servicos s ON a.servico = s.servico 
        WHERE a.status = 'Validado' 
        GROUP BY s.servico
    ";
  
  $result_servicos_realizados = $mysqli->query($sql_servicos_realizados);
  
  $servicos = [
      'Corte de cabelo' => 0,
      'Hidratação' => 0,
      'Manicure' => 0,
      'Depilação axilas' => 0,
      'Depilação completa' => 0,
  ];
  
  if ($result_servicos_realizados) {
      while ($row = $result_servicos_realizados->fetch_assoc()) {
          $servicos[$row['servico']] = $row['quantidade'];
      }
  }
  
  $sql_categoria = "SELECT categoria, SUM(preco) AS total_ganho 
                      FROM agendamento 
                      WHERE status = 'Validado' 
                      GROUP BY categoria";
  
  $result_categoria = $mysqli->query($sql_categoria);
  
  $categorias = [];
  $totalGanhosPorCategoria = [];
  
  if ($result_categoria) {
      while ($row = $result_categoria->fetch_assoc()) {
          $categorias[] = $row['categoria'];
          $totalGanhosPorCategoria[] = (float)$row['total_ganho'];
      }
  }
  
  $categoriasJson = json_encode($categorias);
  $totalGanhosPorCategoriaJson = json_encode($totalGanhosPorCategoria);
  
  $ganhosMensais = array_fill(0, 12, 0);
  
  $sql = "SELECT MONTH(data) AS mes, SUM(preco) AS total_ganho 
           FROM agendamento 
            WHERE status = 'Validado' 
            GROUP BY mes
            ORDER BY mes";
  
  $result = $mysqli->query($sql);
  
  if ($result) {
      while ($row = $result->fetch_assoc()) {
          $mes = (int)$row['mes'] - 1;
          $ganhosMensais[$mes] = $row['total_ganho'];
      }
  }
  
  $ganhosMensaisJson = json_encode($ganhosMensais);
  
  $dataAtual = date('Y-m-d');
  $primeiroDiaMes = date('Y-m-01');
  
  $sql_ganhos = "SELECT SUM(preco) AS totalGanhos FROM agendamento WHERE status = 'Validado' AND data >= '$primeiroDiaMes' AND data <= '$dataAtual'";
  $result_ganhos = $mysqli->query($sql_ganhos);
  $ganhos = $result_ganhos->fetch_assoc()['totalGanhos'] ?? 0;
  
  $sql_pendentes_aprovacao = "SELECT COUNT(*) AS totalPendentes FROM agendamento WHERE status = 'aguardando aprovação'";
  $result_pendentes_aprovacao = $mysqli->query($sql_pendentes_aprovacao);
  $pendentesAprovacao = $result_pendentes_aprovacao->fetch_assoc()['totalPendentes'];
  
  $sql_servicos_prestados = "SELECT COUNT(*) AS totalPrestados FROM agendamento WHERE status = 'Validado'";
  $result_servicos_prestados = $mysqli->query($sql_servicos_prestados);
  $servicosPrestados = $result_servicos_prestados->fetch_assoc()['totalPrestados'];
  
  $sql_servicos_pendentes = "SELECT COUNT(*) AS totalPendentesServicos FROM agendamento WHERE status = 'Agendado'";
  $result_servicos_pendentes = $mysqli->query($sql_servicos_pendentes);
  $servicosPendentes = $result_servicos_pendentes->fetch_assoc()['totalPendentesServicos'];
  
  
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
                <!-- Conteúdo da pagina -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Gerar relatório(Versão PRO)</a>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Ganhos Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Ganhos (mensal)
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                R$<?php echo number_format($ganhos, 2, ',', '.'); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Serviços pendentes aprovação Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Serviços pendentes de aprovação
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $pendentesAprovacao; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Serviços prestados Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Serviços prestados
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php echo $servicosPrestados; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Serviços pendentes Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Serviços pendentes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $servicosPendentes; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Grafico de ganhos</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Só versão PRO:</div>
                                            <a class="dropdown-item" href="#">PRO</a>
                                            <a class="dropdown-item" href="#">PAGA O PRO</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">SÓ SE PAGAR O PRO</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Ganhos por categoria de serviços</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Menu PRO:</div>
                                            <a class="dropdown-item" href="#">PRO</a>
                                            <a class="dropdown-item" href="#">Outra ação PRO</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Tem que pagar o PRO</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Cabelo
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Podologia
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Depilação
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Serviços realizados</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Corte de cabelo <span
                                            class="float-right"><?= $servicos['Corte de cabelo'] ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                            style="width: <?= $servicos['Corte de cabelo'] ?>%"
                                            aria-valuenow="<?= $servicos['Corte de cabelo'] ?>" aria-valuemin="0"
                                            aria-valuemax="1"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Hidratação <span
                                            class="float-right"><?= $servicos['Hidratação'] ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: <?= $servicos['Hidratação'] ?>%"
                                            aria-valuenow="<?= $servicos['Hidratação'] ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Manicure <span
                                            class="float-right"><?= $servicos['Manicure'] ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: <?= $servicos['Manicure'] ?>%"
                                            aria-valuenow="<?= $servicos['Manicure'] ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Depilação axilas <span
                                            class="float-right"><?= $servicos['Depilação axilas'] ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: <?= $servicos['Depilação axilas'] ?>%"
                                            aria-valuenow="<?= $servicos['Depilação axilas'] ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Depilação completa <span
                                            class="float-right"><?= $servicos['Depilação completa'] ?></span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: <?= $servicos['Depilação completa'] ?>%"
                                            aria-valuenow="<?= $servicos['Depilação completa'] ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Propaganda</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="...">
                                    </div>
                                    <p>Adicione novas funcionalidades ativando a versão <a target="_blank"
                                            rel="nofollow" href="#">PRO</a>, diversas opções de
                                        pagamentos e funcionalidades ótimas que dariam muito trabalho para fazer!
                                    </p>
                                    <a target="_blank" rel="nofollow" href="#">Versão PRO &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
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
    <script>
    var ganhosMensais = <?php echo $ganhosMensaisJson; ?>;
    var categorias = <?php echo $categoriasJson; ?>;
    var totaisGanhos = <?php echo $totalGanhosPorCategoriaJson; ?>;
    </script>
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>