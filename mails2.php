<?php
	 require "PHPMailer/Exception.php";
	 require "PHPMailer/PHPMailer.php";
	 require "PHPMailer/SMTP.php";

	 use PHPMailer\PHPMailer\PHPMailer;
	 use PHPMailer\PHPMailer\Exception;
	 use PHPMailer\PHPMailer\SMTP;


      $ftp_server = "nombre_Server";
      $conn_id = ftp_connect($ftp_server);
      $ftp_user_name = "usuarioServer";
      $ftp_user_pass = "contraseñaServer";
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      $contents = ftp_nlist($conn_id, 'ruta_archivo');  

	  $fecha_actual = strtotime(date("d-m-Y",time()));
	  $fecha_actualTamp = strtotime(date("d-m-Y"));


	 // Setear a hora argentina
	 date_default_timezone_set('America/Araguaina');


	  function MandarMail($mensaje,$objeto)
	  {
		$oMail = new PHPMailer();
		$oMail->isSMTP();
		$oMail->Host="smtp.ProveedorSmtp.com";
		$oMail->Port=587;
		$oMail->SMTPSecure="tls";
		$oMail->SMTPAuth=true;
		$oMail->Username= "Email";
		$oMail->Password ="";
		$oMail->setFrom("AdressDeDonde","");
		$oMail->addAddress("","In");
		

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
		  if(strpos($current, $query) !== false)
          {
			array_push($found, $element);
		  }
		}
		return $found;
	  } 

	 // Listar los archivos filtrando  stock y price
	 $listaStock= arrayFilter($contents, 'stock');
	 $listaPrice= arrayFilter($contents, 'price');

	 $DateAndTime = date('m-d-Y h:i:s a', time());  
	 

$fecha_actual = date("d-m-Y");
$fecha_ayer = date("d-m-Y",strtotime($fecha_actual."- 1 days"));
$fecha_AyerTamp = strtotime($fecha_ayer);

$fechaActualll = date("d-m-Y h:i:s a");


$fecha4HorasAntes = date( "d-m-Y H:i:s", strtotime( $fechaActualll )-4*60*60 );
$fecha2HorasAntes = date( "d-m-Y H:i:s", strtotime( $fechaActualll )-2*60*60 );


