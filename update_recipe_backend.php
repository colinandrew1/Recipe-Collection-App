<?php
    $config = parse_ini_file("config.ini");
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];
    $cn = mysqli_connect($server, $username, $password, $database);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $recipe_id = $_POST['recipe_id'];
        $recipeName = $_POST['recipe_name'];
        $categoryName = $_POST['category'];
        $cuisineName = $_POST['cuisine'];
        $cost = $_POST['cost'];
        $prepTime = $_POST['prep_time'];
        $rating = $_POST['rating'];
        $dietaryRestrictionName = $_POST['dietary_restrictions'];
        $recipeIngredients = $_POST['selected_ingredients'];
        $recipeSteps = $_POST['display_steps'];


        $q = "SELECT category_id FROM Category WHERE category_name = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $categoryName);
        $st->execute();
        $st->bind_result($category_id);
        $st->fetch();
        $st->close();

        $q = "SELECT cuisine_id FROM Cuisine WHERE cuisine_name = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $cuisineName);
        $st->execute();
        $st->bind_result($cuisine_id);
        $st->fetch();
        $st->close();
        

        $q = "SELECT restriction_id FROM DietaryRestrictionType WHERE restriction_name = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("s", $dietaryRestrictionName);
        $st->execute();
        $st->bind_result($restriction_id);
        $st->fetch();
        $st->close();

        $q = "UPDATE Recipe SET recipe_name=?, category_id=?, cuisine_id=?, price=?, prep_time=?, rating=? WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("siiiiii", $recipeName, $category_id, $cuisine_id, $cost, $prepTime, $rating, $recipe_id);
        $st->execute();
        $st->close();


        $q = "DELETE FROM RecipeDietaryRestriction WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();
        
        $q = "INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES (?,?)";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("ii", $recipe_id, $restriction_id);
        $st->execute();
        $st->close();

        
        
        $q = "DELETE FROM Recipe_Ingredient WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();

        $ingredientStrings = explode("\n", $recipeIngredients);
        $ingredientStringsLength = count($ingredientStrings);


        for ($i = 0; $i < $ingredientStringsLength - 1; $i++) {
            $parts = explode(' ', trim($ingredientStrings[$i]), 3);
            
            $number = '';
            $unit = '';
            $ingredient = '';

            if (isset($parts[0])) {
                $number = $parts[0];
            }
            if (isset($parts[1])) {
                $unit = $parts[1];
            }
            if (isset($parts[2])) {
                $ingredient = $parts[2];
            }

            $q = "SELECT ingredient_id FROM Ingredient WHERE ingredient_name = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("s", $ingredient);
            $st->execute();
            $st->bind_result($ingredient_id);
            $st->fetch();
            $st->close();

            $q = "SELECT unit_id FROM Unit WHERE unit_name = ?";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("s", $unit);
            $st->execute();
            $st->bind_result($unit_id);
            $st->fetch();
            $st->close();
            
            $q = "INSERT INTO Recipe_Ingredient (recipe_id, ingredient_id, quantity, unit_id) VALUES (?,?,?,?)";
            $st = $cn->stmt_init();
            $st->prepare($q);
            $st->bind_param("iiii", $recipe_id, $ingredient_id, $number, $unit_id);
            $st->execute();
            $st->close();

        }

        $q = "DELETE FROM Instructions WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();


        $instructionStrings = explode("\n", $recipeSteps);
        $instructionStringsLength = count($instructionStrings);

        for ($i = 0; $i < $instructionStringsLength - 1; $i++) {
            $colonPosition = strpos($instructionStrings[$i], ':');

            if ($colonPosition !== false) {
                $stepNumber = trim(substr($instructionStrings[$i], 5, $colonPosition - 5)); // "Step " has 5 characters
                $instruction = trim(substr($instructionStrings[$i], $colonPosition + 1));

                $q = "INSERT INTO Instructions (recipe_id, step_number, instruction_text) VALUES (?,?,?)";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("iis", $recipe_id, $stepNumber, $instruction);
                $st->execute();
                $st->close();

            }
        }
        header("Location: recipe_collection_app.php");
        exit();
    }
?>