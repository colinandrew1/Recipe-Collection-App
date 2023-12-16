<?php
  $config = parse_ini_file("config.ini");   // better to hide this!
  $server = $config["host"];
  $username = $config["user"];
  $password = $config["password"];
  $database = $config["database"];
  $cn = mysqli_connect($server, $username, $password, $database);
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
    <title>Search Recipes</title>
</head>
<body>
    <div class="add-recipe">
        <h1>Search Recipes</h1>

        <form method="post" action="recipe_results.php">

            <div class="form-group">
                <h2>Recipe Information</h2>

                <label for="category">Category:</label>
                <select name="category" id="category">
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
                <select name="cuisine" id="cuisine">
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

            <div class="search-recipes">

          <div class="form-group">
              <label for="min_cost">Minimum Cost:</label>
              <input type="number" name="min_cost" id="min_cost" min="0" max="999">
          </div>

          <div class="form-group">
              <label for="max_cost">Max Cost:</label>
              <input type="number" name="max_cost" id="max_cost" min="0" max="999">
          </div>

          <div class="form-group">
              <label for="min_prep_time">Minimum Prep Time:</label>
              <input type="number" name="min_prep_time" id="min_prep_time" min="0" max="999">
          </div>

          <div class="form-group">
              <label for="max_prep_time">Maximum Prep Time:</label>
              <input type="number" name="max_prep_time" id="max_prep_time" min="0" max="999">
          </div>

          <div class="form-group">
              <label for="min_rating">Minimum Rating (0-5):</label>
              <input type="number" name="min_rating" id="min_rating" min="0" max="5">
          </div>

          <div class="form-group">
              <label for="max_rating">Maximum Rating (0-5):</label>
              <input type="number" name="max_rating" id="max_rating" min="0" max="5">
          </div>

            <div class="form-group">
                <label for="dietary_restrictions">Dietary Restrictions:</label>
                <select name="dietary_restrictions" id="dietary_restrictions">
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
                <input type="submit" value="Search Recipes">
            </div>

        </form>
        
    </div>
</body>
</html>
