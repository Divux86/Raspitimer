<html>
<link rel="stylesheet" href="cssphp.css">
<body>
  <!--barra lateral-->
<div class="panelcentral">
<h1>introduce el nombre de la carrera que quieras crear</h1>
<br>
<form  action="crearcarrera.php" method="POST">
nombre de la carrera sin espacios: <input type="text" name="nombre" ><br><br>
vueltas: <input type="number" name="vueltas" ><br><br>
<input type="submit" name="submit" value="crear"><br><br>
</form>
<a href="index.php">Volver atrás</a>
</div>
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
//SI submit ha sido presionado y nombre esta rellenado se cumple la condicion
if (isset($_POST['submit']) && !empty($_POST["nombre"]) && !empty($_POST["vueltas"])){
  // Conectando, seleccionando la base de datos
  if (!$conect = mysqli_connect($hostname, $user, $pwd,$dbname)) {
      die("no se ha podido conectar al SGBD");
  }

//recogo las variables de formulario
$vueltas=$_POST["vueltas"];
$nombre=$_POST["nombre"];

$vueltassql = '';
//saco la fecha
$hoy=date("_j_F_Y_g_i_a ");
//adjunto el nombre y la fecha
$nombreyfecha="'".$nombre.$hoy."'";

//creo el string que lleva el nombre de columna y tipo de datos
$sql="insert into races (name,vueltas) values (".$nombreyfecha." , ".$vueltas.")";

//ejecuto la orden crear
if (mysqli_query($conect,$sql) === TRUE) {
    echo "Se ha creado la carrera correctamente";
  } else {
    echo "ha habido un error, puede que la carrera ya exista o no hayas introducido las vueltas correctamente";
  }
// Cerrar la conexión
mysqli_close($conect);
}else {}

?>
</body>
</html>

