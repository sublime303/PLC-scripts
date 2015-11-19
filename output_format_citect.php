<?php

#$out_variable="out_variable.DBF";

/*
 * 
CITECT DB FORMAT /Ben
Table Format: dBase III
Records Number: 32 (33)
Fields Number: 16
Header Size: 545
Record Length: 328

#  Field Name Field Type Field Size 
-- ---------- ---------- ---------- 
1  NAME       Character  79         
2  TYPE       Character  16         
3  UNIT       Character  16         
4  ADDR       Character  64         
5  RAW_ZERO   Character  11         
6  RAW_FULL   Character  11         
7  ENG_ZERO   Character  11         
8  ENG_FULL   Character  11         
9  ENG_UNITS  Character  8          
10 FORMAT     Character  11         
11 COMMENT    Character  48         
12 EDITCODE   Character  8          
13 LINKED     Character  1          
14 OID        Character  10         
15 REF1       Character  11         
16 REF2       Character  11         
 *  
 * 
 */

$format_citect_variable_dbf = array(
  array("NAME","C",79),
  array("TYPE","C",16),
  array("UNIT","C",16),
  array("ADDR","C",64),
  array("RAW_ZERO","C",11),
  array("RAW_FULL","C",11),
  array("ENG_ZERO","C",11),
  array("ENG_FULL","C",11),
  array("ENG_UNITS","C",8),
  array("FORMAT","C",11),
  array("COMMENT","C",48),
  array("EDITCODE","C",8),
  array("LINKED","C",1),
  array("OID","C",10),
  array("REF1","C",11),
  array("REF2","C",11)
  );
  
  
  
/*
Table Information
digalm.DBF

Table Format: dBase III
Records Number: 47
Fields Number: 20
Header Size: 673
Record Length: 1498

#  Field Name Field Type Field Size 
-- ---------- ---------- ---------- 
1  TAG        Character  79         
2  NAME       Character  79         
3  DESC       Character  127        
4  VAR_A      Character  254        
5  VAR_B      Character  254        
6  CATEGORY   Character  16         
7  HELP       Character  64         
8  PRIV       Character  16         
9  AREA       Character  16         
10 COMMENT    Character  48         
11 SEQUENCE   Character  16         
12 DELAY      Character  16         
13 CUSTOM1    Character  64         
14 CUSTOM2    Character  64         
15 CUSTOM3    Character  64         
16 CUSTOM4    Character  64         
17 CUSTOM5    Character  64         
18 CUSTOM6    Character  64         
19 CUSTOM7    Character  64         
20 CUSTOM8    Character  64         
*/
$format_citect_digalm_dbf = array(
  array("TAG","C",79),
  array("NAME","C",79),
  array("DESC","C",127),
  array("VAR_A","C",254),
  array("VAR_B","C",254),
  array("CATEGORY","C",16),
  array("HELP","C",64),
  array("PRIV","C",16),
  array("AREA","C",16),
  array("COMMENT","C",48),
  array("SEQUENCE","C",16),
  array("DELAY","C",16),
  array("CUSTOM1","C",64),
  array("CUSTOM2","C",64),
  array("CUSTOM3","C",64),
  array("CUSTOM4","C",64),
  array("CUSTOM5","C",64),
  array("CUSTOM6","C",64),
  array("CUSTOM7","C",64),
  array("CUSTOM8","C",64)
  );  
  
  
  

echo "<pre>";
  print_r($def);
  
// creation
if (!dbase_create("out_variable.DBF", $format_citect_variable_dbf)) {echo "Error, can't create the database\n";}else{echo "made the file ok!";$db = dbase_open("out_variable.DBF", 2);}
if (!dbase_create("out_digalm.DBF", $format_citect_digalm_dbf)) 	{echo "Error, can't create the database\n";}else{echo "made the file ok!";$db_digalm = dbase_open("out_digalm.DBF", 2);}



echo "Start...<pre>";flush();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);




$ioDev = 'A1148AS4';

$dbfFile = "inputDbf.txt";
$fpDBF  = fopen($dbfFile, "r" );

$ioListFile = "ioList.txt";

$fpVariable = fopen('variable.txt', "w");
$fpTrend = fopen('trend.txt', "w");
$fpDigalm = fopen('digalm.txt', "w");
$what=0;

