<?php
session_start();
$_SESSION["LoggedUser"] = "Sign In";
$_SESSION["ProfName"] = "NO NAME";
$_SESSION["UserId"] = 0;
$_SESSION["IsLogged"] = false;
?>
<?php
$action = isset($_POST['submitF']) ? $_POST['submitF'] : null;
switch ($action) {
    case 'Sign in':
        $hostname = "localhost";
        $username = "id17917049_sinnombre";
        $password = "Q}YXWfq6=Xf-F^u*";
        $databaseName = "id17917049_wikiprofes20";

        // connect to mysql database using mysqli
        $connect = mysqli_connect($hostname, $username, $password, $databaseName);

        $_SESSION['mysql_conn'] = $connect;

        function query($sql)
        {
            return mysqli_query($_SESSION['mysql_conn'], $sql) or die(mysqli_error($_SESSION['mysql_conn']));
        }

        if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error);
        }
        //echo "Connected successfully";

        $UserMail = $_POST['mail'];
        $UserPass = $_POST['pass'];

        $sqlFind = "SELECT * from Usuarios WHERE correo_inst = '$UserMail' and user_password = '$UserPass'";
        //$result = query($sqlFind);
        $result = mysqli_query($_SESSION['mysql_conn'], $sqlFind);
        //if ($result) {

        while ($row = mysqli_fetch_array($result)) {
            $_SESSION["LoggedUser"] = $row['nombre'];
            $_SESSION["UserId"] = $row['idUsuario'];
            $_SESSION["IsLogged"] = TRUE;
            header('location: user.php');
        }
        mysqli_close($connect);
        break;

    case 'Unirme':
        $hostname = "localhost";
        $username = "id17917049_sinnombre";
        $password = "Q}YXWfq6=Xf-F^u*";
        $databaseName = "id17917049_wikiprofes20";

        // connect to mysql database using mysqli
        $connect = mysqli_connect($hostname, $username, $password, $databaseName);

        $UserName = $_POST['user_name'];
        $Correo = $_POST['email'];
        $Password = $_POST['passw'];
        $CorreoInst = $Correo . "@alumnos.udg.mx";

        if ($UserName && $Correo && $Password) {
            if (strlen($Password) < 8) {
                echo "<h5>La contraseña debe contar con más de 8 caracteres</h5>";
            } else {
                $sqlSignUp = "INSERT INTO Usuarios(nombre, correo_inst, user_password)
                                VALUES('$UserName', '$CorreoInst', '$Password')";

                if ($connect->query($sqlSignUp) === TRUE) {
                    echo "Gracias por registrarte! ";
                } else {
                    echo "Error: " . $sqlSignUp . "<br>" . $connect->error;
                }
            }
        } else {
            echo "Rellena todos los campos";
        }
        mysqli_close($connect);
        break;

    case 'Enviar':
        $FullName = $_POST['profFullName'];
        $MateriasTot = $_POST['materias'];
        $To = "sinnombreingsoft@gmail.com";
        $Subject = "Petición para añadir profesor";
        $Message = "Profesor a añadir: " . $FullName . " --- Materias: " . $MateriasTot . ".";

        if (mail($To, $Subject, $Message)) {
            echo "Mail sent";
        } else {
            echo "Mail not sent";
        }
        break;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WikiProfes 2.0</title>
    <link rel="stylesheet" href="styleIndexPage.css">
</head>

