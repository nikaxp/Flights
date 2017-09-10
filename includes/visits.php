<?php

$cookie = false;

if(!isset($_COOKIE['visits'])){
    echo "Witaj pierwszy raz na naszej stronie";
    setcookie('visits', '1', time() + 31536000 );
}
else{
    $cookie = $_COOKIE['visits'];
    echo "Witaj, odwiedziłeś nas już " . $cookie . "razy";
    setcookie('visits', ++$cookie, time() + 31536000 ); //++cookie, bo najpierw zwiększamy.
}