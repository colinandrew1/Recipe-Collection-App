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
        $config = parse_ini_file("config.ini");
        $server = $config["host"];
        $username = $config["user"];
        $password = $config["password"];
        $database = $config["database"];
        $cn = mysqli_connect($server, $username, $password, $database);
        if (!$cn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $category_name = $_POST["category"];
        $cuisine_name = $_POST["cuisine"];
        $min_cost = $_POST["min_cost"];
        $max_cost = $_POST["max_cost"];
        $min_prep_time = $_POST["min_prep_time"];
        $max_prep_time = $_POST["max_prep_time"];
        $min_rating = $_POST["min_rating"];
        $max_rating = $_POST["max_rating"];
        $dietary_restrictions_name = $_POST["dietary_restrictions"];

        $q = "SELECT recipe_id, recipe_name, rating FROM Recipe JOIN RecipeDietaryRestriction USING (recipe_id) WHERE 1=1";
        $params = "";
        $param_values = [];

        if (!empty($category_name)) {
            $category_q = "SELECT category_id FROM Category WHERE category_name = ?";
            $category_st = $cn->stmt_init();
            $category_st->prepare($category_q);
            $category_st->bind_param("s", $category_name);
            $category_st->execute();
            $category_st->bind_result($category_id);
            $category_st->fetch();
            $category_st->close();
            $q .= " AND category_id = ?";
            $params .= "i";
            $param_values[] = $category_id;
        }
        if (!empty($cuisine_name)) {
            $cuisine_q = "SELECT cuisine_id FROM Cuisine WHERE cuisine_name = ?";
            $cuisine_st = $cn->stmt_init();
            $cuisine_st->prepare($cuisine_q);
            $cuisine_st->bind_param("s", $cuisine_name);
            $cuisine_st->execute();
            $cuisine_st->bind_result($cuisine_id);
            $cuisine_st->fetch();
            $cuisine_st->close();
            $q .= " AND cuisine_id = ?";
            $params .= "i";
            $param_values[] = $cuisine_id;
        }
        if (!empty($min_cost)) {
            $q .= " AND price >= ?";
            $params .= "d";
            $param_values[] = $min_cost;
        }
        if (!empty($max_cost)) {
            $q .= " AND price <= ?";
            $params .= "d";
            $param_values[] = $max_cost;
        }
        if (!empty($min_prep_time)) {
            $q .= " AND prep_time >= ?";
            $params .= "i";
            $param_values[] = $min_prep_time;
        }
        if (!empty($max_prep_time)) {
            $q .= " AND prep_time <= ?";
            $params .= "i";
            $param_values[] = $max_prep_time;
        }
        if (!empty($min_rating)) {
            $q .= " AND rating >= ?";
            $params .= "d";
            $param_values[] = $min_rating;
        }
        if (!empty($max_rating)) {
            $q .= " AND rating <= ?";
            $params .= "d";
            $param_values[] = $max_rating;
        }
        if (!empty($dietary_restrictions_name)) {
            $restrictions_q = "SELECT restriction_id FROM DietaryRestrictionType WHERE restriction_name = ?";
            $restrictions_st = $cn->stmt_init();
            $restrictions_st->prepare($restrictions_q);
            $restrictions_st->bind_param("s", $dietary_restrictions_name);
            $restrictions_st->execute();
            $restrictions_st->bind_result($restriction_id);
            $restrictions_st->fetch();
            $restrictions_st->close();
            $q .= " AND restriction_id = ?";
            $params .= "i";
            $param_values[] = $restriction_id;
        }

        $q .= " AND 2=2 ORDER BY rating DESC, recipe_name ASC";

        $st = $cn->stmt_init();
        $st->prepare($q);

        if (!empty($params)) {
            $bind_params = array_merge([$params], $param_values);
            $refs = array();
            foreach ($bind_params as $key => $value) {
                $refs[$key] = &$bind_params[$key];
            }
            call_user_func_array([$st, 'bind_param'], $refs);
        }

        $st->execute();
        $st->bind_result($recipe_id, $recipe_name, $rating);
        $rows = [];
        while ($st->fetch()) {
            $rows[] = ['recipe_id' => $recipe_id, 'recipe_name' => $recipe_name, 'rating' => $rating];
        }

        if (count($rows) > 0) {
            echo "<div class='recipe-list'>";
            echo "<p><em>Current Recipes</em></p>\n";
            foreach ($rows as $row) {
                echo "<form action='recipe_details.php' method='post'>";
                echo "<button type='submit' name='recipe_id' value='{$row["recipe_id"]}'>";
                echo $row["recipe_name"] . "....." . $row["rating"] . "/5";
                echo "</button>";
                echo "</form>";
            }
            echo "</div>";
        } else {
            echo "<div class='recipe-list'>";
            echo "<p><b>No Recipes Meet This Criteria</b></p>\n";
            echo "</div>";
        }

        $st->close();
        mysqli_close($cn);
    ?>

</body>
</html>