$fecha4HorasAntesTamp = strtotime($fecha4HorasAntes);
$fecha2HorasAntesTamp = strtotime($fecha2HorasAntes);
	

	 // Obtener el ultimo archivo con tal nombre(si es stock ultimo archivo stock)
	 $lastElementStock = end($listaStock);
	 $lastElementPrice = end($listaPrice);
	 

	 // Fijar hora del ultimo archivo
	 $last_modStock = ftp_mdtm($conn_id, $lastElementStock);
	 $last_modPrice = ftp_mdtm($conn_id, $lastElementPrice);

	 function BorrarRuta($archivoCompleto) 
	 {
	     echo"<a href=".substr($archivoCompleto,1).">".substr($archivoCompleto,6) .  "<br/> <br/> </a>";
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
<table  Border>

<thead>
  <tr>
    <th scope="col">Nombre del proceso</th>
    <th scope="col">Ultima fecha que corrio</th>
  </tr>
 </thead>

  <tr>
    <td> <?php  BorrarRuta($lastElementStock); ?>
    <td class="tcellStock" > <?php  
    
			$ultimaFechaStock = "<b>".date("d-m-Y h:i:s a", $last_modStock)."</b>";
			$ultimaFechaStockFunc = date("d-m-Y h:i:s a", $last_modStock);

            $fecha3HorasDespues = date( "d-m-Y H:i:s", strtotime( $ultimaFechaStockFunc)+3*60*60 );
 
            $corrioHaceMasDe1Dia = $last_modStock < $fecha_AyerTamp;
            $corrioHaceMasDe4Horas = $last_modStock < $fecha4HorasAntesTamp;
            
            $objetoStock = "Error proceso Stock"; 
            $objetoStockWarning = "Warning!!!";    

            $ultimo = "<b>".substr($lastElementStock,24,18)."</b>";
            $mensajeStock="El proceso <br/> ".$ultimo." <br/> no esta corriendo actualmente. <br/> 
                La ultima fecha que se corrio fue el <br/> ". $ultimaFechaStock;

            $mensajeWarningStock="El proceso <br/> ".$ultimo." <br/> no esta corriendo con regularidad. <br/> 
                    La ultima fecha que se corrio fue el <br/> ". $ultimaFechaStock. "Tendria que correr el".$fecha3HorasDespues." ". "aproximadamente";
            

            switch($last_modStock)
            {
                case $corrioHaceMasDe1Dia: 
                    echo "  La ultima fecha que corrio fue el <br/>
				    ". $ultimaFechaStock."  <style> .tcellStock { background: #ff4747; } </style> ";
	                MandarMail($mensajeStock,$objetoStock);
                break;
                case $corrioHaceMasDe4Horas :
                    echo " La ultima fecha que corrio fue el <br/>
				    ". $ultimaFechaStock."  <style> .tcellStock { background: #fcfb00; } </style> ";
	                MandarMail($mensajeWarningStock,$objetoStockWarning);
                break;
                default :                  
                    echo "  El proceso stock corrio perfectamente. ultima fecha que corrio fue el <br/>
			    	". $ultimaFechaStock."  
                    <style> table { width:1300px; border-collapse: collapse; }
                     table { height:200px; border-collapse: collapse; }
                     .tcellStock { background: #00FF00; }
                    </style> ";
            }

	    ?>
    </td>  
</tr>
        

  <tr>
    <td><?php  BorrarRuta($lastElementPrice); ?></td>
    <td class="tcellPrice"> <?php 
			$ultimaFechaPrice = "<b>".date("d-m-Y h:i:s a", $last_modPrice)."</b>";
			$ultimaFechaPriceFunc = date("d-m-Y h:i:s a", $last_modPrice);
            $fecha1HorasDespues = date( "d-m-Y H:i:s", strtotime( $ultimaFechaPriceFunc)+1*60*60 );
 
           // Condiciones  
            $corrioHaceMasDe1DiaPrice = $last_modPrice < $fecha_AyerTamp;
            $corrioHaceMasDe2Horas = $last_modPrice < $fecha2HorasAntesTamp;

            $objetoPrice = "Error proceso Price"; 
            $objetoPriceWarning = "Warning!!!";    

            $ultimo = "<b>".substr($lastElementPrice,24,18)."</b>";
            $mensajePrice="El proceso <br/> ".$ultimo." <br/> no esta corriendo actualmente. <br/> 
                La ultima fecha que se corrio fue el <br/> ". $ultimaFechaPrice;

            $mensajeWarningPrice="El proceso <br/> ".$ultimo." <br/> no esta corriendo con regularidad. <br/> 
                    La ultima fecha que se corrio fue el <br/> ". $ultimaFechaPrice. "Tendria que correr el".$fecha1HorasDespues." ". "aproximadamente";
            

            switch($last_modPrice)
            {
                case $corrioHaceMasDe1DiaPrice: 
                    echo "  La ultima fecha que corrio fue el <br/>
                     ". $ultimaFechaPrice."  <style>  .tcellPrice { background: #ff4747; }</style> ";
	                MandarMail($mensajePrice,$objetoPrice);
                break;
                case $corrioHaceMasDe2Horas :
                    echo "  La ultima fecha que corrio fue el <br/>
                     ". $ultimaFechaPrice."  <style> .tcellPrice { background: #fcfb00; } </style> ";
                    MandarMail($mensajePrice,$objetoPriceWarning);
                break;
                default :
                    echo "  El proceso price corrio perfectamente. ultima fecha que corrio fue el <br/>
                     ". $ultimaFechaPrice."  <style> .tcellPrice { background: #00FF00; } </style> ";
            }
    
    ?>       </td>
         </tr>

        </td>
  </table>
 </center>

</body>
</html>







