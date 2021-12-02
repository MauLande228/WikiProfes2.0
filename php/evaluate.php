<?php
session_start();
//$IdProfesor = "1";
//$Profesor = "JOSE DE JESUS SOTO SANCHEZ";

// php code to Insert data into mysql database from input text
//if (isset($_POST['update'])) {
  $hostname = "localhost";
  $username = "id17917049_sinnombre";
  $password = "Q}YXWfq6=Xf-F^u*";
  $databaseName = "id17917049_wikiprofes20";

  // connect to mysql database using mysqli
  $connect = mysqli_connect($hostname, $username, $password, $databaseName);

  // get values form input text and number
  $Materia = $_POST["materia"];
  $DominioTema = $_POST["dom_tema"];
  $DificultadCurso = $_POST["dif_curso"];
  $Puntualidad = $_POST["puntualidad"];
  $Comentario = $_POST["comentario"];
  $id = $_SESSION["IdProf"];

  $sqlAddEvaluation = "INSERT INTO Evaluaciones(idProf, tipo) VALUES('$id', 'valido')";
  $sqlAddResult = mysqli_query($connect, $sqlAddEvaluation);

  $sqlReadCount = "SELECT COUNT(*) FROM Evaluaciones WHERE idProf = $id AND tipo = 'valido'";
  $sqlReadCountRS = mysqli_query($connect, $sqlReadCount);
  $count = mysqli_fetch_array($sqlReadCountRS);
  //$resultado = mysqli_query($connect,$insertar);
  $sqlProf = "UPDATE Profesores SET dom_tema = (dom_tema*($count[0]-1) + '$DominioTema')/$count[0],
    dif_curso = (dif_curso*($count[0]-1) + '$DificultadCurso')/$count[0], 
    puntualidad = (puntualidad*($count[0]-1) + '$Puntualidad')/$count[0],
    promedio = (dom_tema+dif_curso+puntualidad)/3
    WHERE idProf = $id";

  $sqlMat = "INSERT INTO Materias(idProf, nombre_materia) 
                VALUES('$id', '$Materia')";

  $sqlCom = "INSERT INTO Comentarios(idProf, comentario)
                VALUES('$id', '$Comentario')";

  //SELECT idProf FROM Profesores WHERE idProf = $_SESSION["IdProf"]
  //$resultado = mysqli_query($connect,$insertar);

  if ($Comentario !== "") {
    if ($connect->query($sqlProf) === TRUE) {
      echo "Actualizada";
    } else {
      echo "Error: " . $sqlProf . "<br>" . $connect->error;
    }
  } else {
    if ($DominioTema < 40) $DominioTema = 40;
    if ($DificultadCurso < 40) $DificultadCurso = 40;
    if ($Puntualidad < 40) $Puntualidad = 40;
    $sqlNoCom = "UPDATE Profesores SET dom_tema = (dom_tema*($count[0]-1) + '$DominioTema')/$count[0], 
    dif_curso = (dif_curso*($count[0]-1) + '$DificultadCurso')/$count[0], 
    puntualidad = (puntualidad*($count[0]-1) + '$Puntualidad')/$count[0],
    promedio = (dom_tema+dif_curso+puntualidad)/3
    WHERE idProf = $id";
    if ($connect->query($sqlNoCom) === TRUE) {
      echo "ActualizadaNoCom";
    } else {
      echo "Error: " . $sqlNoCom . "<br>" . $connect->error;
    }
  }

  if ($connect->query($sqlMat) === TRUE) {
    echo "Actualizada materia";
  } else {
    echo "Error: " . $sqlMat . "<br>" . $connect->error;
  }

  if ($Comentario !== "") {
    if ($connect->query($sqlCom) === TRUE) {
      echo "Actualizado comentario";
    } else {
      echo "Error: " . $sqlCom . "<br>" . $connect->error;
    }
  }

  mysqli_close($connect);
//}
