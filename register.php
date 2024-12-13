<?php
include('conexao.php');

if (isset($_POST['cadastrar_cliente'])) {
    $tipo = $mysqli->real_escape_string($_POST['tipo']);
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $sobrenome = $mysqli->real_escape_string($_POST['sobrenome']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $endereco = $mysqli->real_escape_string($_POST['endereco']);
    $senha = $mysqli->real_escape_string($_POST['senha']);
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql_code = "INSERT INTO usuarios (tipo, nome, sobrenome, telefone, email, endereco, senha) 
                 VALUES ('$tipo', '$nome', '$sobrenome', '$telefone', '$email', '$endereco', '$senha_hash')";

    if ($mysqli->query($sql_code)) {
        header("Location: login.php");
        exit();
    } else {
        die("Erro ao cadastrar cliente: " . $mysqli->error);
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
    <title>Projeto Integrador</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Criar conta!</h1>
                            </div>
                            <form class="user" method="post">
                                <label for="tipo">Tipo</label>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" name="nome" class="form-control form-control-user"
                                            id="idnome" placeholder="Nome">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="sobrenome" class="form-control form-control-user"
                                            id="idtelefone" placeholder="Sobrenome">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="telefone" class="form-control form-control-user"
                                        id="idtelefone" placeholder="Telefone">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control form-control-user" id="idemail"
                                        placeholder="E-mail">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="endereco" class="form-control form-control-user"
                                        id="idendereco" placeholder="Endereço">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="senha" class="form-control form-control-user"
                                            id="idsenha" placeholder="Senha">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="idsenhaii"
                                            placeholder="Repita a senha">
                                    </div>
                                </div>
                                <div>
                                    <div style="text-align: center;">
                                        <select name="tipo" id="idtipo" class="btn btn-primary"
                                            style="width: auto; height: auto; padding: 0.375rem 0.75rem; border-radius: 0.25rem;">
                                            <option value="cliente">cliente</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    </div>
                                    <p></p>
                                    <button type="submit" href="login.php" class="btn btn-primary btn-user btn-block"
                                        name="cadastrar_cliente">Cadastrar</button>
                                    <hr>
                                    <a href="#" class="btn btn-google btn-user btn-block">
                                        <i class="fab fa-google fa-fw"></i> Register with Google(Desativado)
                                    </a>
                                    <a href="#" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook(Desativado)
                                    </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="#">Esqueceu a senha?(Desativado)</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Já tem conta? Acesse!</a>
                            </div>
                        </div>
                    </div>
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
</body>

</html>