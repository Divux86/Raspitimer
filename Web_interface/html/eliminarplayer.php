<html>
<link rel="stylesheet" href="cssphp.css">
<body>
  <!--barra lateral-->
<div class="panelcentral">
<h1>selecciona el jugador que eliminar</h1>
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
$delete= "delete from players where name = '".$nombre."'";

if (mysqli_query($conect,$delete) === TRUE) {
    echo "Se ha eliminado el jugador correctamente <br>";
  } else {
    echo "algo ha salido mal, puede que el jugador ya no exista <br>";}

    $showplayers= mysqli_query($conect, "select name from players;");
    
    echo '<form action="" method="POST">';
    echo '<select class="center" name="nombre">';
     while($table = mysqli_fetch_array($showplayers)) { 
      echo('<option values='.$table['name'].">".$table['name']."</option>");  
     }
    echo '</select>';
    echo '<input type="submit" name="submit" value="Borrar jugador"><br><br>
    </form>';
}


else{
  $showplayers= mysqli_query($conect, "select name from players;");
  
  echo '<form action="" method="POST">';
  echo '<select  class="center" name="nombre">';
   while($table = mysqli_fetch_array($showplayers)) { 
    echo('<option values='.$table['name']. ">".$table['name']."</option>");  
   }
  echo '</select>';
  echo '<input type="submit"  name="submit" value="Borrar jugador"><br><br>
  </form>';

}
  
 // Cerrar la conexión
mysqli_close($conect);
?>
<a href="index.php">Volver atrás</a>
</div>
