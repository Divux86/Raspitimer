<html>
<link rel="stylesheet" href="cssphp.css">
<body>
<h1>Selecciona la carrera que quieras correr</h1>
  <!--barra lateral-->
<div class="panelcentral">
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
$showtables= mysqli_query($conect, "select name from races;");
?>
    
<form action="" method="POST">
<select class="center" name="nombre">

<?php
 while($table = mysqli_fetch_array($showtables)) { 
  echo('<option values='.$table[0] . ">".$table[0]."</option>");  
 }
 ?>

</select>
<input type="submit" name="submit" value="seleccionar"><br><br>
</form>


<?php
 //SI submit ha sido presionado y nombre esta rellenado se cumple la condicion
if (isset($_POST['submit']) && !empty($_POST["nombre"])){
    // Conectando, seleccionando la base de datos
    if (!$conect = mysqli_connect($hostname, $user, $pwd,$dbname)) {
        die("no se ha podido conectar al SGBD");
    }
  
    
 //convertir el nombre de la carrera en su ID   
$nombre='"'.$_POST["nombre"].'"';

$selectlaps= 'select id_race from races where name = '.$nombre.';';

$result = mysqli_query($conect,$selectlaps);
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc() ) {
    //genero  la tabla
    $idcarrera=$row['id_race'];
  }
}

//insertar el ID de carrera en currace
$insert="insert into currace values (".$idcarrera.")";

$delete= "delete from currace";
if (mysqli_query($conect,$delete) === TRUE) {
    echo "Se ha actualizado correctamente";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  if (mysqli_query($conect,$insert) === TRUE) {
    echo " y seleccionado correctamente";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
 // Cerrar la conexión
mysqli_close($conect);


?>

</div>

<br>
<a href="index.php">Volver atrás</a>
</body>
</html>

