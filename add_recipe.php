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

        /* Additional styles for the Add Recipe form */
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

        <form action="process_recipe.php" method="post">

            <!-- Recipe Information Section -->
            <div class="form-group">
                <h2>Recipe Information</h2>

                <!-- Category -->
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="">Select Category</option>
                    <option value="appetizer">Appetizer</option>
                    <option value="main_course">Main Course</option>
                    <option value="dessert">Dessert</option>
                    <!-- Add more categories as needed -->
                </select>
            </div>

            <div class="form-group">
                <!-- Cuisine -->
                <label for="cuisine">Cuisine:</label>
                <select name="cuisine" id="cuisine" required>
                    <option value="">Select Cuisine</option>
                    <option value="italian">Italian</option>
                    <option value="mexican">Mexican</option>
                    <option value="asian">Asian</option>
                    <!-- Add more cuisines as needed -->
                </select>
            </div>

            <div class="form-group">
                <!-- Cost to make -->
                <label for="cost">Cost to make:</label>
                <input type="number" name="cost" id="cost" min="0" max="999" required>
            </div>

            <div class="form-group">
                <!-- Prep Time -->
                <label for="prep_time">Prep Time:</label>
                <input type="number" name="prep_time" id="prep_time" min="0" max="999" required>
            </div>

            <div class="form-group">
                <!-- Rating -->
                <label for="rating">Rating (0-5):</label>
                <input type="number" name="rating" id="rating" min="0" max="5" required>
            </div>

            <div class="form-group">
                <!-- Dietary Restrictions -->
                <label for="dietary_restrictions">Dietary Restrictions:</label>
                <select name="dietary_restrictions" id="dietary_restrictions">
                    <option value="">Select Dietary Restrictions</option>
                    <option value="vegetarian">Vegetarian</option>
                    <option value="vegan">Vegan</option>
                    <option value="gluten_free">Gluten-Free</option>
                    <!-- Add more dietary restrictions as needed -->
                </select>
            </div>

            <!-- Ingredient Section -->
            <div class="form-group">
                <h2>Ingredients</h2>

                <!-- Ingredient Name -->
                <label for="ingredient_name">Ingredient Name:</label>
                <select name="ingredient_name" id="ingredient_name" required>
                    <option value="">Select Ingredient</option>
                    <option value="ingredient1">Ingredient 1</option>
                    <option value="ingredient2">Ingredient 2</option>
                    <option value="ingredient3">Ingredient 3</option>
                </select>

                <!-- Quantity -->
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="0" max="999" required>

                <!-- Unit -->
                <label for="unit">Unit:</label>
                <select name="unit" id="unit" required>
                    <option value="">Select Unit</option>
                    <option value="grams">Grams</option>
                    <option value="cups">Cups</option>
                    <option value="pieces">Pieces</option>
                </select>

                <!-- Button to Add Ingredient -->
                <button type="button" onclick="addIngredient()">Add Ingredient</button>

                <!-- Selected Ingredients -->
                <label for="selected_ingredients">Selected Ingredients:</label>
                <textarea name="selected_ingredients" id="selected_ingredients" rows="4" cols="40" readonly></textarea>
            </div>

            <!-- Instructions Section -->
            <div class="form-group">
                <h2>Instructions</h2>

                <!-- Step Tracker -->
                <div id="step_tracker">Step: 1</div>

                <!-- Instruction Textbox -->
                <input type="text" name="instruction" id="instruction">
                <button type="button" onclick="addStep()">Add Step</button>
            </div>

            <div class="form-group">
                <!-- Display Steps -->
                <textarea name="display_steps" id="display_steps" rows="6" cols="40" readonly></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Submit Recipe">
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

                  // Clear the form fields
                  ingredientSelect.value = '';
                  quantitySelect.value = '';
                  unitSelect.value = '';
              }
          }

        </script>
    </div>
</body>
</html>
