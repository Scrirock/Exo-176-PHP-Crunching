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

    $string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
    $brut = json_decode($string, true);
    $top = $brut["feed"]["entry"]; # liste de films

    $i = 1;
    foreach ($top as $values){
        foreach ($values as $key => $imname){
            if ($key === "im:name" && $i<11) {
                foreach ($imname as $name) {
                    echo $i.". ".$name . "<br>";
                }
                $i++;
            }
        }
    }

    echo "<br>";

    $i = 0;
    foreach ($top as $values){
        foreach ($values as $key => $imname){
            if ($key === "im:name") {
                foreach ($imname as $name) {
                    if ($name === "Gravity"){
                        echo "le film gravity est a la place: ".$i."<br>";
                    }
                    $i++;
                }
            }
        }
    }

    echo "<br>";

    $i = 0;
    foreach ($top as $values){
        foreach ($values as $key => $imname){
            if ($key === "im:name") {
                foreach ($imname as $name) {
                    if ($name === "The LEGO Movie"){
                        $nbr = $i;
                    }
                    $i++;
                }
            }
        }
    }

    $i = 0;
    foreach ($top as $values){
        foreach ($values as $key => $imartist){
            if ($key === "im:artist") {
                foreach ($imartist as $name) {
                    if ($i === $nbr){
                        echo $name." est le réalisateur de The LEGO Movie";
                    }
                    $i++;
                }
            }
        }
    }
    echo "<br><br>";

    $i = 0;
    foreach ($top as $values){
        foreach ($values as $key => $imreleaseDate){
            if ($key === "im:releaseDate") {
                foreach ($imreleaseDate as $attribute) {
                    foreach ($attribute as $date){
                        if (substr($date, -4) < 2000){
                            $i++;
                        }
                    }
                }
            }
        }
    }
    echo "il y a ".$i." films sortit avant les année 2000<br><br>";

    $max = "1000-01-01";
    $min = "9000-12-31";
    $i = 0;
    $dateArray = [];
    foreach ($top as $values){
        foreach ($values as $key => $imreleaseDate){
            if ($key === "im:releaseDate") {
                foreach ($imreleaseDate as $otherKey => $attribute) {
                    if ($otherKey === "label"){
                        $date = substr($attribute, 0, -15);
                        $dateArray[] = $date;
                        if ($date > $max) {
                            $max = $date;
                            $titreMax = $top[$i]['im:name']['label'];
                        }
                        if ($date < $min) {
                            $min = $date;
                            $titreMin = $top[$i]['im:name']['label'];
                        }
                        $i++;
                    }
                }
            }
        }
    }

    echo "Le titre le plus récent est ".$titreMin.", il est sortit le ".$min."<br>";
    echo "Le titre le plus vieux est ".$titreMax.", il est sortit le ".$max."<br>";
    echo "<br>";

    $genderArray = [];
    foreach ($top as $values){
        foreach ($values as $key => $category){
            if ($key === "category") {
                foreach ($category as $attribute) {
                    foreach ($attribute as $term => $gender){
                        if ($term === "term"){
                            if (!strpos(implode($genderArray), $gender)){
                                $genderArray[$gender] += 1;
                            }
                        }
                    }
                }
            }
        }
    }

    $max = 0;
    foreach ($genderArray as $gender => $nbr){
        if ($nbr > $max){
            $max = $nbr;
            $affiche = "le genre le plus représenté est: ".$gender." il se retrouve ".$max." fois";
        }
    }
    echo $affiche."<br><br>";

    $realisateurArray = [];
    foreach ($top as $values){
        foreach ($values as $key => $artist){
            if ($key === "im:artist") {
                foreach ($artist as $label => $realisateur) {
                    if (!strpos(implode($realisateurArray), $realisateur)){
                        $realisateurArray[$realisateur] += 1;
                    }
                }
            }
        }
    }

    $max = 0;
    foreach ($realisateurArray as $key => $nbr){
        if ($nbr > $max){
            $max = $nbr;
            $affiche = "le realisateur le plus représenté est: ".$key." il a fait ".$max." films";
        }
    }
    echo $affiche."<br><br>";

    $i = 0;
    $top10Price = 0;
    foreach ($top as $values){
        foreach ($values as $key => $imprice){
            if ($key === "im:price") {
                foreach ($imprice as $label => $price) {
                    if ($label === "label" && $i<10) {
                        $top10Price += (float)str_replace("$", "", $price);
                        $i++;
                    }
                }
            }
        }
    }
    echo "Acheter tout les film du top 10 reviendrais a ".$top10Price."$<br><br>";

    $i = 0;
    $top10LocationPrice = 0;
    foreach ($top as $values){
        if ($i<10) {
            foreach ($values as $key => $imRentalPrice) {
                if ($key === "im:rentalPrice") {
                    foreach ($imRentalPrice as $label => $price) {
                        if ($label === "label") {
                            $top10LocationPrice += (float)str_replace("$", "", $price);
                        }
                    }

                }
            }
            $i++;
        }
    }
    echo "Louer tout les film du top 10 reviendrais a ".$top10LocationPrice."$<br><br>";

    $months = [];
    foreach ($dateArray as $date){
        $tmp = substr($date, 0, -3);
        $months[] =  substr($tmp, 5, 2);
    }
    $arrayCount = array_count_values($months);
    $max = 0;

    foreach ($arrayCount as $nbr) if ($nbr > $max) $max = $nbr;

    foreach ($arrayCount as $key => $nbr){
        if ($nbr === $max){
            switch ($key){
                case "01":
                    echo "le plus de film sortit est en janvier, il y en a eu ".$max."<br>";
                    break;
                case "02":
                    echo "le plus de film sortit est en fevrier, il y en a eu ".$max."<br>";
                    break;
                case "03":
                    echo "le plus de film sortit est en mars, il y en a eu ".$max."<br>";
                    break;
                case "04":
                    echo "le plus de film sortit est en avril, il y en a eu ".$max."<br>";
                    break;
                case "05":
                    echo "le plus de film sortit est en mai, il y en a eu ".$max."<br>";
                    break;
                case "06":
                    echo "le plus de film sortit est en juin, il y en a eu ".$max."<br>";
                    break;
                case "07":
                    echo "le plus de film sortit est en juillet, il y en a eu ".$max."<br>";
                    break;
                case "08":
                    echo "le plus de film sortit est en aout, il y en a eu ".$max."<br>";
                    break;
                case "09":
                    echo "le plus de film sortit est en septembre, il y en a eu ".$max."<br>";
                    break;
                case "10":
                    echo "le plus de film sortit est en octobre, il y en a eu ".$max."<br>";
                    break;
                case "11":
                    echo "le plus de film sortit est en novembre, il y en a eu ".$max."<br>";
                    break;
                case "12":
                    echo "le plus de film sortit est en decembre, il y en a eu ".$max."<br>";
                    break;
            }
        }
    }

    echo "<br>";

    $i = 0;
    $maxPrice = 0;
    $lowPriceArray = [];
    foreach ($top as $values){
        foreach ($values as $key => $imPrice){
            if ($key === "im:price") {
                foreach ($imPrice as $label => $price) {
                    if ($label === "label"){
                        if ($i < 10) $lowPriceArray[] = (float)str_replace("$", "", $price);
                        elseif ((float)str_replace("$", "", $price) < (float)max($lowPriceArray)) {
                            $maxPrice = max($lowPriceArray);
                            unset($lowPriceArray[array_search($maxPrice, $lowPriceArray)]);
                            $lowPriceArray[$values["im:name"]["label"]] = (float)str_replace("$", "", $price);
                        }
                        $i++;
                    }
                }
            }
        }
    }
    echo "Les 10 meilleur film les moin chere sont: <br>";
    foreach ($lowPriceArray as $k => $v) echo $k.": ".$v."$<br>";
?>