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

        .menu-container {
            text-align: center;
        }

        h1 {
            font-size: 24px;
        }

        .menu-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            font-size: 18px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <title>Recipe Collection App</title>
</head>
<body>
    <div class="menu-container">
        <h1>Recipe Collection App</h1>
        <a href="view_recipes.php" class="menu-button">View All Recipes</a>
        <a href="search_recipes.php" class="menu-button">Search Recipes</a>
        <a href="add_recipe.php" class="menu-button">Add Recipes</a>
        <a href="analytics.php" class="menu-button">Analytics</a>
        <a href="manage_ingredients.php" class="menu-button">Add Ingredients</a>
        <a href="shopping_list.php" class="menu-button">Shopping list</a>
    </div>
</body>
</html>
