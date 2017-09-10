<?php
    include('airports.php');

    ?>

<!doctype html>
<head>  
    <title>Formularz</title>
</head>
<body>
    <form action ="../pdf.php" method = "POST">
        <label> Lotnisko wylotu: <br>
            <select name="departure">
                <?php

                foreach($airports as $key => $value){        
                    echo '<option value="' . $value['code'] . '">' . $value['name'] . '</option>';
                }

                ?>                
            </select>
            <br>
        </label>
        <label> Lotnisko przylotu: <br>
            <select name="arrival">
                <?php

                foreach($airports as $key => $value){        
                    echo '<option value="' . $value['code'] . '">' . $value['name'] . '</option>';
                }

                ?> 
                
            </select>
            <br>
        </label>
        <label> Start: <br>
            <input type="datetime-local" name="start">
            <br>
        </label>
        <label> Flight time:<br>
            <input type = "number" name = "time" min="0" step="1">
            <br>
        </label>
        <label> Price:<br>
             <input type = "number" name = "price" min="0" step="0.01">
        <br>
        </label>
        <input type ="submit" value ="Send">
        
    </form> 
      
</body>
