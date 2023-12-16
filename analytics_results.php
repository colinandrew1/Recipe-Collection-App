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
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .recipe-details {
            text-align: center;
            border: 2px solid #336699;
            border-radius: 10px;
            padding: 20px;
            overflow: auto;
            max-height: 80vh;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h1, h2 {
            color: #336699;
        }

        .section-heading {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            color: #336699;
        }

        .section-content {
            margin-top: 10px;
        }


    </style>
    <title>Results</title>
</head>
<body>
    <div class="recipe-details">
        <?php
            $config = parse_ini_file("config.ini");
            $server = $config["host"];
            $username = $config["user"];
            $password = $config["password"];
            $database = $config["database"];

            $cn = mysqli_connect($server, $username, $password, $database);

            $category_name = $_POST["category"];
            $cuisine_name = $_POST["cuisine"];
            $min_cost = $_POST["min_cost"];
            $max_cost = $_POST["max_cost"];
            $min_prep_time = $_POST["min_prep_time"];
            $max_prep_time = $_POST["max_prep_time"];
            $min_rating = $_POST["min_rating"];
            $max_rating = $_POST["max_rating"];
            $dietary_restrictions_name = $_POST["dietary_restrictions"];
            $function = $_POST["function"];
            $attribute = $_POST["attribute"];
            $group = $_POST["group"];

            if (empty($attribute)) {
                $attribute = "*";
            }

            if (!empty($group)) {
                $q = "SELECT " . $group . "_name, $function($attribute) FROM Recipe JOIN Category USING (category_id) JOIN Cuisine USING (cuisine_id) WHERE 1=1";
            }
            else {
                $q = "SELECT $function($attribute) FROM Recipe JOIN RecipeDietaryRestriction USING (recipe_id) WHERE 1=1";
            }

            // SELECT cuisine_name, function(attribute) as aggregate
            // FROM same
            // WHERE same
            // GROUP BY group
            // ORDER BY function(attribute)


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

            $q .= " AND 2=2";

            if (!empty($group)) {
                $q .= " GROUP BY " . $group . "_id ORDER BY " . $function . "(" . $attribute . ") DESC";

            }
            
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
            if (!empty($group)) 
            {
                $st->bind_result($group_title, $group_value);
                while ($st->fetch()) {
                    echo "<p><strong>$group_title:</strong> $group_value</p>";
                }
            }
            else
            {
                $st->bind_result($result_value);
                $st->fetch();
                if (!is_null($result_value)) {
                    echo "<h2>Result:</h2>";
                
                    if ($attribute == "price") {
                        echo "<p><h4>$ {$result_value}</h4></p>";
                    } elseif ($attribute == "prep_time") {
                        echo "<p><h4>{$result_value} minutes</h4></p>";
                    } elseif ($attribute == "rating") {
                        echo "<p><h4>{$result_value} / 5</h4></p>";
                    } else {
                        echo "<p><h4>{$result_value}</h4></p>";
                    }
                }
                else {
                    echo "<h2>No results available, please edit search criteria.</h2>";
                }
            }
            
            $st->close();
            mysqli_close($cn);
        ?>
    </div>
</body>
</html>
