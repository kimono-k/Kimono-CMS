<?php
include "includes/db.php";
include "includes/header.php"; 
?>

<?php
// Global variables.
$username = '';
$email = '';
$password = '';
$message = '';
global $connection;
?>

<?php
if (isset($_POST['submit'])) {
    
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    // VALIDATION
    if (!empty($username) && preg_match("/^[a-zA-Z0-9]*$/", $username) && !empty($email) && !empty($password)) {
        // Hashing
        $password = password_hash($password, PASSWORD_DEFAULT);
    
        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query .= "VALUES('{$username}', '{$email}', '{$password}', '' )"; // 1 admin.
        $register_user_query = mysqli_query($connection, $query); // Fixed.
    
        if (!$register_user_query) {
            die("QUERY FAILED " . mysqli_error($connection) . ' ' . mysqli_errno($connection));
        }
        
        $message = "U bent geregistreerd!";
        
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $message = "U moet de juiste gebruikers intikken die mag alleen grote of kleine letters of nummers van 0-9 bevatten";
        
    } else if (empty($email) || empty($password)) {
        $message = "Zorg dat u alle velden ingevuld hebt."; 
    } 
 
} else {
    $message = "";
}
?>


    <!-- Navigation -->
    <?php  
    include "includes/navigation.php"; 
    ?>
    
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Registreren</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <b class="text-danger"><?= $message; ?></b>
                        
                        <!-- XSS Beveiliging: htmlentities -->
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Voer hier uw gebruikersnaam in" autocomplete="off" value="<?= htmlentities($username); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Bijvoorbeeld: bob@cmgt.nl" autocomplete="off" value="<?= htmlentities($email); ?>">
                        </div>
                        
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Voer hier uw wachtwoord in" autocomplete="off" value="">
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>



<?php include "includes/footer.php";?>
