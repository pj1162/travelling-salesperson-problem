<?php
    /*
    isHTML
    Ger en boolean som visar om en text innehåller HTML.
    Kod från: https://subinsb.com/php-check-if-string-is-html/
    @param string $string
    @return boolean
    */
    function isHTML($string) {
        if ($string != strip_tags($string)) {
            // is HTML
            return true;
        } else {
            // not HTML
            return false;
        }
    }

    /*
    new_destination
    Lägger till en ny destination i databasen.
    @param string $name
    @param decimal $latitude
    @param decimal $longitude
    @param int $journey_id
    @return int $destination_id
    */
    function new_destination ($name, $latitude, $longitude, $journey_id) {
        global $pdo;
        $sql = "INSERT INTO Destination (name, latitude, longitude, journey_id) VALUES ('$name', $latitude, $longitude, $journey_id);";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $destination_id = $pdo->lastInsertId();
        new_to($destination_id);
        new_from($destination_id);
        return $destination_id;
    }

    /*
    get_destination_name
    Hämtar namnet på en destination från databasen.
    @param int $destination_id
    @return array $res
    */
    function get_destination_name ($destination_id) {
        global $pdo;
        $sql = "SELECT name FROM Destination WHERE id = $destination_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_all_destinations
    Hämtar information om alla destinationer från databasen.
    @param int $journey_id
    @return array $res
    */
    function get_all_destinations ($journey_id) {
        global $pdo;
        $sql = "SELECT id, latitude, longitude FROM Destination WHERE journey_id = $journey_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_all_named_destinations
    Hämtar information (med namn) om alla destinationer från databasen.
    @param int $journey_id
    @return array $res
    */
    function get_all_named_destinations ($journey_id) {
        global $pdo;
        $sql = "SELECT id, name, latitude, longitude FROM Destination WHERE journey_id = $journey_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    new_to
    Lägger till en destination som slutpunkt i databasen.
    @param int $destination_id
    */
    function new_to ($destination_id) {
        global $pdo;
        $sql = "INSERT INTO `To` (destination_id) VALUES ($destination_id);";
        $stm = $pdo->prepare($sql);
        $stm->execute();
    }

    /*
    get_to
    Hämtar en slutpunkt från databasen.
    @param int $destination_id
    @return array $res
    */
    function get_to ($destination_id) {
        global $pdo;
        $sql = "SELECT id FROM `To` WHERE destination_id = $destination_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    new_from
    Lägger till en destination som utgångspunkt i databasen.
    @param int $destination_id
    */
    function new_from ($destination_id) {
        global $pdo;
        $sql = "INSERT INTO `From` (destination_id) VALUES ($destination_id);";
        $stm = $pdo->prepare($sql);
        $stm->execute();
    }

    /*
    get_from
    Hämtar en utgångspunkt från databasen.
    @param int $destination_id
    @return array $res
    */
    function get_from ($destination_id) {
        global $pdo;
        $sql = "SELECT id FROM `From` WHERE destination_id = $destination_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_second_destination
    Hämtar alla punkter efter start från databasen.
    @param int $from_id
    @return array $res
    */
    function get_second_destination ($from_id) {
        global $pdo;
        $sql = "SELECT length, to_id FROM `to_from` WHERE from_id = $from_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_third_destination
    Hämtar alla möjliga punkter som tredje punkt från databasen.
    @param int $last_id
    @param int $from_id
    @return array $res
    */
    function get_third_destination ($last_id, $from_id) {
        global $pdo;
        $sql = "SELECT length, to_id FROM `to_from` WHERE to_id != $last_id AND from_id = $from_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_last_destination
    Hämtar den sista punkten från databasen.
    @param int $start_id
    @param int $last_id
    @param int $from_id
    @return array $res
    */
    function get_last_destination ($start_id, $last_id, $from_id) {
        global $pdo;
        $sql = "SELECT length, to_id FROM `to_from` WHERE to_id != $start_id AND to_id != $last_id AND from_id = $from_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    get_last_length
    Hämtar den sista sträckan från databasen.
    @param int $to_id
    @param int $from_id
    @return array $res
    */
    function get_last_length ($to_id, $from_id) {
        global $pdo;
        $sql = "SELECT length FROM `to_from` WHERE to_id = $to_id AND from_id = $from_id;";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /*
    new_to_from
    Lägger till en sträcka i databasen.
    @param decimal $length
    @oaram int $to_id
    @param int $from_id
    */
    function new_to_from ($length, $to_id, $from_id) {
        global $pdo;
        $sql = "INSERT INTO to_from (length, to_id, from_id) VALUES ($length, $to_id, $from_id);";
        $stm = $pdo->prepare($sql);
        $stm->execute();
    }

    /*
    get_all_to_from
    Hämtar alla sträckor som hör till en resa från databasen.
    @param int $journey_id
    @return array $res
    */
    function get_all_to_from ($journey_id) {
        global $pdo;
        $sql = "SELECT tf.length, tf.to_id, tf.from_id FROM `to_from` tf INNER JOIN Destination d ON d.journey_id = $journey_id AND tf.from_id = d.id";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
?>