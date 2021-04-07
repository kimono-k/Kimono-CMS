 <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Username</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                              <tbody>

                              <?php
                              readAllUsers();
                              ?>
                        </tbody>
                        </table>
                        
                        <?php
                        if (isset($_GET['change_to_admin'])) {
                            $the_user_id = $_GET['change_to_admin'];
                            $query = "UPDATE users SET user_role = 'admin' WHERE user_id = $the_user_id";
                            $change_to_admin_query = mysqli_query($connection, $query);
                            header("Location: users.php");
                        }

                        if (isset($_GET['change_to_sub'])) {
                            $the_user_id = $_GET['change_to_sub'];
                            $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = $the_user_id";
                            $change_to_sub_query = mysqli_query($connection, $query);
                            header("Location: users.php");                            
                        }
                        
                        deleteUser();
                        ?>