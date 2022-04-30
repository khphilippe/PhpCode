

<?php

      $ftp_server = "";
      $conn_id = ftp_connect($ftp_server);
      $ftp_user_name = "";
      $ftp_user_pass = "";
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      $contents = ftp_nlist($conn_id, '');


	  function arrayfilter($elements, $query) {
		$found = [];
		$query = strtolower($query);
		foreach($elements as $element) {
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
        
	 if ($last_modStock != -1) {
		 
		 // Checking whether any error occurred or not
		 // while retrieving last modified data
		 echo "<br> $lastElementStock was modified on ".
				 date("F d Y H:i:s.", $last_modStock).". <br/> <br/>";
	 }
	 else {
		 echo "<br>could not get last modified.";
	 }

	 if ($last_modPrice != -1) {
		 
		// Checking whether any error occurred or not
		// while retrieving last modified data
		echo "<br> $lastElementPrice was modified on ".
				date("F d Y H:i:s.", $last_modPrice).".";
	}
	else {
		echo "<br>could not get last modified.";
	}

      ftp_close($conn_id);


?>




