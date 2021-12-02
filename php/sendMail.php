<?php
session_start();

$idRepor = $_POST["idComen"];
$To = "sinnombreingsoft@gmail.com";
$Subject = "Comentario Reportado";
$Message = "El comentario con ID: " . $idRepor . " ha sido reportado.";

if (mail($To, $Subject, $Message)) {
    echo "Mail sent";
} else {
    echo "Mail not sent";
}