<body>

    <div class="container" id="blur">
        <div class="navbar">
            <img src="loveplanet.png" class="logo">
            <form action="buscar.php" method="post" class="search-box">
                <input type="text" name="profName" placeholder="Busca a tu profesor..." class="searchField" required>
                <input type="submit" name="submitSearch" class="search-button" value="Buscar">
            </form>
            <nav>
                <ul>
                    <li><a href="">WikiProfes 2.0</a></li>
                    <li><a href="#" onclick="toggleAP()">Agregar un porfesor</a></li>
                    <li><a href="#" onclick="toggle()"><?php
                                                        echo $_SESSION["LoggedUser"];
                                                        ?></a></li>
                    <li><a href="#" onclick="toggleSU()">Sign up</a></li>
                </ul>
            </nav>
            <img src="menu.png" class="menu-icon">
        </div>
        <div class="row">
            <div class="col-1">
                <h2>Bienvenido a<br>WikiProfes 2.0</h2>
                <h3>El sitio que te ayudará a decidir con que profesores agendar tus materias</h3>
                <p>Ingresa el nombre del profesor de tu interés en la barra de búsqueda</p>
                <button type="button" class="tutorial-button">Tour rápido<img src="arrow.png"></button>
            </div>
            <div class="col-2">
                <div class="status">
                    <h2>Top profesores de CUCEI</h2>
                </div>
                <?php
                $hostname = "localhost";
                $username = "id17917049_sinnombre";
                $password = "Q}YXWfq6=Xf-F^u*";
                $databaseName = "id17917049_wikiprofes20";
                // connect to mysql database using mysqli
                $connect = mysqli_connect($hostname, $username, $password, $databaseName);

                $_SESSION['mysql_conn'] = $connect;
                $sqlTop = "SELECT * FROM Profesores ORDER BY promedio DESC";
                $resultTop = mysqli_query($_SESSION['mysql_conn'], $sqlTop);
                if ($resultTop->num_rows === 0) {
                    echo "No hay profesores disponbibles por el momento";
                } else {
                    while ($rowTop = mysqli_fetch_array($resultTop)) {
                ?>
                        <div class="cards">
                            <div class="card">
                                <div class="card-info">
                                    <h2><?php echo $rowTop['nombre'] ?></h2>
                                    <p><?php echo $rowTop['NRC'] ?></p>
                                    <div class="overall"></div>
                                </div>
                                <h2 class="grade"><?php echo $rowTop['promedio'] ?></h2>
                            </div>
                        </div>
                <?php }
                }
                ?>
            </div>
        </div>
    </div>

    <div id="popup">
        <button class="close-button" onclick="toggle()"><img src="close.png"></button>
        <div class="title">
            <h2>Hola de vuelta!</h2>
            <p>Inicia sesión en tu cuenta desde aquí</p>
        </div>
        <form action="" method="post" class="input-data">
            <input type="text" name="mail" placeholder="Email" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <input type="submit" name="submitF" value="Sign in" class="sign-in">
        </form>
        <nav class="footer-in">
            <ul>
                <li><a href="">Has olvidado tu contraseña?</a></li>
                <li><a href="">Crea una cuenta</a></li>
            </ul>
        </nav>
    </div>

    <div id="signup">
        <button class="close-button" onclick="toggleSU()"><img src="close.png"></button>
        <div class="title">
            <h2>Únete a WikiProfes 2.0</h2>
            <p>Regístrate para poder evaluar a tus profesores</p>
        </div>

        <form action="" method="post" class="input-data">
            <input type="text" name="user_name" class="SUinp" placeholder="Nombre completo" required>
            <div class="emailInput">
                <div class="emailInputElement">
                    <input type="text" name="email" class="field-divided" placeholder="Correo Inst." />
                </div>
                <div class="emailInputElement">
                    <input type="text" name="inst" class="field-divided" value="@alumnos.udg.mx" readonly />
                </div>
            </div>
            <!--<input type="email" name="email" placeholder="Correo Institucional" required> -->
            <input type="password" name="passw" class="SUinp" placeholder="Contraseña" minlength="8" required>
            <input type="submit" name="submitF" value="Unirme" class="sign-in">
        </form>
        <nav class="footer-in">
            <ul>
                <li><a href="">Ya tienes una cuenta? Inicia sesión</a></li>
            </ul>
        </nav>
    </div>

    <div id="add">
        <button class="close-button" onclick="toggleAP()"><img src="close.png"></button>
        <div class="title">
            <h2>Solicitud para agregar profesor</h2>
            <p>Envíanos los datos del profesor que no encontraste</p>
        </div>
        <form action="" method="post" class="input-data">
            <input type="text" name="profFullName" placeholder="Nombre completo" required>
            <input type="text" name="materias" placeholder="Códigos de materias que imparte (separados por coma)" required>
            <input type="submit" name="submitF" value="Enviar" class="sign-in">
        </form>
    </div>
    <script type="text/javascript">
        function toggle() {
            var blur = document.getElementById('blur');
            blur.classList.toggle('active');
            var popup = document.getElementById('popup');
            popup.classList.toggle('active');
        }
    </script>
    <script type="text/javascript">
        function toggleSU() {
            var blur = document.getElementById('blur');
            blur.classList.toggle('active');
            var signup = document.getElementById('signup');
            signup.classList.toggle('active');
        }
    </script>
    <script type="text/javascript">
        function toggleAP() {
            var blur = document.getElementById('blur');
            blur.classList.toggle('active');
            var add = document.getElementById('add');
            add.classList.toggle('active');
        }
    </script>
</body>

</html>