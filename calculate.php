<?php
    require_once('config.php');
    require_once(ROOT_PATH.'/database_connection.php');
    require_once(ROOT_PATH.'/functions.php');

    // Starta en ny resa
    // Lägg in i databasen
    $sql = "INSERT INTO Journey (id) VALUES (NULL);";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $journey_id = $pdo->lastInsertId();

    // Hämta från formuläret
    // Kolla om namnen innehåller HTML
    if(isHTML(trim($_POST['city1'])) || isHTML(trim($_POST['city2'])) || isHTML(trim($_POST['city3'])) || isHTML(trim($_POST['city4']))) {
        header('Location: index.html');
    } else {
        $name1 = htmlentities(trim($_POST['city1']));
        $name2 = htmlentities(trim($_POST['city2']));
        $name3 = htmlentities(trim($_POST['city3']));
        $name4 = htmlentities(trim($_POST['city4']));
    }
    // latituder
    $latitude1 = trim($_POST['latitude1']);
    $latitude2 = trim($_POST['latitude2']);
    $latitude3 = trim($_POST['latitude3']);
    $latitude4 = trim($_POST['latitude4']);

    // longituder
    $longitude1 = trim($_POST['longitude1']);
    $longitude2 = trim($_POST['longitude2']);
    $longitude3 = trim($_POST['longitude3']);
    $longitude4 = trim($_POST['longitude4']);

    // Lägg in städerna i databasen
    $destination1 = new_destination($name1, $latitude1, $longitude1, $journey_id);
    $destination2 = new_destination($name2, $latitude2, $longitude2, $journey_id);
    $destination3 = new_destination($name3, $latitude3, $longitude3, $journey_id);
    $destination4 = new_destination($name4, $latitude4, $longitude4, $journey_id);

    // Räkna ut sträckorna

        // jordens radie
        $radius = (2 * 6378.1370 + 6356.7523) / 3;

    foreach (get_all_destinations($journey_id) as $to) {
        $to_id = get_to($to['id']);

        // Grader görs om till radianer
        $to_latitude = $to['latitude'] * (M_PI/180);
        $to_longitude = $to['longitude'] * (M_PI/180);

        foreach (get_all_destinations($journey_id) as $from) {
            $from_id = get_from($from['id']);

            // Hoppa över beräkningen om till och från är samma
            if ($from_id == $to_id) {
                continue;
            }

            // Grader görs om till radianer
            $from_latitude = $from['latitude'] * (M_PI/180);
            $from_longitude = $from['longitude'] * (M_PI/180);

            // Räkna ut vinkeln mellan punkterna
            $central_angle = acos(sin($to_latitude)*sin($from_latitude) + cos($to_latitude)*cos($from_latitude)*cos($to_longitude - $from_longitude));

            // Räkna ut båglängden
            $length = $radius * $central_angle;

            // Lägg till sträckan
            if ($length > 0) {
                new_to_from($length, $to_id['id'], $from_id['id']);
            }
        }
    }

    $start_id = $destination1;

    // Gå vidare till resultatet
    header('Location: output.php?journey_id='.$journey_id.'&start_id='.$start_id);
?>