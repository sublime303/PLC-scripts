<pre>
<?

# Exempel rad
#                               <------>:<--->;     (*-------------------------------------*)
#A1215_LB21_TF01_L			AT %MX1150.0: BOOL; 	(* A1215_LB21_TF01_L  Driftfel fläkt   *)
#A1215_LB21_U101_COML		AT %MX1150.1: BOOL; 	(* A1215_LB21_U101_COML  Komm.fel modbus frekv.omr.  *)


if ($fd = fopen ("AS05_Global_Variables.txt", "r")){  # Öppna filen & loopa radvis
	while (!feof ($fd)){
	    $line = fgets($fd, 4096);
	    $buff.=$line;
	    
	    
		/*
	    if (preg_match("/ESSA/i",$line,$var)){	
			preg_match("(\(-?[0-9]+ C)",$line,$val);
			echo "<br>TAF: $line";
			$v = substr($v,1);
			$v = substr($v,0,3);		
			$v = intval($v);
			$temp = $v;
			
			#echo $v.'<br>';
			#@dbq("INSERT INTO weather (dat, temp) VALUES (now(), '$temp')");
			
		}
		*/
	}
}	
fclose ($fd); 

$buff= str_replace("\t", " ", "$buff");
$buff= str_replace("  ", " ", "$buff");
#$sorted= str_replace("TEMPO", "\nTEMPO", "$sorted");

echo "$buff";

function grep($from,$to,$haystack){
	$pattern = "#$from (.*?)$to#s";
	if (preg_match($pattern, $haystack,$match)) {
		return $match[0];
	} 
}

echo "->".grep("ESSA","=",$buff);


?>