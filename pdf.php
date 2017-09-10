<?php
require "vendor/autoload.php";
include('includes/airports.php');

$faker = Faker\Factory::create();

use NumberToWords\NumberToWords;
$numberToWords = new NumberToWords();

$mpdf = new mPDF();


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    if($_POST['departure']!= $_POST['arrival'] && isset($_POST['start']) && isset($_POST['time']) && $_POST['price'] > 0 ){
        
        $dep = $_POST['departure'];
        $arr = $_POST['arrival'];
        $time = $_POST['time'];
        $start = $_POST['start']; // data wylotu
        $price = $_POST['price'];
        
        //znajduję strefę czasową
        $tzDep = searchTimeZone($dep, $airports);
        $tzArr = searchTimeZone($arr, $airports);
        
        // znajduje nazwe
        $airportDep = searchName($dep, $airports);
        $airportArr = searchName($arr, $airports);
        
    }
    else echo "Błędnie wpisane dane"; // nie rozdrabniam się na osobne komunikaty dla każdego pola.

}
// funkcja znajdująca timezone dla kodu. 
function searchTimeZone($place, $tab){
    
    $result = false;
    foreach($tab as $key => $value){
        
        if ($value['code'] == $place){
            $result = $value['timezone'];
        }
    }
        return $result;
    
}

$dateOfDep = new DateTime($start);
$tzDep2 = new DateTimeZone($tzDep);
$dateOfDep ->setTimezone($tzDep2);
$formatStart = $dateOfDep->format("d-m-Y H:i:s");

$tzArr2 = new DateTimeZone($tzArr);
$dateOfDep->modify("+" . $time . "hours"); // dodaje czas lotu
$dateOfDep ->setTimezone($tzArr2); // i zmieniam strefe czasowa na tą, gdzie przylecieliśmy

$formatEnd = $dateOfDep->format("d-m-Y H:i:s");


function searchName($place, $tab){
    
    $result = false;
    foreach($tab as $key => $value){
        
        if ($value['code'] == $place){
            $result = $value['name'];
        }
    }
        return $result;
    
}

$passenger = $faker->name;

$currencyTransformer = $numberToWords->getCurrencyTransformer('pl');
$pricePl = $currencyTransformer->toWords($price*100, 'PLN');
ob_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket</title> 
</head>
<body>
    
    <table>
        <tr>
            <th colspan="2"><h1>NikiLot Airlines</h1></th>
        </tr>
        <tr>
            <th>FROM</th>
            <th>TO</th>
        </tr>
        <tr>
            <?php
            echo "<td><h2>" . $airportDep . "</h2>(" . $dep . ")</td><td><h2>" . $airportArr . "</h2>(" . $arr . ")</td>"; 
            ?>
        </tr>
        <tr>
            <td><b>Departure (local time) </b></td>
            <td><b>Arrival (local time)</b></td>
        </tr>
        <tr>
        <?php
            echo "<td><h3>" . $formatStart . "</h3><br>" . $tzDep . "</td><td><h3>" . $formatEnd . "</h3><br>" . $tzArr . "</td>"; 
            ?>
        </tr>
        <tr><td></td> </tr>
        <tr><td></td> </tr>
        <tr>
            <?php 
            echo "<td colspan = 2><h3> Flight time:  " . $time . " hours.</h3></td>";
            ?>
        <tr>
            <?php
            echo "<td><h3> Price:  " . $price . " zł </h3></td>";
            ?> 
        </tr>
        <tr>
            <?php
            echo "<td colspan = 2>(" . $pricePl . ")</td>";
            ?>
        </tr>
        <tr>
            <?php
            echo "<td colspan = 2><h2> Passenger:  " . $passenger . " </h2></td>";
            ?> 
        </tr>
        
    </table>

</body>
</html>
<?php
$html = ob_get_contents();

ob_end_clean(); 
$mpdf->WriteHTML($html);
$mpdf->Output();
?>