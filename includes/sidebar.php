<div class="col-md-4">
    <!-- Blog Search Well -->
    <div class="well">
        <h4>Blog Search</h4>
        <form action="search.php" method="post">
        <div class="input-group">
            <input name="search" type="text" class="form-control">
            <span class="input-group-btn">
                <button name="submit" class="btn btn-default" type="submit">
                    <span class="glyphicon glyphicon-search"></span>
            </button>
            </span>
        </div>
        </form> <!-- Search form -->
        <!-- /.input-group -->
    </div>

    <!-- Login -->
    <div class="well">
        <h4>Login</h4>
        <form action="includes/login.php" method="post"> <!-- Het formulier wordt extern afgehandeld -->

        <span>
        <?php
        // GET specifieke error codes.
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 1) {
                echo "Alle velden moeten ingevuld zijn";
            } else if ($_GET['error'] == 2) {
                echo "Uw gebruikersnaam of wachtwoord klopt niet.";
            }
        }
        ?>
        </span>

        <div class="form-group">
            <input name="username" type="text" class="form-control" placeholder="Enter Username">
        </div>

        <div class="input-group">
            <input name="password" type="password" class="form-control" placeholder="Enter Password">
            <span class="input-group-btn">
                <button class="btn btn-primary" name="login" type="submit">Submit</button>
            </span>

        </div>
        </form> <!-- Search form -->
        <!-- /.input-group -->
    </div>


    <!-- Blog Categories Well -->
    <div class="well">

    <?php
    $query = "SELECT * FROM categories";
    $select_categories_sidebar = mysqli_query($connection, $query);
    ?>

        <h4>Blog Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">

                <?php
                // Iteration from the database for list items.
                while($row = mysqli_fetch_assoc($select_categories_sidebar)) {
                    $cat_title = $row['cat_title'];
                    $cat_id = $row['cat_id'];

                    echo "<li><a href='category.php?category=$cat_id'>" . htmlentities($cat_title) . "</a></li>"; // CMGT REFACTOR TIME.
                }
                ?>

                </ul>
            </div>
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php
    include "widget.php";
    ?>
    </div>