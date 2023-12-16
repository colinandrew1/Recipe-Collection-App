<?php
    $config = parse_ini_file("config.ini");
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];
    $cn = mysqli_connect($server, $username, $password, $database);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $recipe_id = $_POST['recipe_id'];



        $q = "DELETE FROM RecipeDietaryRestriction WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();
        

        
        $q = "DELETE FROM Recipe_Ingredient WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();


        $q = "DELETE FROM Instructions WHERE recipe_id=?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();


        $q = "DELETE FROM Recipe WHERE recipe_id = ?";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $st->bind_param("i", $recipe_id);
        $st->execute();
        $st->close();

        
        header("Location: recipe_collection_app.php");
        exit();
    }
?>