while (!feof($fpDBF))
{

	$buffer = fgets($fpDBF, 4096);
	$varDBF = explode('	',$buffer);
	$varDBF[2] = $ioDev;
	$varDBF[3] = '???';
	$fpIO  = fopen($ioListFile, "r" );
	$adress = '';
	while (!feof($fpIO))
	{
		$buffer = fgets($fpIO, 4096);
		$buffer = str_replace('	','',$buffer);
		$buffer = str_replace(' ','',$buffer);
		$varIO = explode('AT%',$buffer);

		if($varIO[0]==$varDBF[0])
		{

			$adress = explode(':',$varIO[1]);
			$array = explode('[0..',$adress[1]);
			if(strstr($array[0],'ARRAY'))
			{
				$array = explode(']',$array[1]);
        			$num = $array[0] + 1;
        			if($num==5)
          			$num = 10;
				$adress[0] = ''.$adress[0].'['.$num.']';
			}
      				elseif(strstr($array[0],'REAL'))
			{
        			$varDBF[1]='REAL';
      			}
				$varDBF[3] = $adress[0];
				break;
		}
	}

	$varDBF[0] = str_replace('.','_',$varDBF[0]);
	$varDBF[0] = str_replace('-','_',$varDBF[0]);

	$last = sizeof($varDBF)-1;
	$varDBF[10] = str_replace(' '.$varDBF[0].'','',$varDBF[10]);

	if($varDBF[1]=='DIGITAL' && (stristr($varDBF[0],'_HL') || stristr($varDBF[0],'_COML') || stristr($varDBF[0],'_SL') || stristr($varDBF[0],'_L') || stristr($varDBF[0],'_AL') || stristr($varDBF[0],'_HHL') || strstr($varDBF[0],'_GFL')) && !strstr($varDBF[0],'_MAN') && !strstr($varDBF[0],'_I') && !strstr($varDBF[0],'_DI'))
	{//Larm, _HLL, _HHL, _LL, _LLL, fastnar på _HL och _L
		$cat = 2;
		$help = 'Huvudmeny';
		$digalmDBF = array($varDBF[0],$varDBF[0],$varDBF[10],$varDBF[0],'',$cat,$help,$varDBF[$last]);
		print_r ($digalmDBF);
		dbase_add_record($db_digalm, $digalmDBF);
		fwrite($fpDigalm, implode(chr(9),$digalmDBF));
	}
  	if(strstr($varDBF[0],'_KP'))
	{//
    		$varDBF[4] = '0.01';
    		$varDBF[5] = '100';
  	}
  		elseif(strstr($varDBF[0],'_KI'))
	{//
    		$varDBF[4] = '0.1';
    		$varDBF[5] = '300';
  	}
  		elseif(strstr($varDBF[0],'_KD'))
	{//
    		$varDBF[4] = '0';
    		$varDBF[5] = '300';
  	}
  	elseif(strstr($varDBF[0],'_KS'))
	{//
    		$varDBF[4] = '1';
    		$varDBF[5] = '60';
  	}

	if($varDBF[8]!='')
	{//trend

		if($varDBF[8]=='°C')
			$varDBF[9] = '###.#EU';
		elseif($varDBF[8]=='%')
			$varDBF[9] = '###EU';
		elseif($varDBF[8]=='kW')
			$varDBF[9] = '###.#EU';
		elseif($varDBF[8]=='RPM')
			$varDBF[9] = '#####EU';
		elseif($varDBF[8]=='A')
			$varDBF[9] = '###.#EU';
		elseif($varDBF[8]=='%Rh' || $varDBF[8]=='%RH')
			$varDBF[9] = '###EU';
		else
			$varDBF[9] = '####.#EU';

		/*if(strstr($varDBF[1],'GT8'))//frysvakt
			$sample = '00'.chr(58).'00'.chr(58).'10';
		elseif(strstr($varDBF[1],'GP'))//frysvakt
			$sample = '00'.chr(58).'01'.chr(58).'00';
		elseif(strstr($varDBF[1],'SV'))//frysvakt
			$sample = '00'.chr(58).'00'.chr(58).'30';
		else*/
		if(!strstr($varDBF[0],'_X') && !strstr($varDBF[0],'_Y'))
		{
			$sample = '33'.chr(58).'33'.chr(58).'33';

			$fileName = ''.chr(91).'data'.chr(93).''.chr(58).''.chr(92).''.$varDBF[2].''.chr(92).'';

			$files = '12';
			$period = '1st';
			$type = 'TRN_PERIODIC';
			$store = 'Floating Point(8-byte samples)';
			$trendDBF = array($varDBF[0],$varDBF[0],'',$sample,'','','','',$fileName,$files,'', $period,$comment,$type,'','','','','','','',$store,$varDBF[$last]);
			##dbase_add_record($db_digalm, $trendDBF);
			fwrite($fpTrend, implode(chr(9),$trendDBF));

		}
	}
	if(strstr($varDBF[10],'I-tid'))//nån tid, kanske enhet?
	{
		$varDBF[8] = 'sec';
		$varDBF[9] = '#####EU';
	}
	elseif(strstr($varDBF[10],'D-tid'))//nån tid, kanske enhet?
	{
		$varDBF[8] = 'sec';
		$varDBF[9] = '#####EU';
	}
	elseif(strstr($varDBF[10],'Samplingstid'))//nån tid, kanske enhet?
	{
		$varDBF[8] = 'sec';
		$varDBF[9] = '#####EU';
	}
	
	$varDBF[14]="";$varDBF[15]="";
	#print_r ($varDBF);
	$c++;$cnt++;echo ".";if($c>100){echo "<br>";$c=0;}
	flush();
	dbase_add_record($db, $varDBF);   
	fwrite($fpVariable, implode(chr(9),$varDBF));
	
}

fclose($fpVariable);
fclose($fpTrend);
fclose($fpDigalm);
fclose($fpDBF);

dbase_close($db);#bEN
dbase_close($db_digalm);#bEN
echo "\nKLAR! $cnt taggar";
?>