
<?php
	$hostname = 'localhost';
	$username = 'root';
	$password = 'muhdazly';
	$dbname = 'calendar';

	$error = "Cannot connect to database, Please try again later...";

	mysql_connect($hostname,$username,$password) or die ($error);
	mysql_select_db($dbname) or die ($error);

	$error = 'Cannot connect to the database';

	$mysqli1 = new mysqli ( $hostname, $username, $password, $dbname ) or die ( $error );

?>
<html>
<head>
    <script>
        function goLastMonth(month, year){
            if(month == 1){
                --year;
                month = 13;
            }
            --month
            var monthstring =""+month+"";
            var monthlength = monthstring.length;
            if(monthlength <= 1){
                monthstring = "0"+monthstring;
            }
            document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;
        }

        function goNextMonth(month, year){
            if(month == 12){
                ++year
                month = 0;
            }
            ++month
            var monthstring =""+month+"";
            var monthlength = monthstring.length;
            if(monthlength <= 1){
                monthstring = "0"+monthstring;
            }
            document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;

        }
    </script>
</head>

<body>
<?php
if(isset($_GET['day'])){
    $day = $_GET['day'];
}else{
    $day = date("j");
}

if(isset($_GET['month'])){
    $month = $_GET['month'];
}else{
    $month = date("n");
}


if(isset($_GET['year'])){
    $year = $_GET['year'];
}else{
    $year = date("Y");
}

//calendar variable
$currentTimeStamp = strtotime("$year-$month-$day");
$monthName = date("F", $currentTimeStamp);
$numDays = date("t", $currentTimeStamp);
$counter = 0;
?>
<?php
//the code must be below the date variable
if(isset($_GET['add'])){
    $title = $_POST['txttitle'];
    $detail = $_POST['txtdetail'];

    $eventdate = $month."/".$day."/".$year;

    $sqlinsert = "insert into calendar(Title,Detail,eventDate,dateAdded) values ('".$title."','".$detail."','".$eventdate."',now())";
    $resultinsert = mysql_query($sqlinsert);
    if($resultinsert){
        echo "Event was successfully Added...";
    }else{
        echo "Event failed to be Added...";

    }

}
?>
<table border='1'>
    <tr>
        <td><input style='width:50px;' type='button' value='<' name='previousbutton' onclick="goLastMonth(<?php echo $month.",".$year?>)"></td>
        <td colspan='5'> <?php echo $monthName.", ".$year; ?> </td>
        <td><input style='width:50px;' type='button' value='>' name='nextbutton' onclick="goNextMonth(<?php echo $month.",".$year?>)"></td>
    </tr>
    <tr>
        <td width='50px'>Sun</td>
        <td width='50px'>Mon</td>
        <td width='50px'>Tue</td>
        <td width='50px'>Wed</td>
        <td width='50px'>Thu</td>
        <td width='50px'>Fri</td>
        <td width='50px'>Sat</td>
    </tr>
    <?php
    echo "<tr>";

    for($i = 1; $i < $numDays+1; $i++, $counter++){
        $timeStamp = strtotime("$year-$month-$i");
        if($i == 1) {
            $firstDay = date("w", $timeStamp);
            for($j = 0; $j < $firstDay; $j++, $counter++){
                //blank space
                echo "<td>$nbsp</td>";
            }
        }
        if($counter % 7 == 0){
            echo "</tr><tr>";
        }
        $monthstring = $month;
        $monthlength = strlen($monthstring);
        //sorry since it is a loop day string should get form $i
        $daystring = $i;
        $daylength = strlen($daystring);
        if($monthlength <= 1){
            $monthstring = "0".$monthstring;
        }
        if($daylength <= 1){
            $daystring = "0".$daystring;
        }
        echo"<td align='center'><a href='".$_SERVER['PHP_SELF']."?month=".$monthstring."&day=".$daystring."&year=".$year."&v=true'>".$i."</a></td>";


    }

    echo "</tr>";
    ?>
</table>
<?php
if(isset($_GET['v'])){
    echo "<a href='".$_SERVER['PHP_SELF']."?month=".$month."&day=".$day."&year=".$year."&v=true&f=true'>Add Event</a>";
    if(isset($_GET['f'])){
        include("eventform.php");
    }
}
?>
</body>
</html>
