<?php
    $config = parse_ini_file("config.ini");
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];
    $cn = mysqli_connect($server, $username, $password, $database);

    $recipe_id = $_POST['recipe_id'];
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
        .update-recipe {
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
    <title>Update Recipe</title>
</head>
<body>
    <div class="update-recipe">
        <h1>Update Recipe</h1>

        <form method="post" action="update_recipe_backend.php">

            <?php
                $q = "SELECT recipe_name, category_id, cuisine_id, price, prep_time, rating FROM Recipe WHERE recipe_id = ?";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("i", $recipe_id);
                $st->execute();
                $st->bind_result($existing_recipe_name, $existing_category_id, $existing_cuisine_id, $existing_price, $existing_prep_time, $existing_rating);
                $st->fetch();
                $st->close();

                $q = "SELECT restriction_id FROM RecipeDietaryRestriction WHERE recipe_id = ?";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("i", $recipe_id);
                $st->execute();
                $st->bind_result($existing_restriction_id);
                $st->fetch();
                $st->close();
            ?>


            <div class="form-group">
                <label for="recipe_name">Recipe Name:</label>
                <input type="text" name="recipe_name" id="recipe_name" required value="<?php echo $existing_recipe_name; ?>">
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <?php
                    $q = "SELECT category_name FROM Category WHERE category_id = ?";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->bind_param("i", $existing_category_id);
                    $st->execute();
                    $st->bind_result($existing_category_name);
                    $st->fetch();
                    $st->close();

                    $q = "SELECT category_name FROM Category";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($category_name);

                    while ($st->fetch()) {
                        echo '<option value="' . $category_name . '"';
                        if ($existing_category_name == $category_name) {
                            echo ' selected';
                        }
                        echo '>' . $category_name . '</option>';
                    }

                    $st->close();
                    ?>
                </select>
            </div>


            <div class="form-group">
                <label for="cuisine">Cuisine:</label>
                <select name="cuisine" id="cuisine" required>
                    <?php
                    $q = "SELECT cuisine_name FROM Cuisine WHERE cuisine_id = ?";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->bind_param("i", $existing_cuisine_id);
                    $st->execute();
                    $st->bind_result($existing_cuisine_name);
                    $st->fetch();
                    $st->close();

                    $q = "SELECT cuisine_name FROM Cuisine";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($cuisine_name);

                    while ($st->fetch()) {
                        echo '<option value="' . $cuisine_name . '"';
                        if ($existing_cuisine_name == $cuisine_name) {
                            echo ' selected';
                        }
                        echo '>' . $cuisine_name . '</option>';
                    }

                    $st->close();
                    ?>
                </select>
            </div>


            <div class="form-group">
                <label for="cost">Cost to make:</label>
                <input type="number" name="cost" id="cost" min="0" max="999" required value="<?php echo $existing_price; ?>">
            </div>

            <div class="form-group">
                <label for="prep_time">Prep Time:</label>
                <input type="number" name="prep_time" id="prep_time" min="0" max="999" required value="<?php echo $existing_prep_time; ?>">
            </div>

            <div class="form-group">
                <label for="rating">Rating (0-5):</label>
                <input type="number" name="rating" id="rating" min="0" max="5" required value="<?php echo $existing_rating; ?>">
            </div>

            <div class="form-group">
                <label for="dietary_restrictions">Dietary Restrictions:</label>
                <select name="dietary_restrictions" id="dietary_restrictions" required>
                <?php
                    $q = "SELECT * FROM DietaryRestrictionType ORDER BY restriction_id";
                    $st = $cn->stmt_init();
                    $st->prepare($q);
                    $st->execute();
                    $st->bind_result($restriction_id, $restriction_name);

                    while($st->fetch()){
                        echo '<option value="' . $restriction_name . '"';
                        if (isset($existing_restriction_id) && $existing_restriction_id == $restriction_id) {
                            echo ' selected';
                        }
                        echo '>' . $restriction_name . '</option>';
                    }

                    $st->close();
                ?>
                </select>
            </div>

            <div class="form-group">
                <h2>Ingredients</h2>

                <label for="ingredient_name">Ingredient Name:</label>
                <select name="ingredient_name" id="ingredient_name">
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
                <button type="button" onclick="removeIngredient()">Remove Last Ingredient</button>
                <label for="selected_ingredients">Selected Ingredients:</label>
                <textarea name="selected_ingredients" id="selected_ingredients" rows="4" cols="40" readonly></textarea>
                
            </div>

            <?php
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

                    echo '<script>
                            document.getElementById("selected_ingredients").value += "' . $quantity .  " " . $unit_name . " " . $ingredient_name . '\\n";
                        </script>';
                }
            ?>
        
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            

            <div class="form-group">
                <h2>Instructions</h2>
                <div id="step_tracker">Step</div>
                <input type="text" name="instruction" id="instruction">
                <button type="button" onclick="addStep()">Add Step</button>
                <button type="button" onclick="removeStep()">Remove Last Step</button>
            </div>

            <div class="form-group">
                <textarea name="display_steps" id="display_steps" rows="6" cols="40" readonly></textarea>
            </div>
            
            <?php
                $q = "SELECT step_number, instruction_text FROM Instructions WHERE recipe_id = ?";
                $st = $cn->stmt_init();
                $st->prepare($q);
                $st->bind_param("i", $recipe_id);
                $st->execute();
                $st->bind_result($step_number, $instruction_text);
                
                $script_content = "";
                while ($st->fetch()) {
                    

                    echo '<script>
                            document.getElementById("display_steps").value += "'. 'Step '  . $step_number . ': ' . $instruction_text . '\\n";
                        </script>';
                }
                
                $st->close();
            ?>

            <div class="form-group">
                <input type="submit" value="Update Recipe">
            </div>

        </form>

        <script>
            const displayStepsTextarea = document.getElementById('display_steps');
            const textareaContent = displayStepsTextarea.value;
            const stepCount = (textareaContent.match(/Step/g) || []).length;
            stepNumber = stepCount + 1;

            function addStep() {
                const instructionInput = document.getElementById('instruction');
                const displayStepsTextarea = document.getElementById('display_steps');
                displayStepsTextarea.value += `Step ${stepNumber}: ${instructionInput.value}\n`;
                stepNumber++;
                document.getElementById('step_tracker').innerText = `Step: ${stepNumber}`;
                instructionInput.value = '';
            }

            function removeStep() {
                if (stepNumber >= 1) {
                    stepNumber--;
                    
                    document.getElementById('step_tracker').innerText = `Step: ${stepNumber}`;
                    const displayStepsTextarea = document.getElementById('display_steps');
                    const lines = displayStepsTextarea.value.split('\n');
                    lines.pop();
                    lines.pop();
                    displayStepsTextarea.value = lines.join('\n');
                    displayStepsTextarea.value += "\n";
                }
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

            function removeIngredient() {
                const selectedIngredientsTextarea = document.getElementById('selected_ingredients');
                const lines = selectedIngredientsTextarea.value.split('\n');
                lines.pop(); 
                lines.pop();
                selectedIngredientsTextarea.value = lines.join('\n');
                selectedIngredientsTextarea.value += "\n";
                
            }
        </script>

    </div>
</body>
</html>

<?php
$cn->close();
?>
