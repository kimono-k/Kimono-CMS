<?php

/* CRUD SYSTEEM - USERS */

// Een gebruiker toevoegen in de database als de validatie correct is
function addUser() {
    // Global variables
    global $connection;
    global $user_firstname;
    global $user_lastname;
    global $user_role;
    global $username;
    global $user_email;
    global $user_password;
    global $message;

    // DATA - Als je op de submit knop sla de gegevens veilig op met SQL-injecties
    if (isset($_POST['create_user'])) {
        $user_firstname = mysqli_real_escape_string($connection, $_POST['user_firstname']);
        $user_lastname = mysqli_real_escape_string($connection, $_POST['user_lastname']);
        $user_role = mysqli_real_escape_string($connection, $_POST['user_role']);
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $user_email = mysqli_real_escape_string($connection, $_POST['user_email']);
        $user_password = mysqli_real_escape_string($connection, $_POST['user_password']);

        // Password hashing: Om wachtwoorden veilig op te slaan
        $user_password = password_hash($user_password, PASSWORD_DEFAULT); // Up-to-date password algoritme

        // Form validatie: Als alle velden niet leeg zijn, data invoeren in database.
        if (!empty($user_firstname) && !empty($user_lastname) && !empty($user_role)
            && !empty($username) && preg_match("/^[a-zA-Z0-9]*$/", $username)
            && !empty($user_email) && !empty($user_password)) {
            $query = "INSERT INTO users( 
                        user_firstname, 
                        user_lastname, 
                        user_role,
                        username, 
                        user_email, 
                        user_password 
                     ) ";

            $query .= "VALUES(
                        '{$user_firstname}',
                        '{$user_lastname}',
                        '{$user_role}',
                        '{$username}',
                        '{$user_email}',
                        '{$user_password}'
            ) ";

            $create_user_query = mysqli_query($connection, $query); // Daadwerkelijke executie query
            confirmQuery($create_user_query); // Query-check

            // Success melding
            echo "User Created: <a href='users.php'>View Users</a>";

        // Als de invoer niet a-z, A-Z of 0-9 bevat, laat dan een foutmelding zien
        } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $message = "U moet de juiste gebruikers intikken die mag alleen grote of kleine letters of nummers van 0-9 bevatten";

        // Als één van de velden leeg is, laat dan een foutmelding zien
        } else if (empty($user_firstname) || empty($user_lastname) || empty($user_role) || empty($username) ||
            empty($user_email) || empty($user_password)) {
            $message = "Zorg dat u alle velden ingevuld hebt.";

        // Als de bovenste voorwaarden niet gelden laat dan de onderste foutmelding zien
        } else {
            echo "Unexpected error has occured!";
        }
    }
}

// Leest alle gebruikers uit de database op de view_all_users.php pagina
function readAllUsers() {

    // Globale variabelen
    global $connection;
    global $query;
    global $select_users;
    global $user_id;
    global $username;
    global $user_password;
    global $user_firstname;
    global $user_lastname;
    global $user_email;
    global $user_image;
    global $user_role;

    $query = "SELECT * FROM users";
    $select_users = mysqli_query($connection, $query); // Daadwerkelijke executie van de query

    // Met de query door de gegevens heen loopen
    while($row = mysqli_fetch_assoc($select_users)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];

        // DATA - XSS-Beveiliging - De gebruikers kunnen geen HTML tags toepassen in de gegevensset
        echo "<tr>";
        echo "<td>". htmlentities($user_id) . "</td>";
        echo "<td>". htmlentities($username) . "</td>";
        echo "<td>". htmlentities($user_firstname) . "</td>";
        echo "<td>". htmlentities($user_lastname) . "</td>";
        echo "<td>". htmlentities($user_email) . "</td>";
        echo "<td>". htmlentities($user_role) . "</td>";


        // De id d.m.v. GET loopen in de value
        echo "<td><a href='users.php?change_to_admin={$user_id}'>Admin</a>
                                  </td>"; // Loop with id GET.
        echo "<td><a href='users.php?change_to_sub={$user_id}'>Subscriber</a>
                                  </td>"; // Loop with id GET.
        echo "<td><a href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a>
                                  </td>"; // Loop with id GET, look at switch in users.php
        echo "<td><a href='users.php?delete={$user_id}'>Delete</a>
                                  </td>"; // Loop with id GET.
        echo "</tr>";
    }
}

