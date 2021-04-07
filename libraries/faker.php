<?php
require_once "../includes/db.php";
require_once '../vendor/autoload.php';

// Globale variabelen
global $connection;

// Static method create(); aanroepen, want je hoeft geen instantie van de class te maken met de new keyword
$faker = Faker\Factory::create(); // Object (class), Faker\Factory object

// Geef melding dat er data is toegevoegd aan vaker.
echo "De willekeurige records zijn toegevoegd aan je user tabel zie PHPMyAdmin";

// Met de for loop kan ik x keer kan ik de tabel van de gebruikers vullen.
for ($i = 0; $i < 20; $i++) {
    // In het faker object de properties ophalen door $faker->property.
    // Elke property is random gegenereerd door Faker bij elke iteratie.
    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) VALUES(
        '$faker->firstName',
        '$faker->lastName',
        'admin', -- Een eigen waarde.
        '$faker->userName',
        '$faker->safeEmail',
        '$faker->password'
    )";

    // De uitvoering van de query op de database
    $result = mysqli_query($connection, $query);
}
?>