<?php
header('Cache-Control: no cache');
session_cache_limiter('private_no_expire');
session_start();
$_SESSION["IdProf"] = 0;
if (isset($_POST['submitSearch'])) {
	$Id = $_POST["profName"];
	$Id = strtoupper($Id);
	$_SESSION["ProfName"] = $Id;
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Resultados</title>
	<link rel="stylesheet" href="resultSeStyle.css">
</head>

<body>
	<h2>Resultados...</h2>
	<br>
	<div class="table-wrapper">
		<table class="fl-table">
			<thead>
				<tr>
					<th>Id Profesor</th>
					<th>Nombre</th>
					<th>NRC</th>
					<th>Dominio</th>
					<th>Dificultad</th>
					<th>Puntualidad</th>
					<th>Promedio</th>
				</tr>
			</thead>

			<?php
			$hostname = "localhost";
			$username = "id17917049_sinnombre";
			$password = "Q}YXWfq6=Xf-F^u*";
			$databaseName = "id17917049_wikiprofes20";

			// connect to mysql database using mysqli
			$connect = mysqli_connect($hostname, $username, $password, $databaseName);
			$_SESSION['mysql_conn'] = $connect;


			$idL = $_SESSION['ProfName'];
			$sql = "SELECT * FROM Profesores WHERE nombre LIKE '%$idL%' or NRC LIKE '%$idL%'";
			$result = mysqli_query($_SESSION['mysql_conn'], $sql);
			while ($mostrar = mysqli_fetch_array($result)) {
			?>
				<tbody>
					<tr>
						<td>
							<form action="profile.php" method="post">
								<input type="text" style="width:50px" name="idL" class="idpost" value="<?php echo $mostrar['idProf'] ?>" readonly>
								<input type="submit" name="submitId" class="idB" value="View">
							</form>
						</td>
						<td><?php echo $mostrar['nombre'] ?></td>
						<td><?php echo $mostrar['NRC'] ?></td>
						<td><?php echo $mostrar['dom_tema'] ?></td>
						<td><?php echo $mostrar['dif_curso'] ?></td>
						<td><?php echo $mostrar['puntualidad'] ?></td>
						<td><?php echo $mostrar['promedio'] ?></td>
					</tr>
				</tbody>
			<?php
			}
			?>
		</table>
	</div>
</body>

</html>