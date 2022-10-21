<html>
<link rel="stylesheet" href="cssphp.css">
<body>
  <!--barra lateral-->
<div class="panelcentral">
<h1>selecciona la carrera de la que quieras ver los resultados</h1>
<br>
<?php
//variables de la BD
$hostname = "localhost";
$user = "admin";
$pwd = "raspberry";
$dbname = "modo2";

// Conectando, seleccionando la base de datos
if (!$conect = mysqli_connect($hostname, $user, $pwd,$dbname)) {
    die("no se ha podido conectar al SGBD");
}

echo '<br><br><h1>Lista de carreras creadas</h1>';
$showtables= mysqli_query($conect, "select name from races;");
?>
<!-- inicio del formulario -->
<form action="resultados.php" method="POST">
<select class="center" name="nombre">

<?php
//bucle para sacar los nombres de las carreras
 while($table = mysqli_fetch_array($showtables)) { 
  echo('<option values='.$table[0] . ">".$table[0]."</option>");  
 }
 ?>

</select>
<!-- boton submit-->
<input type="submit" name="submit" value="seleccionar"><br><br>
</form>
<?php
 // Cerrar la conexión
mysqli_close($conect);
?>
<a href="index.php">Volver atrás</a>
</div>

</body>
</html>

