<?php
	require "PHPMailer/Exception.php";
	require "PHPMailer/PHPMailer.php";
	require "PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;


    $ftp_server = "heradata";
      $conn_id = ftp_connect($ftp_server);
      $ftp_user_name = "appltest";
      $ftp_user_pass = "appltest";
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      $contents = ftp_nlist($conn_id, '/sqlcom/TEST/EjemploFTP');

	  $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
	 $fecha_actualTamp = strtotime(date("d-m-Y"));
	 $fechaAyerTamp = strtotime(date("d-m-Y",strtotime($fecha_actual."- 1 days")));

	  function MandarMail($mensaje,$objeto)
	  {
		$oMail = new PHPMailer();
		$oMail->isSMTP();
		$oMail->Host="smtp.gmail.com";
		$oMail->Port=587;
		$oMail->SMTPSecure="tls";
		$oMail->SMTPAuth=true;
		$oMail->Username= "kevsphil@gmail.com";
		$oMail->Password ="Herbyson26";
		$oMail->setFrom("kevsphil@gmail.com","Lollllllll");
		$oMail->addAddress("kevsphil@gmail.com","viste");
		$oMail->Subject=$objeto;
		$oMail->msgHTML($mensaje);
	
		if(!$oMail->send())
		{
			echo $oMail->ErrorInfo;
		}  
	  }


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

	 $DateAndTime = date('m-d-Y h:i:s a', time());  
	 //echo "The current date and time are $DateAndTime.";

	 //print_r($DateAndTime-1);

	 $fecha = "2022-07-30T20:55:20";
	 $fechaComoEntero = strtotime($fecha);


/*print_r($fechaComoEntero);
	 $hoy = getdate();
print_r($hoy); */


/*$fecha_entrada = strtotime("19-11-2023 21:00:00");
	
if($fecha_actual > $fecha_entrada)
{
	echo "La fecha actual es mayor a la comparada.";
}else
		{
		echo "La fecha actual es menor a la fecha comparada";
		}
	
*/









$fecha_actual = date("d-m-Y");
$loll = date("y-d-m",strtotime($fecha_actual."- 1 days"));


	

	 // Obtener el ultimo archivo con tal nombre(si es stock ultimo archivo stock)
	 $lastElementStock = end($listaStock);
	 $lastElementPrice = end($listaPrice);
	 

	 // Fijar hora del ultimo archivo
	 $last_modStock = ftp_mdtm($conn_id, $lastElementStock);
	 $last_modPrice = ftp_mdtm($conn_id, $lastElementPrice);


	 function MostrarFecha($entero) 
	 {
		if ($entero != -1) {
		 
			echo date("y-d-m", $entero).". <br/> <br/>";
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
    <td> <?php 	MostrarFecha($last_modStock); 
			if($fechaAyerTamp || $fecha_actualTamp != $last_modStock)
			{ 
                $objetoStock = "Error proceso Stock";
                $ultimo = "<b>".substr($lastElementStock,24,18)."</b>";
                $ultimaFecha = "<b>".date("m-d-Y h:i:s a", $last_modStock)."</b>";
                $mensajeStock="El proceso <br/> ".$ultimo." <br/> no esta corriendo actualmente. <br/> 
                La ultimafecha que se corrio fue el <br/> ". $ultimaFecha;
	            MandarMail($mensajeStock,$objetoStock);	
			}
			else{
					echo "El proceso stock corrio perfectamente ayer";
				}
	    ?>
    </td>  
  </tr>

  <tr>
    <td><?php  BorrarRuta($lastElementPrice); ?></td>
    <td> <?php MostrarFecha($last_modPrice);
    	if($fechaAyerTamp || $fecha_actualTamp != $last_modPrice)
        { 
            $objetoPrice = "Error proceso Price";
            $ultimo = "<b>".substr($lastElementPrice,24,18)."</b>";
            $ultimaFecha = "<b>".date("m-d-Y h:i:s a", $last_modPrice)."</b>";
            $mensajePrice="El proceso <br/> ".$ultimo." <br/> no esta corriendo actualmente. <br/> 
            La ultimafecha que se corrio fue el <br/> ". $ultimaFecha;
            MandarMail($mensajePrice,$objetoPrice);	
        }
        else{
                echo "El proceso stock corrio perfectamente ayer";
            }
    
    ?> </td>
  </tr>
</table>
	</center>

</body>
</html>






