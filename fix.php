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


if ($fd = fopen ("final_fdx_fdx_fix.gdb", "r")){  # Öppna filen & loopa radvis
	while (!feof ($fd)){
	    $line = fgets($fd, 4096);	$buff.=$line;


		if (preg_match("/:: UW/i",$line,$found)){#Normal rad.. siffror
			$line=str_replace("____-","",$line);
		}
		echo $line;

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