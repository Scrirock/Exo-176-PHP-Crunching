<?php

    $string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
    $dico = explode("\n", $string);

    echo "nbr mot: ".count($dico);
    echo "<br><br>";

    $mot15 = 0;
    $motW = 0;
    $motQ = 0;
    foreach ($dico as $value){
        if (strlen($value) === 15){
            $mot15++;
        }
        if (strpos($value, "w")){
            $motW++;
        }
        $letter =  $value[-2];
        if ($letter === "q") {
            $motQ++;
        }
    }
    echo "nbr mot de 15 lettres: ".$mot15;
    echo "<br><br>";

    echo "nbr mot qui ont la lettre w: ".$motW;
    echo "<br><br>";

    echo "nbr mot qui finnissent par q: ".$motQ;
    echo "<br><br>";
    
?>