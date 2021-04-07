<?php

?>

<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" class="form-control" name="user_firstname" value="">
    </div>
    
     <div class="form-group">
        <label for="post_status">Lastname</label>
        <input type="text" class="form-control" name="user_lastname" value="">
    </div>
        
    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" class="form-control" name="username" value="">
    </div>
    
    <div class="form-group">
        <label for="post_content">Email</label>
        <input type="email" class="form-control" name="user_email" value="">
    </div>
    
    <div class="form-group">
        <label for="post_content">Password</label>
        <input type="password" class="form-control" name="user_password" placeholder="Voer hier een nieuw wachtwoord in." value="">
    </div>
    
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Edit User">
    </div>
</form>