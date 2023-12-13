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
            max-height: 400px;
            overflow-y: auto;
        }

        .recipe-list button {
            font-size: 18px;
            margin: 10px 0;
            padding: 10px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            width: 100%;
            text-align: left;
        }

        .recipe-list button:hover {
            background-color: #45a049;
        }
    </style>
    <title>Recipe List</title>
</head>
<body>
    <?php
        $config = parse_ini_file("config.ini");   // better to hide this!
        $server = $config["host"];
        $username = $config["user"];
        $password = $config["password"];
        $database = $config["database"];

        $cn = mysqli_connect($server, $username, $password, $database);

        if (!$cn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $q = "SELECT recipe_id, recipe_name, rating FROM Recipe ORDER BY rating DESC, recipe_name ASC";
        $rs = mysqli_query($cn, $q); 

        if (mysqli_num_rows($rs) > 0) {
            echo "<div class='recipe-list'>";
            echo "<p><em>Current Recipes</em></p>\n";
            while($row = mysqli_fetch_assoc($rs)) {
                echo "<form action='recipe_details.php' method='post'>";
                echo "<button type='submit' name='recipe_id' value='{$row["recipe_id"]}'>";
                echo  $row["recipe_name"] . "....." . $row["rating"] . "/5";
                echo "</button>";
                echo "</form>";
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
