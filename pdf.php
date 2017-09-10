<?php
include('includes/airports.php');

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





?>