<?php
	try{	
		$directory = 'pdf/';
		
		//Load all files into an array
		$files = array();
		foreach (scandir($directory) as $file) {
			if ($file !== '.' && $file !== '..' && strpos($file, 'Invoice_Voelkner') !== false) {
				$files[] = $directory . $file;
			}
		}
		//var_dump($files);
	 
		// Include Composer autoloader if not already done.
		include 'vendor/autoload.php';
		 
		// Parse pdf file and build necessary objects.
		$parser = new \Smalot\PdfParser\Parser();
		
		
		//Cycle throught all files and rename them
		foreach($files as $file){
			$pdf = $parser->parseFile($file);
			
			$text = $pdf->getText();
		
			//Cut everything before "Ihre Bestellung" and cut "Ihre Bestellung"
			$ordernumber = substr($text, strpos($text, 'Ihre Bestellung') + 16);
			//Cut everything after order number
			$ordernumber = substr($ordernumber, 0, strpos($ordernumber, "\n"));
			
			
			rename ($file, $directory . $ordernumber . '.pdf');
			
			echo "Renamed " . $file . " to " . $ordernumber . ".pdf!<br>";
		}
	}
	catch(Exception $e){
		echo "Exception:<br><br> ";
		echo "<pre>";
		var_dump($e);
		echo "</pre>";
	}