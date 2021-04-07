<?php
include "includes/admin_header.php";
?>
                    
<?php
// Globale variabelen
$message = "";
?>
                     
<?php
readProfileData();
?>
      
<?php
editProfileButtonClicked();
?>
       
        <div id="wrapper">

        <!-- Navigation -->
        <?php
        include "includes/admin_navigation.php";
        ?>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        
                         <h1 class="page-header">
                             Welcome to admin
                            <small>Author</small>
                        </h1>
                        
                        <form action="" method="post" enctype="multipart/form-data">
                        
                            <span><?= $message; ?></span>
                        
                            <div class="form-group">
                                <input type="hidden" value="<?php echo $user_id;?>" class="form-control" name="user_id">
                            </div>
    
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

                                    <option value="subscriber"><?php echo $user_role; ?></option>

                                    <?php
                                    userRoleOptionsForUser();
                                    ?>

                                </select>
                            </div>

                        <!--
                            <div class="form-group">
                                <label for="post_image">Post Image</label>
                                <input type="file" name="image">
                            </div>
                        -->

                            <div class="form-group">
                                <label for="post_tags">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
                            </div>

                            <div class="form-group">
                                <label for="post_content">Email</label>
                                <input type="email" class="form-control" name="user_email" value="<?php echo $user_email; ?>">
                            </div>

                            <div class="form-group">
                                <label for="post_content">Password</label>
                                <input type="password" class="form-control" name="user_password" value="<?php echo $user_password; ?>">
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
        
<?php 
include "includes/admin_footer.php";
?>