// Het bewerken van gegevens van een gebruiker in de database als de validatie correct is
function updateUser() {

    // Globale variabelen
    global $connection;
    global $user_firstname;
    global $user_lastname;
    global $user_role;
    global $username;
    global $user_email;
    global $user_password;
    global $password;
    global $the_user_id;
    global $message;

    // DATA - Als je op de edit user link klikt sla de gegevens veilig op met SQL-injecties
    if (isset($_POST['edit_user'])) {
        $user_firstname = mysqli_real_escape_string($connection, $_POST['user_firstname']);
        $user_lastname  = mysqli_real_escape_string($connection, $_POST['user_lastname']);
        $user_role = mysqli_real_escape_string($connection, $_POST['user_role']);
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $user_email = mysqli_real_escape_string($connection, $_POST['user_email']);
        $user_password = mysqli_real_escape_string($connection, $_POST['user_password']);
        $password = password_hash($user_password, PASSWORD_DEFAULT);

        // Form validatie: Als alle velden niet leeg zijn, data bewerken in de database

        // Als de invoer niet a-z, A-Z of 0-9 bevat, laat dan een foutmelding zien
        if (!preg_match("/^[a-zA-Z0-9]*$/", $user_firstname) || !preg_match("/^[a-zA-Z0-9]*$/", $user_lastname) || !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $message = "U moet de juiste voornaam, achternaam of gebruikersnaam intikken die mag alleen grote of kleine letters of nummers van 0-9 bevatten";
            echo $message;

        // Als één van de velden leeg is, laat dan een foutmelding zien
        } else if (empty($user_firstname) || empty($user_lastname) || empty($user_role) || empty($username) ||
            empty($user_email) || empty($user_password)) {
            $message = "Zorg dat u alle velden ingevuld hebt.";
            echo $message;

        // Als het form door de validatie heen is bewerk dan de gegevens uit een specifieke rij in de db
        } else {
            $query  = "UPDATE users SET ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_role = '{$user_role}', ";
            $query .= "username = '{$username}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_password = '{$password}' ";
            $query .= "WHERE user_id = {$the_user_id} ";

            $edit_user_query = mysqli_query($connection, $query); // Daadwerkelijke executie query
            confirmQuery($edit_user_query); // Query-check
            header("Location: users.php");
            exit;
        }
    }
}

// Verwijdert een specifieke gebruiker uit de database - view_all_users.php
function deleteUser() {
    global $connection;

    // Als er geen GET user_id wordt aangeklikt in een delete link, verwijder dan de data uit de database
    if (isset($_GET['delete'])) {
        $the_user_id = $_GET['delete']; // Haal het field van delete op met de value x erin.
        $query = "DELETE FROM users WHERE user_id = {$the_user_id} ";
        $delete_user_query = mysqli_query($connection, $query);
        header("Location: users.php");
    }
}

/* ALGEMENE FUNCTIES */

// Een check of de SQL-query is gelukt
function confirmQuery($result) {
    global $connection;

    if (!$result) {
        die("QUERY FAILED ." . mysqli_error($connection));
    }
}

// Laat alle categorieen voor de posts zien op edit post pagina in <select>
function showAllCategoriesForPost() {
    global $connection;

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    confirmQuery($select_categories);

    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<option value='{$cat_id}'>{$cat_title}</option>";
    }
}

// jQuery checkbox opties in view_all_posts.php
function jQueryCheckBoxes() {

    global $connection;

    if (isset($_POST['checkBoxArray'])) {
        // Iteration through the array.
        // CheckBoxArray name and value is selected postvalueID
        foreach ($_POST['checkBoxArray'] as $postValueId) {
            $bulk_options = $_POST['bulk_options']; // name = <select> Bulk options, value = <option>
            switch ($bulk_options) {
                case 'published':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                    $update_to_published_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_published_status);
                    break;

                case 'draft':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                    $update_to_draft_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_draft_status);
                    break;

                case 'delete':
                    $query = "DELETE FROM posts WHERE post_id = {$postValueId} ";
                    $update_to_delete_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_delete_status);
                    break;
            }
        }
    }
}

