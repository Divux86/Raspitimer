<html>
<link rel="stylesheet" href="cssphp.css">
<body>
  <!--barra lateral-->
<div class="panelcentral">
<h1>selecciona la carrera que eliminar</h1>
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
if (isset($_POST['submit']) && !empty($_POST["nombre"])){
    // Conectando, seleccionando la base de datos
    if (!$conect = mysqli_connect($hostname, $user, $pwd,$dbname)) {
        die("no se ha podido conectar al SGBD");
    }

$nombre=$_POST["nombre"];
//string para eliminar la carrera
$delete= "delete from races where name = '".$nombre."';";

//orden sql para eliminar la carrera
if (mysqli_query($conect,$delete) === TRUE) {
    echo "Se ha eliminado la carrera correctamente <br>";
  } else {
    echo "algo ha salido mal, puede que la carrera ya no exista";}
    
    $showraces= mysqli_query($conect, "select name from races;");
    //desplegable para mostrar las carreras   
       echo '<form action="" method="POST">';
       echo '<select class="center" name="nombre">';
        while($table = mysqli_fetch_array($showraces)) { 
         echo('<option values='.$table[0] . ">".$table[0]."</option>");  
        }
       echo '</select>';
       echo '<input type="submit" name="submit" value="Eliminar"><br><br>
       </form>';
}
//else para mostrar en el desplegable las carreras
else{
  $showraces= mysqli_query($conect, "select name from races;");
  
  echo '<form action="" method="POST">';
  echo '<select class="center" name="nombre">';
   while($table = mysqli_fetch_array($showraces)) { 
    echo('<option values='.$table[0] . ">".$table[0]."</option>");  
   }
  echo '</select>';
  echo '<input type="submit" name="submit" value="Eliminar"><br><br>
  </form>';

}
  
 // Cerrar la conexión
mysqli_close($conect);
?>
<a href="index.php">Volver atrás</a>
</div>
