<?php
    $ftp_server = "";
      $conn_id = ftp_connect($ftp_server);
      $ftp_user_name = "";
      $ftp_user_pass = "";
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      $contents = ftp_nlist($conn_id, '');


	  function arrayfilter($elements, $query)
	  {
		$found = [];
		$query = strtolower($query);
		foreach($elements as $element)
		{
		  $current = strtolower($element);
		  if(strpos($current, $query) !== false) {
			array_push($found, $element);
		  }
		}
		return $found;
	  } 

	 // Listar los archivos filtrando  stock y price
	 $listaStock= arrayFilter($contents, 'stock');
	 $listaPrice= arrayFilter($contents, 'price');

	 // Obtener el ultimo archivo con tal nombre(si es stock ultimo archivo stock)
	 $lastElementStock = end($listaStock);
	 $lastElementPrice = end($listaPrice);
	 

	 // Fijar hora del ultimo archivo
	 $last_modStock = ftp_mdtm($conn_id, $lastElementStock);
	 $last_modPrice = ftp_mdtm($conn_id, $lastElementPrice);


	 function MostrarFecha($entero,$ultimoElemento) 
	 {
		if ($entero != -1) {
		 
			echo date("F d Y H:i:s.", $entero).". <br/> <br/>";
		}
		else {
			echo "<br>could not get last modified.";
		}
	 }

	 function BorrarRuta($archivoCompleto) 
	 {
	     echo"<a href=".substr($archivoCompleto,1).">".substr($archivoCompleto,24) .  "<br/> <br/> </a>";
	 }
        
    ftp_close($conn_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head> 
<body>

<center>
<table Border>

  <tr>
    <th scope="col">Nombre del proceso</th>
    <th scope="col">Ultima fecha que corrio</th>
  </tr>

  <tr>
    <td> <?php  BorrarRuta($lastElementStock); ?>
    <td> <?php 	MostrarFecha($last_modStock,$lastElementStock); ?> </td>  
  </tr>

  <tr>
    <td><?php  BorrarRuta($lastElementPrice); ?></td>
    <td> <?php MostrarFecha($last_modPrice,$lastElementPrice); ?> </td>
  </tr>
</table>
	</center>

</body>
</html>






