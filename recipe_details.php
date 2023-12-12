
<html>
<body>
<h1>Recipe Details</h1>
<?php
  $config = parse_ini_file("config.ini");   // better to hide this!
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
  $st -> close();


  $q = "SELECT category_name FROM Category WHERE category_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $category_id);
  $st->execute();
  $st->bind_result($category_name);
  $st->fetch();
  $st -> close();


  $q = "SELECT cuisine_name FROM Cuisine WHERE cuisine_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $cuisine_id);
  $st->execute();
  $st->bind_result($cuisine_name);
  $st->fetch();
  $st -> close();
  
  
  $q = "SELECT cuisine_name FROM Cuisine WHERE cuisine_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $cuisine_id);
  $st->execute();
  $st->bind_result($cuisine_name);
  $st->fetch();
  $st -> close();

  echo $recipe_name . "<br> " . $category_name . "<br>" . $cuisine_name . "<br>" . "Cost to make: $" . $price .  "<br>" . "Prep time: " . $prep_time . " minutes" . "<br>" . "Rating: " . $rating . "/5" . "<br><br>";






  $q = "SELECT restriction_id FROM RecipeDietaryRestriction WHERE recipe_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $recipe_id);
  $st->execute();
  $st->bind_result($restriction_id);

  $restrictions = array();
  while ($st->fetch()) 
  {
    $restrictions[] = array(
      "restriction_id" => $restriction_id,
    );
  }
  $st->close();

  foreach ($restrictions as $restriction) {
    $restriction_id = $restriction['restriction_id'];
    $q = "SELECT restriction_name FROM DietaryRestrictionType WHERE restriction_id = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("i", $restriction_id);
    $st->execute();
    $st->bind_result($restriction_name);

    while($st->fetch())
    {
        echo $restriction_name . "<br>";
    }
  }
  echo "<br>";


  



  $q = "SELECT ingredient_id, quantity, unit_id FROM Recipe_Ingredient WHERE recipe_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $recipe_id);
  $st->execute();
  $st->bind_result($ingredient_id, $quantity, $unit_id);

  $recipe_ingredients = array();
  while ($st->fetch()) 
  {
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
    $ingredient_st -> fetch();
    $ingredient_st -> close();
    

    $unit_q = "SELECT unit_name FROM Unit WHERE unit_id = ?";
    $unit_st = $cn->stmt_init();
    $unit_st->prepare($unit_q);
    $unit_st->bind_param("i", $unit_id);
    $unit_st->execute();
    $unit_st->bind_result($unit_name);
    $unit_st -> fetch();
    $unit_st -> close();


    if ($quantity == 1 || $unit_name == "Each"){
        $suffix = '';
    }
    else{
        $suffix = 's';
    }
    
    echo $ingredient_name . " - " . $quantity . " " . $unit_name . $suffix . "<br>";

  }
  echo "<br><br>";


  
  $q = "SELECT step_number, instruction_text FROM Instructions WHERE recipe_id = ?";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("i", $recipe_id);
  $st->execute();
  $st->bind_result($step_number, $instruction_text);

  while($st->fetch())
  {
    echo "step " . $step_number . ") " . $instruction_text . "<br>";
  }

  $st -> close();


  $cn->close();
?>
</body>
</html>