// Weergeeft de opties voor de post status (published of draft).
function postStatusOptions() {

    global $connection;
    global $post_status;

    if ($post_status == 'published') {
        echo "<option value='draft'>Draft</option>";

    } else {
        echo "<option value='published'>Publish</option>";
    }
}

function getParamForAllPosts() {
    global $connection;

    if (isset($_GET['source'])) {
        $source = $_GET['source'];
    } else {
        $source = '';
    }

    switch($source) {
        case 'add_post';
            include "includes/add_post.php";
            break;

        case 'edit_post';
            include "includes/edit_post.php";
            break;

        default:
            include "includes/view_all_posts.php";
            break;
    }
}

function getParamForAllComments() {
    global $connection;

    if (isset($_GET['source'])) {
        $source = $_GET['source'];
    } else {
        $source = '';
    }

    switch($source) {
        case 'add_post';
            include "includes/add_post.php";
            break;

        case 'edit_post';
            include "includes/edit_post.php";
            break;

        default:
            include "includes/view_all_comments.php";
            break;
    }
}

function readProfileData() {
    global $connection;
    global $user_id;
    global $username;
    global $user_password;
    global $user_firstname;
    global $user_lastname;
    global $user_email;
    global $user_image;
    global $user_role;

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_profile_query = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_user_profile_query)) {
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
}

// Edit profile
function editProfileButtonClicked() {
    global $connection;
    global $message;
    global $user_firstname;
    global $user_lastname;
    global $user_role;
    global $username;
    global $user_email;
    global $user_password;
    global $user_id;

    if (isset($_POST['edit_user'])) {
        $user_firstname = mysqli_real_escape_string($connection, $_POST['user_firstname']);
        $user_lastname = mysqli_real_escape_string($connection, $_POST['user_lastname']);
        $user_role = mysqli_real_escape_string($connection, $_POST['user_role']);
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $user_email = mysqli_real_escape_string($connection, $_POST['user_email']);
        $user_password = mysqli_real_escape_string($connection, $_POST['user_password']);

        if (!preg_match("/^[a-zA-Z0-9]*$/", $user_firstname) || !preg_match("/^[a-zA-Z0-9]*$/", $user_lastname) || !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $message = "U moet de juiste voornaam, achternaam of gebruikersnaam intikken die mag alleen grote of kleine letters of nummers van 0-9 bevatten";

        } else if (empty($user_firstname) || empty($user_lastname) || empty($user_role) || empty($username) ||
            empty($user_email) || empty($user_password)) {
            $message = "Zorg dat u alle velden ingevuld hebt.";

        } else {
            $query  = "UPDATE users SET ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_role = '{$user_role}', ";
            $query .= "username = '{$username}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_password = '{$user_password}' ";
            $query .= "WHERE user_id = '{$user_id}' "; // You need the id from hidden form field.

            $edit_user_query = mysqli_query($connection, $query);
            $_SESSION['username'] = $username; // The new post is also in the session now.
            confirmQuery($edit_user_query);
            header("Location: users.php");
        }
    }
}

// User role options for user edit
function userRoleOptionsForUser() {

    global $user_role;

    if ($user_role == 'admin') {
        echo "<option value='subscriber'>subscriber</option>";
    } else {
        echo "<option value='admin'>admin</option>";
    }
}

/* CRUD SYSTEEM - POSTS */

