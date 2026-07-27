<?php
$conex = mysqli_connect("localhost","root","","datos");
insertar($conex);

function insertar($conex) {
    $nombreusuario = $_POST['nomusu'];
    $contrasenausuario = $_POST['pasusu'];
    $codigousuario = $_POST['codusu'];

    $consulta = "INSERT INTO datos(Nombre, Contraseña, Codigo)
                 VALUES ('$nombreusuario', '$contrasenausuario', '$codigousuario')";

    mysqli_query($conex, $consulta);
    mysqli_close($conex);

    echo $nombreusuario . "<br>" . $contrasenausuario . "<br>" . $codigousuario;
}
?>
