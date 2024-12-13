<?php
  session_start();
  include('conexao.php');
  
  if (isset($_POST['email']) && isset($_POST['senha'])) { 
  
      if (strlen($_POST['email']) == 0) {
          echo "Preencha seu email";
      } elseif (strlen($_POST['senha']) == 0) {
          echo "Preencha sua senha";
      } else {
          
          $email = $mysqli->real_escape_string($_POST['email']);
          $senha = $_POST['senha'];
  
          
          $sql_code = "SELECT * FROM usuarios WHERE email = '$email'";
          $sql_query = $mysqli->query($sql_code);
  
          
          if ($sql_query && $sql_query->num_rows == 1) {
              $usuario = $sql_query->fetch_assoc();
  
          
              if (password_verify($senha, $usuario['senha'])) {
          
                  $_SESSION['id_Usuario'] = $usuario['idUsuario'];
  
             
                  if ($usuario['tipo'] == 'cliente') {
                      header("Location: pagina_cliente.php");
                  } elseif ($usuario['tipo'] == 'admin') {
                      header("Location: index.php");
                  } else {
                      echo "Tipo de usuário desconhecido!";
                  }
                  exit; 
              } else {
                  echo "Senha incorreta.";
              }
          } else {
              echo "Email não encontrado.";
          }
      }
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
    <title>Os cria - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bem vindo!</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input type="text" name="email" class="form-control form-control-user"
                                                id="idemail" aria-describedby="emailHelp" placeholder="Login">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="senha" class="form-control form-control-user"
                                                id="idsenha" placeholder="Senha">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                        <hr>
                                        <a href="#" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google(Desativado)
                                        </a>
                                        <a href="#" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook(Desativado)
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="#">Esqueceu a senha?(Desativado)</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Criar conta!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>