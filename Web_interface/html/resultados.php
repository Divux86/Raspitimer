<html>
<link rel="stylesheet" href="cssphp.css">
<body>
  <!--barra lateral-->
<div class="panelcentral">
<h1>Resultados de la carrera</h1>

<a href="index.php">Volver atrás</a>
<br><br>

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
//convertir el nombre de la carrera en su ID   
$nombre='"'.$_POST["nombre"].'"';
//seleccionar la id de carrera segun el nombre
$selectid= 'select id_race from races where name = '.$nombre;
//sacar la ID de la carrera a raiz del nombre y poniendola en una variable
$result = mysqli_query($conect,$selectid);
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc() ) {
    //genero  la tabla
    $idcarrera=$row["id_race"];
  }
}

//select de los tiempos
  $selectresults = "select p.name , r.id_jugador , SUM(r.time) as 'ttotal', MIN(r.time) as 'tmejor' from resultados
   r left join players p on p.id = r.id_jugador where r.id_carrera = ".$idcarrera." group by id_jugador order by ttotal;";
//consulta de los tiempos
  $result = mysqli_query($conect,$selectresults);

?>

<table class='center'>
<tr>
      <th>Numero de jugador</th>
      <th>Nombre jugador</th>
      <th>Total</th>
      <th>Best Time</th>
</tr>

<?php
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      //genero  la tabla
      echo 
      "<tr>
      <td>".$row["id_jugador"]."</td>
        <td>".$row["name"]."</td>
        <td>".$row["ttotal"]."</td>
        <td>".$row["tmejor"]."</td>
      </tr>";
    }
  }
}
  // Cerrar la conexión
  mysqli_close($conect);
?>
</table><br><br>
</div>
</body>
</html>

