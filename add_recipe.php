<?php
    $config = parse_ini_file("config.ini");
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];
    $cn = mysqli_connect($server, $username, $password, $database);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $recipeName = $_POST['recipe_name'];
        $categoryName = $_POST['category'];
        $cuisineName = $_POST['cuisine'];
        $cost = $_POST['cost'];
        $prepTime = $_POST['prep_time'];
        $rating = $_POST['rating'];
        $dietaryRestrictionName = $_POST['dietary_restrictions'];
        $recipeIngredients = $_POST['selected_ingredients'];
        $recipeSteps = $_POST['display_steps'];


        $q = "SELECT MAX(recipe_id) FROM Recipe";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->execute();
        $st->bind_result($max_recipe_id);
        $st->fetch();
        $st->close();
        $recipe_id = $max_recipe_id + 1;

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

        $q = "INSERT INTO Recipe (recipe_id, recipe_name, category_id, cuisine_id, price, prep_time, rating) VALUES (?,?,?,?,?,?,?)";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("isiiiii", $recipe_id, $recipeName, $category_id, $cuisine_id, $cost, $prepTime, $rating);
        $st->execute();
        $st->close();

        $q = "INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES (?,?)";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("ii", $recipe_id, $restriction_id);
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

        .recipe-details,
        .add-recipe {
            text-align: center;
            border: 2px solid #336699;
            border-radius: 10px;
            padding: 20px;
            max-height: 80vh;
            max-width: 600px;
            overflow: auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
    </style>
    <title>Add Recipe</title>
</head>
<body>
    <div class="add-recipe">
        <h1>Add Recipe</h1>

        <form method="post">

            <div class="form-group">
                <label for="recipe_name">Recipe Name:</label>
                <input type="text" name="recipe_name" id="recipe_name" required>
            </div>

            <div class="form-group">
                <h2>Recipe Information</h2>

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                <option value=""></option>
                    <?php
                    $q = "SELECT category_name FROM Category";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($category_name);

                    while($st->fetch()){
                        echo "<option value='" . $category_name . "'>" . $category_name . "</option>";
                    }
                    $st->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cuisine">Cuisine:</label>
                <select name="cuisine" id="cuisine" required>
                <option value=""></option>
                <?php
                    $q = "SELECT cuisine_name FROM Cuisine";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($cuisine_name);

                    while($st->fetch()){
                        echo "<option value='" . $cuisine_name . "'>" . $cuisine_name . "</option>";
                    }
                    $st->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cost">Cost to make:</label>
                <input type="number" name="cost" id="cost" min="0" max="999" required>
            </div>

            <div class="form-group">
                <label for="prep_time">Prep Time:</label>
                <input type="number" name="prep_time" id="prep_time" min="0" max="999" required>
            </div>

            <div class="form-group">
                <label for="rating">Rating (0-5):</label>
                <input type="number" name="rating" id="rating" min="0" max="5" required>
            </div>

            <div class="form-group">
                <label for="dietary_restrictions">Dietary Restrictions:</label>
                <select name="dietary_restrictions" id="dietary_restrictions" required>
                <option value=""></option>
                <?php
                    $q = "SELECT restriction_name FROM DietaryRestrictionType ORDER BY restriction_id";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($restriction_name);

                    while($st->fetch()){
                        echo "<option value='" . $restriction_name . "'>" . $restriction_name . "</option>";
                    }
                    $st->close();
                ?>
                </select>
            </div>

            <div class="form-group">
                <h2>Ingredients</h2>

                <label for="ingredient_name">Ingredient Name:</label>
                <select name="ingredient_name" id="ingredient_name">
                <option value=""></option>
                <?php
                    $q = "SELECT ingredient_name FROM Ingredient ORDER BY ingredient_name";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($ingredient_name);

                    while($st->fetch()){
                        echo "<option value='" . $ingredient_name . "'>" . $ingredient_name . "</option>";
                    }
                    $st->close();
                ?>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="0" max="999">

                <label for="unit">Unit:</label>
                <select name="unit" id="unit">
                <option value=""></option>
                <?php
                    $q = "SELECT unit_name FROM Unit";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($unit_name);

                    while($st->fetch()){
                        echo "<option value='" . $unit_name . "'>" . $unit_name . "</option>";
                    }
                    $st->close();
                ?>
                </select>

                <button type="button" onclick="addIngredient()">Add Ingredient</button>

                <label for="selected_ingredients">Selected Ingredients:</label>
                <textarea name="selected_ingredients" id="selected_ingredients" rows="4" cols="40" readonly></textarea>
            </div>

            <div class="form-group">
                <h2>Instructions</h2>

                <div id="step_tracker">Step: 1</div>

                <input type="text" name="instruction" id="instruction">
                <button type="button" onclick="addStep()">Add Step</button>
            </div>

            <div class="form-group">
                <textarea name="display_steps" id="display_steps" rows="6" cols="40" readonly></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Add Recipe" onsubmit="createRecipe()">
            </div>

        </form>

        <script>
            let stepNumber = 1;

            function addStep() {
                const instructionInput = document.getElementById('instruction');
                const displayStepsTextarea = document.getElementById('display_steps');
                displayStepsTextarea.value += `Step ${stepNumber}: ${instructionInput.value}\n`;
                stepNumber++;
                document.getElementById('step_tracker').innerText = `Step: ${stepNumber}`;
                instructionInput.value = '';
            }

            function addIngredient() {
              const ingredientSelect = document.getElementById('ingredient_name');
              const quantitySelect = document.getElementById('quantity');
              const unitSelect = document.getElementById('unit');
              const selectedIngredientsTextarea = document.getElementById('selected_ingredients');

              const selectedIngredient = ingredientSelect.value;
              const selectedQuantity = quantitySelect.value;
              const selectedUnit = unitSelect.value;

              if (selectedIngredient && selectedQuantity && selectedUnit) {
                  selectedIngredientsTextarea.value += `${selectedQuantity} ${selectedUnit} ${selectedIngredient}\n`;
                  ingredientSelect.value = '';
                  quantitySelect.value = '';
                  unitSelect.value = '';
              }
          }

        </script>
    </div>
</body>
</html>