// CREATE - POSTS
function createPost() {

    global $connection;

    if (isset($_POST['create_post'])) {
        // SQL-Injectie beveiliging d.m.v. mysqli_real_escape_string.
        $post_title = mysqli_real_escape_string($connection, $_POST['title']);
        $post_author = mysqli_real_escape_string($connection, $_POST['author']);
        $post_category_id = mysqli_real_escape_string($connection, $_POST['post_category']);

        $post_status = mysqli_real_escape_string($connection, $_POST['post_status']);
        $post_tags = mysqli_real_escape_string($connection, $_POST['post_tags']);
        $post_content = mysqli_real_escape_string($connection, $_POST['post_content']);


        $post_image = $_FILES['image']['name']; // Is needed for images, name, the actual img.
        $post_image_temp = $_FILES['image']['tmp_name']; // Temporary location.

        $post_date = date('d-m-y');

        move_uploaded_file($post_image_temp, "../images/$post_image " ); // Temp to real img loc.

        // Mental note: Geen haakjes () na de LOGICAL OPERATORS.
        // XSS-beveilging: preg_match zorgt dat er geen HTML tags ingevoerd kunnen worden in het form.
        if (empty($post_title) || empty($post_author) || empty($post_status) || empty($post_tags) ||
            empty($post_content)) {
            $message = "Zorg dat u alle velden ingevuld hebt.";

        } else {
            $query = "INSERT INTO posts(
                post_category_id, 
                post_title, 
                post_author, 
                post_date,
                post_image, 
                post_content, 
                post_tags, 
                post_status
            ) ";

            $query .= "VALUES(
                {$post_category_id},
                '{$post_title}',
                '{$post_author}',
                now(),
                '{$post_image}',
                '{$post_content}',
                '{$post_tags}',
                '{$post_status}'
            ) ";
            $create_post_query = mysqli_query($connection, $query);
            confirmQuery($create_post_query);
            $the_post_id = mysqli_insert_id($connection);
            echo "<p class='bg-success'>Post Created. 
        <a href='../post.php?p_id={$the_post_id}'>View Post</a> or
        <a href='posts.php'>Edit More Posts</a></p>";
        }
    }
}

// READ - POSTS
function readPosts() {
    global $connection;
    global $post_id;
    global $post_author;
    global $post_title;
    global $post_category_id;
    global $post_status;
    global $post_image;
    global $post_tags;
    global $post_comment_count;
    global $post_date;

    $query = "SELECT * FROM posts";
    $select_posts = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_posts)) {
        $post_id = $row['post_id'];
        $post_author = $row['post_author'];
        $post_title = $row['post_title'];
        $post_category_id = $row['post_category_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];

        echo "<tr>";
        ?>

        <!-- Iteration for ID's happens here -->
        <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>

        <?php
        echo "<td>" . htmlentities($post_id) . "</td>";
        echo "<td>" . htmlentities($post_author) . "</td>";
        echo "<td>" . htmlentities($post_title) . "</td>";

        $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";

        $select_categories_id = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($select_categories_id)) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            echo "<td>" . htmlentities($cat_title) . "</td>";
        }

        echo "<td>" . htmlentities($post_status) . "</td>";

        echo "<td><img width='100' src='../images/$post_image' alt='images'></td>";
        echo "<td>" . htmlentities($post_tags) . "</td>";
        echo "<td>" . htmlentities($post_comment_count) . "</td>";
        echo "<td>" . htmlentities($post_date) . "</td>";
        echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a>
                                  </td>"; // Loop with id GET.
        echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a>
                                  </td>"; // Loop with id GET.
        echo "<td><a href='posts.php?delete={$post_id}'>Delete</a>
                                  </td>"; // Loop with id GET.
        echo "</tr>";
    }
}

