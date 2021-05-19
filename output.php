<?php
    require_once('config.php');
    require_once(ROOT_PATH.'/database_connection.php');
    require_once(ROOT_PATH.'/functions.php');

    if (isset($_GET['journey_id']) && isset($_GET['start_id'])) {
        // Hämta resans id
        $journey_id = trim($_GET['journey_id']);
        // Hämta startpunkten
        $start_id = trim($_GET['start_id']);
    } else {
        header('Location: index.html');
        exit;
    }

    // Gör alla resvägar

    // Gå till nästa plats
    foreach (get_second_destination($start_id) as $next) {
        // Gör en lista med vägarna
        $route = array($start_id, $next['to_id']);
        $length_total = 0;

        // räkna totala sträckan
        $length_total += $next['length'];

        // Hämta nästa plats
        foreach (get_third_destination($start_id, $next['to_id']) as $next2) {
            // lägg till platsen i listan
            $route[] = $next2['to_id'];

            $length_total += $next2['length'];

            // Hämta sista platsen
            $next3 = get_last_destination($start_id, $next['to_id'], $next2['to_id']);

            $route[] = $next3['to_id'];
            $length_total += $next3['length'];

            // Tillbaks till start platsen
            $length_total += get_last_length($start_id, $next3['to_id'])['length'];
            $route[] = $start_id;

            // Spara resvägen i en array
            $routes[] = $route;
            $lengths[] = $length_total;
        }
    }
    // Hämta min värden
    $keys = array_keys($lengths, min($lengths));
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Import Google Icon Font-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>En handelsresandes problem</title>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-wrapper indigo darken-4">
            <a href="<?php echo BASE_URL; ?>" class="brand-logo truncate">En handelsresandes problem</a>
        </div>
    </nav>
    <!-- // Navbar -->
    <div class="container">
        <h1>Resultat:</h1>
        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Namn</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach (get_all_named_destinations($journey_id) as $row) {
                        echo "<tr>";
                        echo "\n\t\t\t\t\t<td>".$row['id']."</td>";
                        echo "\n\t\t\t\t\t<td>".$row['name']."</td>";
                        echo "\n\t\t\t\t\t<td>".$row['latitude']."</td>";
                        echo "\n\t\t\t\t\t<td>".$row['longitude']."</td>";
                        echo "\n\t\t\t\t</tr>";
                    }
                    echo "\n";
                ?>
            </tbody>
        </table>
        <!-- // Table -->
        <h2>Kortaste resväg:</h2>
        <!-- Route -->
        <?php
            foreach ($keys as $key) {
                echo "\n\t\t<p>";
                foreach ($routes[$key] as $route) {
                    echo get_destination_name($route)['name']." - ";
                }
                echo number_format(min($lengths), 2, ",", " ")." km</p>";
            }
        ?>
        <!-- // Route -->
        <h2>Sträckor</h2>
        <table>
            <thead>
                <tr>
                    <th>Från</th>
                    <th>Till</th>
                    <th>Längd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach (get_all_to_from($journey_id) as $row) {
                        echo "<tr>";
                        echo "\n\t\t\t\t\t<td>".get_destination_name($row['from_id'])['name']."</td>";
                        echo "\n\t\t\t\t\t<td>".get_destination_name($row['to_id'])['name']."</td>";
                        echo "\n\t\t\t\t\t<td>".number_format($row['length'], 2, ",", " ")." km</td>";
                        echo "\n\t\t\t\t</tr>";
                    }
                    echo "\n";
                ?>
            </tbody>
        </table>
    </div>
    <!-- Footer -->
    <footer class="page-footer indigo darken-4">
        <div class="footer-copyright indigo darken-3">
            &copy;2021 Patricia J
        </div>
    </footer>
    <!-- //Footer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>