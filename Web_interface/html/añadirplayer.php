<html>

<link rel="stylesheet" href="cssphp.css">
<body>
<h1>Añade un jugador</h1>
<h2>El nombre debe de ser individual y caracteristico de cada jugador</h2>
  <!--barra lateral-->
<div class="panelcentral">
<br>

<!--formulario de añadir el jugador-->
<form  action="" method="POST">
Nombre del jugador: <input type="text" name="nombre" ><br><br>
Numero del jugador: <input type="text" name="id" ><br><br>
<input type="submit" name="submit" value="crear"><br><br>
</form>

<a href="index.php">Volver atrás</a>

<br>
<!-- inicio de la tabla -->
<table>
<tr>
<th>ID</th>
<th>Nombre</th>
</tr>
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
//consulta para seleccionar los jugadores
$select='select * from players order by id';
//formatear el nombre e ID del jugador
$nombre_jugador='"'.$_POST["nombre"].'"';
$id_jugador='"'.$_POST["id"].'"';
//consulta para hacer el insert
$insert="insert into players values (".$id_jugador.",".$nombre_jugador.")";

//si el insert de hace poner ese mensaje
  if (mysqli_query($conect,$insert) === TRUE) {
    echo "<br><br> <p>Se ha insertado el usuario correctamente</p>";
  } else {
    echo " <br><br> Algo ha salido mal, comprueba  que el numero de usuario no este repetido y el nombre y prueba de nuevo <br><br>";
  }

$result = mysqli_query($conect,$select);
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //calculo de retenciones y sueldo neto
    
    //genero  la tabla
    echo "
    <tr>
      <td>".$row["id"]."</td>
      <td>".$row["name"]."</td>
    </tr>";
  }
} else { echo "No se ha añadido ningun jugador todavia <br>";}
} 
else {
  $select='select * from players order by id';
  $result = mysqli_query($conect,$select);
  // output data of each row
  while($row = $result->fetch_assoc()) {
    
    //genero  la tabla
    echo "<tr>
      <td>".$row["id"]."</td>
      <td>".$row["name"]."</td>
        </tr>";
  
}
}
 // Cerrar la conexión
mysqli_close($conect);
?>
</table>
</div>

</body>
</html>

