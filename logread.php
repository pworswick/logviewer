<html>
<head>    </head>

<body>

<?php

/*

https://docs.microsoft.com/en-us/previous-versions/windows/it-pro/windows-server-2008-R2-and-2008/cc771748(v=ws.10)?redirectedfrom=MSDN

*/

@$period=(int)$_GET['period'];

if (! $period)
    $period = date("Ym");

print "<form name=form1>
Select Period <select onchange='document.form1.submit();' name=period>";

$months = array(0,"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

for ($yr=date("Y");$yr>=2020;$yr--)
    {
    if ($yr == date("Y"))
        $endmth = date("m")+0;
        else 
        $endmth = 12;
        
    for ($mth = $endmth; $mth >=1; $mth--)
        {
        print "<option value=".($yr*100 + $mth);
        
        if ($yr*100+$mth == $period)
            print " selected";
        
        print ">".$months[$mth]." ".$yr."</option>\n";
        }
    
    
    }
print "</select>\n</form>\n";

function mydiff($a,$b)
{
//$dtNow = strtotime($a);
//$dtToCompare = strtotime($b);

//$diff = $dtToCompare - $dtnow;


return strtotime($b)-strtotime($a);



}


function sectohms($a)
{
$hr = floor($a/3600);

$a = $a - $hr * 3600;

$min = floor($a/60);

$a = $a - $min * 60;

return sprintf("%d:%02d:%02d",$hr,$min,$a);

}

function nf($a,$b=0)
{
return number_format($a,$b);
}

//$period = 2003;

$x = $period-200000;



$f = fopen("c:\\windows\\system32\\Logfiles\\IN".$x.".log", "r");

if (! $f)
    die("Could not open");
    
while (($data = fgetcsv($f, 1000, ",")) !== FALSE)
    {
    //if ($data[37]!="")   // Session time
   //     {
    //    $result[]=array($data[2],$data[3],$data[5],$data[37],$data[34],$data[33]);   // Date, Time, Username and Session time ,data in, data out
    //    
    //  $data[35]    Session ID
     //   
    //    }
    // if ($data[23]=="" || $data[23]=="5") // Auth Type
    if ($data[33]!="" && $data[34]!="")
        {
        $sum[$data[35]]=$data[33]+$data[34];
        }
    
    
    if (! ($data[23]=="4" && $data[24]==""))
        {
        switch ($data[4])  // Packet Type
            {
            case 1:
                if (@!$result[$data[35]])   
                    $result[$data[35]] = array($data[2],$data[3],$data[5],"","");        // Date, Time, Username  ,data in, data out
                    break;
                    
            case 4:
                $result[$data[35]][3]=$data[2];
                $result[$data[35]][4]=$data[3];
                break;
            }
         } // End if
        
    } // End While
    

fclose($f);


/*
print "<pre>";
print_r($result);
print "</pre>";

die();
*/

$olddate= "";
print "<table>\n<tr valign=top><th>Name</th><th>Start Time</th><th>End Time</th><th>Duration <br/>h:mm:ss</th><th>Data Transferred</th></tr>\n";


foreach ($result as $key=>$arr)
    {
    if ($arr[3]=="")
        continue;
    
   if (@$olddate <> @$arr[0])
        {
        print "<tr><td></td></tr>\n<tr><td colspan=5><b>".@$arr[0]."</b></tr>\n<tr><td>&nbsp;</td></tr>";
        $olddate = @$arr[0];
        }
        
    //$enddate = date( "H:i:s", strtotime( $arr[1]." + ".$arr[3]." seconds" ) );
        
    @print "<tr><td>".$arr[2]."</td><td>".$arr[1]."</td><td>".$arr[4]."</td><td align=right> ".sectohms(mydiff($arr[0]." ".$arr[1],$arr[0]." ".$arr[4])). "</td><td align=right>".nf($sum[$key],0)."</td></tr>\n";
    }

print "</table>\n";
//echo "Date 1  07/01/2020 07:43:44<br/>Date 2   7/01/2020  08:24:19<br/>";

//print strtotime("07/01/2020 07:43:44")."<br/>";
//print strtotime("7/01/2020  08:24:19");



/*
07/01/2020
 
KAPPA\pworswick	07:43:44	08:24:19	1593603824
*/
?>
</body>
</html>