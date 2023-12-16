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
            position: relative; /* Ensure relative positioning for absolute positioning inside */
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

        .update-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .delete-button {
            left: 10px;
        }
    </style>
    <title>Recipe Details</title>
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

            $recipe_id = $_POST["recipe_id"];

            $q = "SELECT recipe_name, category_id, cuisine_id, price, prep_time, rating FROM Recipe WHERE recipe_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $recipe_id);
            $st->execute();
            $st->bind_result($recipe_name, $category_id, $cuisine_id, $price, $prep_time, $rating);
            $st->fetch();
            $st->close();

            echo "<h1>" . $recipe_name . "</h1>";

            $q = "SELECT category_name FROM Category WHERE category_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $category_id);
            $st->execute();
            $st->bind_result($category_name);
            $st->fetch();
            $st->close();

            $q = "SELECT cuisine_name FROM Cuisine WHERE cuisine_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $cuisine_id);
            $st->execute();
            $st->bind_result($cuisine_name);
            $st->fetch();
            $st->close();

            echo "<div class='section-heading'>Recipe Details</div>";
            echo "<div class='section-content'>";
            echo "Category: " . $category_name . "<br>" . "Cuisine: " . $cuisine_name . "<br>" . "Cost to make: $" . $price .  "<br>" . "Prep time: " . $prep_time . " minutes" . "<br>" . "Rating: " . $rating . "/5" . "<br><br>";
            echo "</div>";

            $q = "SELECT restriction_id FROM RecipeDietaryRestriction WHERE recipe_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $recipe_id);
            $st->execute();
            $st->bind_result($restriction_id);

            $restrictions = array();
            while ($st->fetch()) {
                $restrictions[] = array(
                    "restriction_id" => $restriction_id,
                );
            }
            $st->close();

            echo "<div class='section-heading'>Dietary Restrictions</div>";
            echo "<div class='section-content'>";
            foreach ($restrictions as $restriction) {
                $restriction_id = $restriction['restriction_id'];
                $q = "SELECT restriction_name FROM DietaryRestrictionType WHERE restriction_id = ?";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("i", $restriction_id);
                $st->execute();
                $st->bind_result($restriction_name);

                while($st->fetch()) {
                    echo $restriction_name . "<br>";
                }
                $st->close();
            }
            echo "</div>";

            $q = "SELECT ingredient_id, quantity, unit_id FROM Recipe_Ingredient WHERE recipe_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $recipe_id);
            $st->execute();
            $st->bind_result($ingredient_id, $quantity, $unit_id);

            $recipe_ingredients = array();
            while ($st->fetch()) {
                $recipe_ingredients[] = array(
                    "ingredient_id" => $ingredient_id,
                    "quantity" => $quantity,
                    "unit_id" => $unit_id
                );
            }
            $st->close();

            echo "<div class='section-heading'>Recipe Ingredients</div>";
            echo "<div class='section-content'>";
            foreach ($recipe_ingredients as $recipe_ingredient) {
                $ingredient_id = $recipe_ingredient['ingredient_id'];
                $quantity = $recipe_ingredient['quantity'];
                $unit_id = $recipe_ingredient['unit_id'];

                $ingredient_q = "SELECT ingredient_name FROM Ingredient WHERE ingredient_id = ?";
                $ingredient_st = $cn->stmt_init();
                $ingredient_st->prepare($ingredient_q);
                $ingredient_st->bind_param("i", $ingredient_id);
                $ingredient_st->execute();
                $ingredient_st->bind_result($ingredient_name);
                $ingredient_st->fetch();
                $ingredient_st->close();

                $unit_q = "SELECT unit_name FROM Unit WHERE unit_id = ?";
                $unit_st = $cn->stmt_init();
                $unit_st->prepare($unit_q);
                $unit_st->bind_param("i", $unit_id);
                $unit_st->execute();
                $unit_st->bind_result($unit_name);
                $unit_st->fetch();
                $unit_st->close();

                if ($quantity == 1 || $unit_name == "Each") {
                    $suffix = '';
                } else {
                    $suffix = 's';
                }

                echo $ingredient_name . " - " . $quantity . " " . $unit_name . $suffix . "<br>";
            }
            echo "</div>";

            $q = "SELECT step_number, instruction_text FROM Instructions WHERE recipe_id = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("i", $recipe_id);
            $st->execute();
            $st->bind_result($step_number, $instruction_text);

            echo "<div class='section-heading'>Instructions</div>";
            echo "<div class='section-content'>";
            while ($st->fetch()) {
                echo "<div style='text-align: left;'>Step " . $step_number . ") " . $instruction_text . "</div><br>";
            }
            echo "</div>";

            echo "<div class='update-button'>";
            echo "<form action='update_recipe_frontend.php' method='post'>";
            echo "<button type='submit'>Update</button>";
            echo "<input type='hidden' name='recipe_id' value='" . $recipe_id . "'>";
            echo "</form>";
            echo "</div>";

            echo "<div class='delete-button'>";
            echo "<form action='delete_recipe.php' method='post'>";
            echo "<button type='submit'>Delete</button>";
            echo "<input type='hidden' name='recipe_id' value='" . $recipe_id . "'>";
            echo "</form>";
            echo "</div>";

            $cn->close();
        ?>
    </div>
</body>
</html>
