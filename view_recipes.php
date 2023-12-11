<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .recipe-list {
            text-align: center;
        }

        .recipe-list p {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
    <title>Recipe List</title>
</head>
<body>
    <?php
        // connection params
        $config = parse_ini_file("config.ini");   // better to hide this!
        $server = $config["host"];
        $username = $config["user"];
        $password = $config["password"];
        $database = $config["database"];

        // connect to db   
        $cn = mysqli_connect($server, $username, $password, $database);

        // check connection
        if (!$cn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // execute a simple query
        $q = "SELECT * FROM Recipe";
        $rs = mysqli_query($cn, $q); 

        // retrieve results via result set
        if (mysqli_num_rows($rs) > 0) {
            echo "<div class='recipe-list'>";
            echo "<p><em>Current Recipes</em></p>\n";
            while($row = mysqli_fetch_assoc($rs)) {  // fetch as associative array
                echo "<p>";
                echo  $row["recipe_name"];
                echo "</p>";
            }
            echo "</div>";
        }
        else {
            echo "<div class='recipe-list'>";
            echo "<p><b>No Recipes</b></p>\n";
            echo "</div>";
        }

        mysqli_close($cn);
    ?>
</body>
</html>
