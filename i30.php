<pre>
<?PHP
echo "\ni30 Parser ver.080819   2do A/B Larm";

## Honeywell i30 .DEF file parser ##
## ANALOG/DIGITAL I/O Detection ##############
#
# 
#
# På digitala kort så är Digital OUT adresserna 21-32.
# På analoga kort så är Analog OUT adresserna 61-68.
#
# 01 -> 16  DI
# 21 -> 32  DO
# 41 -> 48  AI 
# 61 -> 68  AO 
#
#_______________________________________________________

function kanaltyp($n){
	#Hämtar kanaltyp för ett kanalnummer
	$n="??"; #Om inget matchas
	if ($n <= 16){ 				return "DI";}
	if ($n >= 21 && $n <= 32){ 	return "DO";}
	if ($n >= 41 && $n <= 48){ 	return "AI";}
	if ($n >= 61 && $n <= 68){ 	return "AO";}
}


if ($fd = fopen ("8908391.DEF", "r")){  # Öppna filen & loopa radvis
	while (!feof ($fd)){
	    $line = fgets($fd, 4096);	$buff.=$line;
	    $kommentar="";$typ="";
		
	    if (preg_match("/IO\d+/i",$line,$found)){	#IO Sektionen: t.ex IO200
	    	$IOsection = "$found[0]";
		}
		
		if (preg_match("/\t?\d{1,2} \w{2}/i",$line,$found)){#Normal rad.. siffror
			$IOkanal = substr(trim("$found[0]"),0,2);
			$typ=kanaltyp($IOkanal);
			$IOnum = substr($IOsection,2)+$IOkanal; 		# strippa IO, addera integers	
			$IOnum = str_pad($IOnum, 4, "0", STR_PAD_LEFT); #zero fill på 4 tecken
		
			if (preg_match('/".+"/', $line,$match)) {
				$kommentar=$match[0];
			}	 	
			
			if ($kommentar !=""){
				echo "\n$typ$IOnum $kommentar";
			}
		}
		
		if (preg_match("/(LD\d{1,2})\s\w/i",$line,$found)){	#Larm (LD) blir AL
			#echo "\nLARM: $found[0]";
			$IOkanal = trim($found[0]);
			#$nummer = substr($IOkanal,2);
			$IOnum = substr($IOsection,2) + substr($IOkanal,2); # strippa IO, addera integers	
			$IOnum = "AL".str_pad($IOnum, 4, "0", STR_PAD_LEFT); #zero fill på 4 tecken
			
			if (preg_match('/".+"/', $line,$match)) {
				$kommentar=$match[0];
			}	 
		
			if ($kommentar !=""){
				echo "\n$IOnum $kommentar";
			}
		}
	}
}	
fclose ($fd); 

/*
$buff= str_replace("\t", " ", "$buff");
$buff= str_replace("  ", " ", "$buff");
#$sorted= str_replace("TEMPO", "\nTEMPO", "$sorted");

#echo "$buff";

function grep($from,$to,$haystack){
	$pattern = "#$from (.*?)$to#s";
	if (preg_match($pattern, $haystack,$match)) {
		return $match[0];
	} 
}

*/

?>