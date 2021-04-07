<?php
include "db.php";
session_start(); // Zorgt ervoor dat $_SESSION tot mijn beschikking is.

// Als de er op de submit knop geklikt is dan
if (isset($_POST['login'])) {

    // Laat een foutmelding zien wanneer er de velden geen waardes bevatten
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        header("Location: ../index.php?error=1");
        exit;
    }

    // Veilig opslaan van POST data (Geen SQL-injecties)
    global $connection;
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Als de gebruikersnaam of wachtwoord veld leeg is laat dan een foutmelding zien
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=1");
        exit;
    }

    // Controleren of de gebruiker bestaat in de database
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query); // Daadwerkelijke executie van de query.

    // Loop door de gegevens heen van de specifieke gebruiker
    while($row = mysqli_fetch_assoc($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
    }

    // Checken of het wachtwoord overeen komt met het gehashte wachtwoord in de database en kijken en de rol 'admin' is
    if ($username === $db_username && password_verify($password, $db_user_password) && $db_user_role == "admin") {
        // Neem sessie variabelen mee naar admin pagina om daarmee te werken.
        $_SESSION['username'] = $db_username;
        $_SESSION['password'] = $password;
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;
        header("Location: ../admin");
        exit;

        // Als het wachtwooord NIET overeenkomt met het gehashte wachtwoord laat een foutmelding zien op index.php
    } else {
        header("Location: ../index.php?error=2");
        exit;
    }
}