// UPDATE - POSTS - ONGEBRUIKT
function updatePost() {
    global $connection;
    global $post_id;
    global $post_author;
    global $post_title;
    global $post_category_id;
    global $post_status;
    global $post_image;
    global $post_content;
    global $post_tags;
    global $post_comment_count;
    global $post_date;
    global $the_post_id;

    if (isset($_GET['p_id'])) {
        $the_post_id = $_GET['p_id'];
    }

    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
    $select_posts_by_id = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_posts_by_id)) {
        $post_id = $row['post_id'];
        $post_author = $row['post_author'];
        $post_title = $row['post_title'];
        $post_category_id = $row['post_category_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];
    }

    if (isset($_POST['update_post'])) {
        $post_author = $_POST['post_author'];
        $post_title = $_POST['post_title'];
        $post_category_id = $_POST['post_category'];
        $post_status = $_POST['post_status'];
        $post_image = $_FILES['image']['name'];
        $post_image_temp = $_FILES['image']['tmp_name'];
        $post_content = $_POST['post_content'];
        $post_tags = $_POST['post_tags'];

        move_uploaded_file($post_image_temp, "../images/$post_image");

        if (empty($post_image)) {
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
            $select_image = mysqli_query($connection, $query);

            while($row = mysqli_fetch_array($select_image)) {
                $post_image = $row['post_image'];
            }
        }

        if (empty($post_title) || empty($post_author) || empty($post_status) || empty($post_tags) ||
            empty($post_content)) {
            $message = "Zorg dat u alle velden ingevuld hebt.";

        } else {
            $query  = "UPDATE posts SET ";
            $query .= "post_title = '{$post_title}', ";
            $query .= "post_category_id = '{$post_category_id}', ";
            $query .= "post_date = now(), ";
            $query .= "post_author = '{$post_author}', ";
            $query .= "post_status = '{$post_status}', ";
            $query .= "post_tags = '{$post_tags}', ";
            $query .= "post_content = '{$post_content}', ";
            $query .= "post_image = '{$post_image}' ";
            $query .= "WHERE post_id = {$the_post_id} ";

            $update_post = mysqli_query($connection, $query);
            confirmQuery($update_post);
            echo "<p class='bg-success'>Post Updated. 
        <a href='../post.php?p_id={$the_post_id}'>View Post</a> or
        <a href='posts.php'>Edit More Posts</a></p>";
        }
    }
}

// DELETE - POSTS
function deletePost() {

    global $connection;

    if (isset($_GET['delete'])) {
        $the_post_id = $_GET['delete'];
        $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
        $delete_query = mysqli_query($connection, $query);
        header("Location: posts.php");
    }
}

/* CRUD SYSTEEM - CATEGORIEËN */

// CREATE CATEGORY - Het invoeren van een categorie in de database.
function createCategory() {
    
    global $connection;
    
    if (isset($_POST['submit'])) {
        // SQL-injectie beveiliging d.m.v. mysqli_real_escape_string.
        $cat_title = mysqli_real_escape_string($connection, $_POST['cat_title']);

        // Validation.
        if ($cat_title == "" || empty($cat_title)) {
            echo "This field should not be empty";

        // All fields filled in? - CREATE
        } else {
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUES('{$cat_title}') ";

            $create_category_query = mysqli_query($connection, $query);

            if (!$create_category_query) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
    }
}

// READ CATEGORY - Alle categorieen weergeven in een tabel
function findAllCategories() {
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>". htmlentities($cat_id) . "</td>"; // CMGT REFACTOR.
        echo "<td>". htmlentities($cat_title) ."</td>"; // CMGT REFACTOR.
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>"; // CMGT REFACTOR.
    }    
}

// UPDATE CATEGORY - Verandert de titel van de categorie waarop geklikt is (id).
function updateCategories() {
    global $connection;
    // Is the ?edit=x set? - UPDATE, key, value.
    if (isset($_GET['edit'])) {
        $cat_id = $_GET['edit'];
        $query = "SELECT * FROM categories WHERE cat_id = {$cat_id} ";
        $select_categories_id = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($select_categories_id)) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            echo "<input value='$cat_title' type='text' class='form-control' name='cat_title'>";
        }
    }

    if (isset($_POST['update_category'])) {
        $the_cat_title = $_POST['cat_title'];
        $query = "UPDATE categories SET cat_title = '{$the_cat_title}' WHERE cat_id = {$cat_id} ";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            die("QUERY FAILED" . mysqli_error($connection));
        }
    }
}

// DELETE CATEGORY - Verwijdert een categorie op basis van de GET parameter uit de database
function deleteCategories() {
    global $connection;
    // Is the ?delete=x set? - DELETE
    if (isset($_GET['delete'])) {
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php"); // Refreshes the page.
    }    
}
?>