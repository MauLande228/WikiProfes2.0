<?php
header('Cache-Control: no cache');
session_cache_limiter('private_no_expire');
session_start();
$_SESSION["PrfNRC"] = 0;
$_SESSION["DifCurso"] = 0;
$_SESSION["DomTema"] = 0;
$_SESSION["Puntualidad"] = 0;
$_SESSION["Promedio"] = 0;
$_SESSION["IdComentario"] = 0;
$_SESSION["Likes"] = 0;
$_SESSION["Dislikes"] = 0;
$likedComments = array();
$dislikedComments = array();

/*if (isset($_POST["sendReport"])) {
    $idRepor = $_POST["idComen"];
    $To = "sinnombreingsoft@gmail.com";
    $Subject = "Comentario Reportado";
    $Message = "El comentario con ID: " . $idRepor . " ha sido reportado.";

    if (mail($To, $Subject, $Message)) {
        echo "Mail sent";
    } else {
        echo "Mail not sent";
    }
}*/

if (isset($_POST['submitId'])) {
    $hostname = "localhost";
    $username = "id17917049_sinnombre";
    $password = "Q}YXWfq6=Xf-F^u*";
    $databaseName = "id17917049_wikiprofes20";

    // connect to mysql database using mysqli
    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    $_SESSION['mysql_conn'] = $connect;

    $Id = $_POST["idL"];
    $_SESSION["IdProf"] = $Id;
    $idL = $_SESSION['IdProf'];
    $sql = "SELECT * FROM Profesores WHERE idProf = $idL";
    $result = mysqli_query($_SESSION['mysql_conn'], $sql);
    while ($mostrar = mysqli_fetch_array($result)) {
        $_SESSION["ProfName"] = $mostrar['nombre'];
        $_SESSION["PrfNRC"] = $mostrar['NRC'];
        $_SESSION["DifCurso"] = $mostrar['dif_curso'];
        $_SESSION["DomTema"] = $mostrar['dom_tema'];
        $_SESSION["Puntualidad"] = $mostrar['puntualidad'];
        $_SESSION["Promedio"] = $mostrar['promedio'];
    }
    mysqli_close($connect);
}
?>
<?php
include('server.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Perfil de profesor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="profileStyles.css">
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
                    <?php if ($_SESSION["IsLogged"]) : ?>
                        <li><a href="user.php">WikiProfes 2.0</a></li>
                    <?php else : ?>
                        <li><a href="index.php">WikiProfes 2.0</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION["IsLogged"] !== TRUE) : ?>
                        <li><a href="#" onclick="toggle()"><?php
                                                            echo $_SESSION["LoggedUser"];
                                                            ?></a></li>
                    <?php else : ?>
                        <li><a href="#"><?php
                                        echo $_SESSION["LoggedUser"];
                                        ?></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <img src="menu.png" class="menu-icon">
        </div>
        <main>
            <section class="glass">
                <div class="dashboard">
                    <div class="user">
                        <img src="prof.png" width="32px" alt="" />
                        <h3>
                            <?php
                            echo $_SESSION["ProfName"];
                            ?>
                        </h3>
                        <p>
                            <?php
                            echo $_SESSION["PrfNRC"];
                            ?>
                        </p>
                    </div>
                    <div class="links">
                        <div class="link">
                            <img src="complex.png" width="32px" alt="" />
                            <h2>Dificultad del curso</h2>
                            <p><b>
                                    <?php
                                    echo $_SESSION["DifCurso"];
                                    ?>
                                </b></p>
                        </div>
                        <div class="link">
                            <img src="gear.png" width="32px" alt="" />
                            <h2>Dominio del tema</h2>
                            <p><b>
                                    <?php
                                    echo $_SESSION["DomTema"];
                                    ?>
                                </b></p>
                        </div>
                        <div class="link">
                            <img src="clock.png" width="32px" alt="" />
                            <h2>Puntualidad</h2>
                            <p><b>
                                    <?php
                                    echo $_SESSION["Puntualidad"];
                                    ?>
                                </b></p>
                        </div>
                        <div class="link">
                            <img src="average.png" width="32px" alt="" />
                            <h2>Promedio</h2>
                            <p><b>
                                    <?php
                                    echo $_SESSION["Promedio"];
                                    ?>
                                </b></p>
                        </div>
                        <div class="evaluate">
                            <?php if ($_SESSION["IsLogged"]) : ?>
                                <a href="#" onclick="toggle()">Evaluar profesor</a>
                            <?php else : ?>
                                <h5>Inicia sesión para poder evaluar a este profesor</h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="evaluations">
                    <div class="status">
                        <h1>Sección de comentarios</h1>
                    </div>
                    <div class="comment-section">
                        <?php
                        $hostname = "localhost";
                        $username = "id17917049_sinnombre";
                        $password = "Q}YXWfq6=Xf-F^u*";
                        $databaseName = "id17917049_wikiprofes20";

                        // connect to mysql database using mysqli
                        $connect = mysqli_connect($hostname, $username, $password, $databaseName);
                        $_SESSION['mysql_conn'] = $connect;
                        $id = $_SESSION["IdProf"];

                        $sqlComment = "SELECT * FROM Comentarios WHERE idProf = $id";
                        $result = mysqli_query($_SESSION['mysql_conn'], $sqlComment);

                        if ($result->num_rows === 0) {
                            echo "Nadie ha comentado acerca de este profesor";
                        } else {

                            while ($row = mysqli_fetch_array($result)) {
                        ?>
                                <?php if (getDifference(
                                    getLikes($row['idComentario']),
                                    getDislikes($row['idComentario'])
                                ) > -4) : ?>
                                    <form action="sendMail.php" id="reportBtn">
                                        <input type="hidden" name="idComen" value="<?php echo $row['idComentario'] ?>">
                                        <input type="submit" name="sendReport" value="Reportar" class="report">
                                    </form>
                                    <div class="comment">
                                        <p><?php echo $row['comentario'] ?></p>
                                    </div>
                                    <div>
                                        <i <?php if (userLiked($row['idComentario'])) : ?> class="fa fa-thumbs-up like-btn" <?php else : ?> class="fa fa-thumbs-o-up like-btn" <?php endif ?> data-id="<?php echo $row['idComentario'] ?>"></i>
                                        <span class="likes"><?php echo getLikes($row['idComentario']); ?></span>

                                        &nbsp;&nbsp;&nbsp;&nbsp;

                                        <i <?php if (userDisliked($row['idComentario'])) : ?> class="fa fa-thumbs-down dislike-btn" <?php else : ?> class="fa fa-thumbs-o-down dislike-btn" <?php endif ?> data-id="<?php echo $row['idComentario'] ?>"></i>
                                        <span class="dislikes"><?php echo getDislikes($row['idComentario']); ?></span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                <?php endif; ?>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </section>
        </main>
        <div class="circle1"></div>
        <div class="circle2"></div>
    </div>
    <div id="popup">
        <button class="close-button" onclick="toggle()"><img src="close.png"></button>
        <div class="title">
            <h2> Evaluando a
                <?php
                echo $_SESSION["ProfName"];
                ?>
            </h2>
            <p>Si omites la sección de comentarios no podrás asignar menos de 40 pts por atributo</p>
        </div>
        <form action="evaluate.php" name="Form" class="input-data" id="eval" onsubmit="validateCom()">
            <h5>Código de la Materia correspondiente</h5>
            <input type="text" name="materia" placeholder="Código de Materia correspondiente" required>
            <h5>Dificultad del curso</h5>
            <input type="number" name="dif_curso" placeholder="Dificultad del curso" max="100" required>
            <h5>Dominio del tema</h5>
            <input type="number" name="dom_tema" placeholder="Dominio del tema" max="100" required>
            <h5>Puntualidad</h5>
            <input type="number" name="puntualidad" placeholder="Puntualidad" max="100" required>
            <textarea name="comentario" placeholder="Escribe un comentario" class="comment-space" maxlength="300"></textarea>
            <input type="submit" name="update" value="Publicar evaluación" class="sign-in">
        </form>
    </div>
    <script type="text/javascript">
        var flag = false;
    </script>

    <script type="text/javascript">
        function toggle() {
            var blur = document.getElementById('blur');
            blur.classList.toggle('active');
            var popup = document.getElementById('popup');
            popup.classList.toggle('active');
        }
    </script>
    <script type="text/javascript">
        function validateCom() {
            var a = document.forms["Form"]["comentario"].value;
            flag = true;
            if (a == null || a == "") {
                alert("No escribiste un comentario! Todos los atributos que hayas evaluado con menos de 40 se volverán 40");
                return false;
            }
        }
    </script>

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
        $(function() {
            $('#eval').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: 'post',
                    url: url,
                    data: form.serialize(),
                    success: function() {
                        alert('Datos actualizados');
                        toggle()
                    }
                });
            });
        });
    </script>

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
        $(function() {
            $('#reportBtn').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: 'post',
                    url: url,
                    data: form.serialize(),
                    success: function() {
                        alert('Tu reporte se ha enviado');
                    }
                });
            });
        });
    </script>

    <script src="scripts.js"></script>

</body>

</html>