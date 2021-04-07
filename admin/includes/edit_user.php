<?php

if (isset($_GET['edit_user'])) {

    $the_user_id = $_GET['edit_user'];

    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_users_query = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_users_query)) {
          $user_id = $row['user_id'];
          $username = $row['username'];
          $user_password = $row['user_password'];
          $user_firstname = $row['user_firstname'];
          $user_lastname = $row['user_lastname'];
          $user_email = $row['user_email'];
          $user_image = $row['user_image'];
          $user_role = $row['user_role'];
    }
}

updateUser();
?>

<form action="" method="post" enctype="multipart/form-data">
    

    
    <!-- XSS beveiliging: <script> is onbruikbaar door preg_match -->
    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
    </div>
    
     <div class="form-group">
        <label for="post_status">Lastname</label>
        <input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
    </div>
    
    <div class="form-group">
        <select name="user_role" id="">
            
            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
           
            <?php
            if ($user_role == 'admin') {
                echo "<option value='subscriber'>subscriber</option>";
            } else {
                echo "<option value='admin'>admin</option>";
            }
            ?>

        </select>
    </div>

    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" class="form-control" name="username" value="<?php echo htmlentities($username); ?>">
    </div>
    
    <div class="form-group">
        <label for="post_content">Email</label>
        <input type="email" class="form-control" name="user_email" value="<?php echo htmlentities($user_email); ?>">
    </div>
    
    <div class="form-group">
        <label for="post_content">Password</label>
        <input type="password" class="form-control" name="user_password" placeholder="Voer hier een nieuw wachtwoord in." value=""> 
    </div>
    
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Edit User">
    </div>
    
</form>