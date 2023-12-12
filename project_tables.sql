DROP TABLE IF EXISTS ShoppingList;
DROP TABLE IF EXISTS SimilarRecipes;
DROP TABLE IF EXISTS Favorites;
DROP TABLE IF EXISTS Instructions;
DROP TABLE IF EXISTS Recipe_Ingredient;
DROP TABLE IF EXISTS RecipeDietaryRestriction ;
DROP TABLE IF EXISTS Recipe;
DROP TABLE IF EXISTS DietaryRestrictionType;
DROP TABLE IF EXISTS Cuisine;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Unit;
DROP TABLE IF EXISTS Ingredient;


CREATE TABLE Ingredient (
    ingredient_id INT PRIMARY KEY,
    ingredient_name VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE Unit (
    unit_id INT PRIMARY KEY,
    unit_name VARCHAR(50) NOT NULL UNIQUE
);


CREATE TABLE Category (
    category_id INT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE Cuisine (
    cuisine_id INT PRIMARY KEY,
    cuisine_name VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE DietaryRestrictionType (
    restriction_id INT PRIMARY KEY,
    restriction_name VARCHAR(50) NOT NULL UNIQUE
);


CREATE TABLE Recipe (
    recipe_id INT PRIMARY KEY,
    recipe_name VARCHAR(255),
    category_id INT,
    cuisine_id INT,
    price INT,
    prep_time INT,
    rating INT,
    FOREIGN KEY (category_id) REFERENCES Category(category_id),
    FOREIGN KEY (cuisine_id) REFERENCES Cuisine(cuisine_id)
);


CREATE TABLE RecipeDietaryRestriction (
    recipe_id INT,
    restriction_id INT,
    PRIMARY KEY (recipe_id, restriction_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (restriction_id) REFERENCES DietaryRestrictionType(restriction_id)
);

CREATE TABLE Recipe_Ingredient (
    recipe_id INT,
    ingredient_id INT,
    quantity INT,
    unit_id INT,
    PRIMARY KEY (recipe_id, ingredient_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id),
    FOREIGN KEY (unit_id) REFERENCES Unit(unit_id)
);



CREATE TABLE Instructions (
    recipe_id INT,
    step_number INT,
    instruction_text TEXT,
    PRIMARY KEY (recipe_id, step_number),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id)
);


CREATE TABLE Favorites (
    recipe_id INT,
    PRIMARY KEY (recipe_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id)
);



CREATE TABLE SimilarRecipes (
    recipe_id INT,
    similar_recipe_id INT,
    PRIMARY KEY (recipe_id, similar_recipe_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (similar_recipe_id) REFERENCES Recipe(recipe_id)
);


CREATE TABLE ShoppingList (
    recipe_id INT,
    ingredient_id INT,
    PRIMARY KEY (recipe_id, ingredient_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id)
);


INSERT INTO Category (category_id, category_name) VALUES
(1, 'Breakfast'),
(3, 'Lunch'),
(2, 'Dinner'),
(4, 'Dessert'),
(5, 'Snack');


INSERT INTO Cuisine (cuisine_id, cuisine_name) VALUES
(1, 'Italian'),
(2, 'Mexican'),
(3, 'Chinese'),
(4, 'Indian'),
(5, 'Mediterranean'),
(6, 'Japanese'),
(7, 'Thai'),
(9, 'French'),
(10, 'Greek'),
(11, 'Spanish'),
(12, 'American'),
(13, 'Brazilian'),
(14, 'Korean'),
(15, 'Vietnamese'),
(16, 'Lebanese'),
(17, 'Turkish'),
(18, 'Moroccan'),
(19, 'African'),
(8, 'Caribbean'),
(20, 'N/A');


INSERT INTO DietaryRestrictionType (restriction_id, restriction_name) VALUES
(1, 'Vegetarian'),
(2, 'Vegan'),
(3, 'Gluten-Free'),
(4, 'Keto'),
(5, 'Paleo'),
(6, 'Pescatarian'),
(7, 'Lactose-Free'),
(8, 'Nut-Free'),
(9, 'N/A');

INSERT INTO Recipe (recipe_id, recipe_name, category_id, cuisine_id, price, prep_time, rating) VALUES
(1, 'Pancakes', 1, 1, 8, 20, 4),
(2, 'Spaghetti Bolognese', 2, 1, 15, 45, 5),
(3, 'Chicken Curry', 2, 4, 20, 50, 3),
(4, 'Chocolate Brownies', 4, 1, 10, 30, 5),
(5, 'Caprese Salad', 3, 5, 12, 15, 4),
(6, 'Sushi Rolls', 2, 6, 25, 60, 3),
(7, 'Omelette', 1, 1, 10, 15, 4),
(8, 'Grilled Chicken Caesar Salad', 3, 5, 18, 30, 5),
(9, 'Tomato Basil Pasta', 2, 1, 12, 25, 2),
(10, 'Chocolate Chip Cookies', 4, 1, 8, 20, 5),
(11, 'Vegetarian Stir-Fry', 2, 3, 15, 30, 3),
(12, 'Mushroom Risotto', 2, 1, 20, 40, 4),
(13, 'Chicken Quesadillas', 2, 2, 15, 35, 4),
(14, 'Classic Margherita Pizza', 2, 1, 14, 30, 5),
(15, 'Shrimp Scampi', 2, 1, 22, 35, 3),
(16, 'Greek Salad', 3, 5, 14, 20, 4),
(17, 'Beef Tacos', 2, 2, 18, 40, 5),
(18, 'Caramelized Onion and Goat Cheese Tart', 4, 1, 16, 45, 3),
(19, 'Lemon Garlic Roast Chicken', 2, 5, 25, 50, 4),
(20, 'Vegetable Lasagna', 2, 1, 20, 45, 5),
(21, 'Baked Ziti', 2, 1, 15, 35, 2),
(22, 'Honey Mustard Glazed Salmon', 2, 5, 20, 30, 5),
(23, 'Pesto Pasta with Cherry Tomatoes', 2, 1, 15, 25, 4),
(24, 'Teriyaki Chicken Rice Bowl', 3, 6, 18, 40, 3),
(25, 'Strawberry Shortcake', 4, 1, 12, 30, 5);



INSERT INTO Instructions (recipe_id, step_number, instruction_text) VALUES
-- Instructions for Pancakes (Recipe ID: 1)
(1, 1, 'Mix flour, sugar, and eggs in a bowl.'),
(1, 2, 'Cook pancakes on a griddle.'),

-- Instructions for Spaghetti Bolognese (Recipe ID: 2)
(2, 1, 'Cook ground beef in a pan.'),
(2, 2, 'Add tomatoes and simmer.'),

-- Instructions for Chicken Curry (Recipe ID: 3)
(3, 1, 'Cook chicken in curry sauce.'),
(3, 2, 'Serve over rice.'),

-- Instructions for Chocolate Brownies (Recipe ID: 4)
(4, 1, 'Mix melted chocolate with flour and eggs.'),
(4, 2, 'Bake in a preheated oven.'),

-- Instructions for Caprese Salad (Recipe ID: 5)
(5, 1, 'Slice tomatoes and cheese.'),
(5, 2, 'Layer tomatoes, cheese, and basil.'),

-- Instructions for Sushi Rolls (Recipe ID: 6)
(6, 1, 'Prepare sushi rice and ingredients.'),
(6, 2, 'Roll sushi using bamboo mat.'),

-- Instructions for Omelette (Recipe ID: 7)
(7, 1, 'Whisk eggs and pour into a hot pan.'),
(7, 2, 'Add desired fillings and fold in half.'),

-- Instructions for Grilled Chicken Caesar Salad (Recipe ID: 8)
(8, 1, 'Grill chicken and slice into strips.'),
(8, 2, 'Toss with Caesar salad dressing and serve.'),

-- Instructions for Tomato Basil Pasta (Recipe ID: 9)
(9, 1, 'Cook pasta according to package instructions.'),
(9, 2, 'Mix with fresh tomatoes and basil.'),

(10, 1, 'Cream together butter and sugar.'),
(10, 2, 'Mix in eggs and vanilla.'),
(10, 3, 'Combine flour, baking soda, and salt.'),
(10, 4, 'Add dry ingredients to wet ingredients and mix well.'),
(10, 5, 'Fold in chocolate chips.'),
(10, 6, 'Drop spoonfuls of dough onto a baking sheet.'),
(10, 7, 'Bake in a preheated oven until golden brown.'),

-- Instructions for Vegetarian Stir-Fry (Recipe ID: 11)
(11, 1, 'Stir-fry a mix of vegetables in a hot pan.'),
(11, 2, 'Add soy sauce and seasonings.'),
(11, 3, 'Serve over rice.'),

-- Instructions for Mushroom Risotto (Recipe ID: 12)
(12, 1, 'Saute mushrooms and onions in olive oil.'),
(12, 2, 'Add Arborio rice and cook until translucent.'),
(12, 3, 'Gradually add chicken or vegetable broth while stirring.'),
(12, 4, 'Continue cooking until rice is creamy and cooked through.'),

-- Instructions for Chicken Quesadillas (Recipe ID: 13)
(13, 1, 'Cook chicken and shred.'),
(13, 2, 'Layer tortillas with cheese, chicken, and vegetables.'),
(13, 3, 'Cook in a pan until cheese is melted.'),

-- Instructions for Classic Margherita Pizza (Recipe ID: 14)
(14, 1, 'Roll out pizza dough and spread with tomato sauce.'),
(14, 2, 'Add fresh mozzarella and basil leaves.'),
(14, 3, 'Bake until crust is golden brown.'),

-- Instructions for Shrimp Scampi (Recipe ID: 15)
(15, 1, 'Saute shrimp in garlic and butter.'),
(15, 2, 'Add white wine and lemon juice.'),
(15, 3, 'Serve over cooked linguine.'),

-- Instructions for Greek Salad (Recipe ID: 16)
(16, 1, 'Combine diced tomatoes, cucumbers, olives, and feta cheese.'),
(16, 2, 'Toss with olive oil and lemon juice.'),
(16, 3, 'Season with salt and pepper.'),

-- Instructions for Beef Tacos (Recipe ID: 17)
(17, 1, 'Season ground beef with taco seasoning.'),
(17, 2, 'Fill taco shells with seasoned beef and desired toppings.'),

-- Instructions for Caramelized Onion and Goat Cheese Tart (Recipe ID: 18)
(18, 1, 'Saute onions until caramelized.'),
(18, 2, 'Roll out pastry dough and spread with goat cheese.'),
(18, 3, 'Top with caramelized onions and bake.'),

-- Instructions for Lemon Garlic Roast Chicken (Recipe ID: 19)
(19, 1, 'Rub chicken with lemon, garlic, and herbs.'),
(19, 2, 'Roast in the oven until golden brown.'),

-- Instructions for Vegetable Lasagna (Recipe ID: 20)
(20, 1, 'Layer lasagna noodles with ricotta cheese, vegetables, and marinara sauce.'),
(20, 2, 'Bake until bubbly and golden.'),

-- Instructions for Baked Ziti (Recipe ID: 21)
(21, 1, 'Cook ziti pasta and mix with marinara sauce and ricotta cheese.'),
(21, 2, 'Top with mozzarella and bake until cheese is melted.'),

-- Instructions for Honey Mustard Glazed Salmon (Recipe ID: 22)
(22, 1, 'Mix honey, mustard, and soy sauce for the glaze.'),
(22, 2, 'Brush glaze on salmon fillets and bake until flaky.'),

-- Instructions for Pesto Pasta with Cherry Tomatoes (Recipe ID: 23)
(23, 1, 'Cook pasta and toss with pesto sauce.'),
(23, 2, 'Top with halved cherry tomatoes and Parmesan cheese.'),

-- Instructions for Teriyaki Chicken Rice Bowl (Recipe ID: 24)
(24, 1, 'Stir-fry chicken in teriyaki sauce.'),
(24, 2, 'Serve over steamed rice with vegetables.'),

-- Instructions for Strawberry Shortcake (Recipe ID: 25)
(25, 1, 'Slice strawberries and sweeten with sugar.'),
(25, 2, 'Layer strawberries with whipped cream and shortcake.');



-- Ingredients Inserts

INSERT INTO Ingredient (ingredient_id, ingredient_name) VALUES
(1, 'Flour'),
(2, 'Sugar'),
(3, 'Eggs'),
(4, 'Chicken'),
(5, 'Tomatoes'),
(6, 'Rice'),
(7, 'Olive Oil'),
(8, 'Chocolate'),
(9, 'Milk'),
(10, 'Cheese'),
(11, 'Basil'),
(12, 'Soy Sauce'),
(13, 'Garlic'),
(14, 'Ground Beef'),
(15, 'Spaghetti'),
(16, 'Shrimp'),
(17, 'Linguine'),
(18, 'Cucumber'),
(19, 'Feta Cheese'),
(20, 'Taco Seasoning'),
(21, 'Pastry Dough'),
(22, 'Goat Cheese'),
(23, 'Lemon'),
(24, 'Ricotta Cheese'),
(25, 'Ziti'),
(26, 'Salmon'),
(27, 'Honey'),
(28, 'Mustard'),
(29, 'Teriyaki Sauce'),
(30, 'Strawberries'),
(31, 'Whipped Cream'),
(32, 'Shortcake'),
(33, 'Bell Peppers'),
(34, 'Avocado'),
(35, 'Black Beans'),
(36, 'Tortillas'),
(37, 'Cheddar Cheese'),
(38, 'Sour Cream'),
(39, 'Cilantro'),
(40, 'Lime'),
(41, 'Cumin'),
(42, 'Paprika'),
(43, 'Cayenne Pepper'),
(44, 'Oregano'),
(45, 'Thyme'),
(46, 'Parsley'),
(47, 'Dijon Mustard'),
(48, 'Maple Syrup'),
(49, 'Walnuts'),
(50, 'Pecans'),
(51, 'Almonds'),
(52, 'Peanuts'),
(53, 'Cashews'),
(54, 'Pine Nuts'),
(55, 'Sesame Seeds'),
(56, 'Peanut Butter'),
(57, 'Coconut Milk'),
(58, 'Curry Powder'),
(59, 'Cinnamon'),
(60, 'Vanilla Extract'),
(61, 'Nutmeg'),
(62, 'Ginger'),
(63, 'Cocoa Powder'),
(64, 'Yogurt'),
(65, 'Eggplant'),
(66, 'Zucchini'),
(67, 'Spinach'),
(68, 'Artichokes'),
(69, 'Red Onion'),
(70, 'Capers'),
(71, 'Mushrooms'),
(72, 'Pineapple'),
(73, 'Ham'),
(74, 'Pepperoni'),
(75, 'Olives'),
(76, 'Pesto'),
(77, 'Sun-Dried Tomatoes'),
(78, 'Pumpkin'),
(79, 'Butternut Squash'),
(80, 'Asparagus'),
(81, 'Green Beans'),
(82, 'Brussels Sprouts'),
(83, 'Cauliflower'),
(84, 'Cabbage'),
(85, 'Egg Noodles'),
(86, 'Sausage'),
(87, 'Bacon'),
(88, 'Cranberries'),
(89, 'Pomegranate Seeds'),
(90, 'Parmesan Cheese');



-- Inserting data into Unit table
INSERT INTO Unit (unit_id, unit_name) VALUES
(1, 'Teaspoon'),
(2, 'Tablespoon'),
(3, 'Cup'),
(4, 'Ounce'),
(5, 'Gram'),
(6, 'Milliliter'),
(7, 'Liter'),
(8, 'Each'),
(9, 'Pound'),
(10, 'Package');

-- Recipe_Ingredient Inserts

INSERT INTO Recipe_Ingredient (recipe_id, ingredient_id, quantity, unit_id) VALUES
-- Pancakes
(1, 1, 2, 3), -- Flour (Cup)
(1, 2, 2, 3), -- Sugar (Cup)
(1, 3, 2, 8), -- Eggs (Each)
(1, 9, 1, 6), -- Milk (Milliliter)

-- Spaghetti Bolognese
(2, 15, 300, 3), -- Spaghetti (Cup)
(2, 14, 250, 5), -- Ground Beef (Gram)
(2, 5, 500, 3), -- Tomatoes (Cup)
(2, 13, 2, 2), -- Garlic (Tablespoon)
(2, 7, 2, 2), -- Olive Oil (Tablespoon)
(2, 11, 1, 8), -- Basil (Each)

-- Chicken Curry
(3, 4, 500, 5), -- Chicken (Gram)
(3, 6, 1, 3), -- Rice (Cup)
(3, 13, 2, 2), -- Garlic (Tablespoon)
(3, 12, 3, 2), -- Soy Sauce (Tablespoon)

-- Chocolate Brownies
(4, 2, 1, 3), -- Sugar (Cup)
(4, 8, 200, 5), -- Chocolate (Gram)
(4, 3, 3, 8), -- Eggs (Each)
(4, 1, 1, 3), -- Flour (Cup)

-- Caprese Salad
(5, 10, 150, 5), -- Cheese (Gram)
(5, 11, 1, 8), -- Basil (Each)
(5, 5, 2, 2), -- Tomatoes (Tablespoon)
(5, 7, 2, 2), -- Olive Oil (Tablespoon)

-- Sushi Rolls
(6, 6, 2, 3), -- Rice (Cup)
(6, 7, 2, 2), -- Olive Oil (Tablespoon)

-- Omelette
(7, 3, 3, 8), -- Eggs (Each)
(7, 5, 2, 2), -- Tomatoes (Tablespoon)


-- Grilled Chicken Caesar Salad
(8, 4, 400, 5), -- Chicken (Gram)
(8, 7, 3, 2), -- Olive Oil (Tablespoon)

-- Tomato Basil Pasta
(9, 5, 3, 2), -- Tomatoes (Tablespoon)
(9, 1, 2, 3), -- Flour (Cup)
(9, 13, 2, 2), -- Garlic (Tablespoon)

-- Chocolate Chip Cookies
(10, 2, 2, 3), -- Sugar (Cup)
(10, 8, 200, 5), -- Chocolate (Gram)
(10, 3, 2, 3), -- Eggs (Cup)
(10, 1, 1, 3), -- Flour (Cup)

-- Vegetarian Stir-Fry
(11, 6, 2, 3), -- Rice (Cup)


-- Mushroom Risotto
(12, 6, 2, 3), -- Rice (Cup)
(12, 13, 2, 2), -- Garlic (Tablespoon)
(12, 14, 250, 5), -- Ground Beef (Gram)
(12, 7, 2, 2), -- Olive Oil (Tablespoon)

-- Chicken Quesadillas
(13, 4, 300, 3), -- Chicken (Cup)
(13, 9, 1, 6), -- Milk (Milliliter)
(13, 5, 2, 2), -- Tomatoes (Tablespoon)

-- Classic Margherita Pizza
(14, 5, 3, 8), -- Cucumber (Each)
(14, 6, 2, 3), -- Rice (Cup)
(14, 12, 3, 2), -- Soy Sauce (Tablespoon)
(14, 7, 2, 2), -- Olive Oil (Tablespoon)

-- Shrimp Scampi
(15, 7, 3, 2), -- Olive Oil (Tablespoon)
(15, 13, 2, 2), -- Garlic (Tablespoon)
(15, 12, 2, 2), -- Soy Sauce (Tablespoon)

-- Greek Salad
(16, 5, 4, 8), -- Cucumber (Each)
(16, 6, 2, 3), -- Rice (Cup)
(16, 14, 250, 5), -- Ground Beef (Gram)
(16, 11, 1, 8), -- Basil (Each)

-- Beef Tacos
(17, 14, 250, 5), -- Ground Beef (Gram)
(17, 5, 2, 2), -- Tomatoes (Tablespoon)
(17, 11, 1, 8), -- Basil (Each)
(17, 15, 300, 3), -- Tortillas (Cup)

-- Caramelized Onion and Goat Cheese Tart
(18, 8, 200, 5), -- Goat Cheese (Gram)
(18, 13, 2, 2), -- Garlic (Tablespoon)
(18, 11, 1, 8), -- Basil (Each)
(18, 3, 2, 3), -- Eggs (Cup)

-- Lemon Garlic Roast Chicken
(19, 4, 500, 5), -- Chicken (Gram)
(19, 7, 3, 2), -- Olive Oil (Tablespoon)
(19, 13, 2, 2), -- Garlic (Tablespoon)
(19, 15, 400, 5), -- Lemon (Cup)

-- Vegetable Lasagna
(20, 1, 2, 3), -- Flour (Cup)
(20, 6, 2, 3), -- Rice (Cup)
(20, 13, 2, 2), -- Garlic (Tablespoon)
(20, 11, 1, 8), -- Basil (Each)

-- Baked Ziti
(21, 2, 2, 3), -- Sugar (Cup)
(21, 8, 200, 5), -- Chocolate (Gram)
(21, 3, 2, 3), -- Eggs (Cup)
(21, 15, 300, 3), -- Ziti (Cup)

-- Honey Mustard Glazed Salmon
(22, 4, 400, 5), -- Chicken (Gram)
(22, 7, 3, 2), -- Olive Oil (Tablespoon)
(22, 13, 2, 2), -- Garlic (Tablespoon)
(22, 16, 200, 5), -- Salmon (Gram)
(22, 10, 150, 5), -- Mustard (Gram)

-- Pesto Pasta with Cherry Tomatoes
(23, 5, 2, 2), -- Tomatoes (Tablespoon)
(23, 1, 2, 3), -- Flour (Cup)
(23, 13, 2, 2), -- Garlic (Tablespoon)
(23, 11, 1, 8), -- Basil (Each)

-- Teriyaki Chicken Rice Bowl
(24, 4, 400, 5), -- Chicken (Gram)
(24, 6, 2, 3), -- Rice (Cup)
(24, 13, 2, 2), -- Garlic (Tablespoon)
(24, 12, 3, 2), -- Soy Sauce (Tablespoon)

-- Strawberry Shortcake
(25, 2, 2, 3), -- Sugar (Cup)
(25, 8, 200, 5), -- Chocolate (Gram)
(25, 3, 2, 3), -- Eggs (Cup)
(25, 17, 300, 3); -- Strawberries (Cup)


-- Pancakes (Vegetarian, Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(1, 1), -- Vegetarian
(1, 8); -- Nut-Free

-- Spaghetti Bolognese (Gluten-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(2, 7); -- Lactose-Free

-- Chicken Curry (Not Vegan, Lactose-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(3, 7); -- Lactose-Free

-- Chocolate Brownies (Gluten-Free, Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(4, 8); -- Nut-Free

-- Caprese Salad (Vegetarian, Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(5, 1), -- Vegetarian
(5, 8); -- Nut-Free

-- Sushi Rolls (Vegan)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(6, 2); -- Vegan

-- Omelette (Vegetarian, Lactose-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(7, 1), -- Vegetarian
(7, 7); -- Lactose-Free

-- Grilled Chicken Caesar Salad (Lactose-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(8, 7); -- Lactose-Free

-- Tomato Basil Pasta (Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(9, 8); -- Nut-Free

-- Chocolate Chip Cookies (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(10, 1); -- Vegetarian

-- Vegetarian Stir-Fry (Vegan)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(11, 2); -- Vegan

-- Mushroom Risotto (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(12, 1); -- Vegetarian

-- Chicken Quesadillas (Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(13, 8); -- Nut-Free

-- Classic Margherita Pizza (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(14, 1); -- Vegetarian

-- Shrimp Scampi (Lactose-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(15, 7); -- Lactose-Free

-- Greek Salad (Vegetarian, Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(16, 1), -- Vegetarian
(16, 8); -- Nut-Free

-- Beef Tacos (Gluten-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(17, 3); -- Gluten-Free

-- Caramelized Onion and Goat Cheese Tart (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(18, 1); -- Vegetarian

-- Lemon Garlic Roast Chicken (Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(19, 8); -- Nut-Free

-- Vegetable Lasagna (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(20, 1); -- Vegetarian

-- Baked Ziti (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(21, 1); -- Vegetarian

-- Honey Mustard Glazed Salmon (Lactose-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(22, 7); -- Lactose-Free

-- Pesto Pasta with Cherry Tomatoes (Vegan)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(23, 2); -- Vegan

-- Teriyaki Chicken Rice Bowl (Nut-Free)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(24, 8); -- Nut-Free

-- Strawberry Shortcake (Vegetarian)
INSERT INTO RecipeDietaryRestriction (recipe_id, restriction_id) VALUES
(25, 1); -- Vegetarian

