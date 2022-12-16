<?php 
    include("./config/db.php");
    $title = $email = $ingredients = '';
    $error = array('email' => '', 'title' => '', 'ingredients' => '');

    if(isset($_POST['submit'])) {

        // check email
        if(empty($_POST['email'])) {
            $error['email'] = 'You should input an email';
        }else{
            
            $email = $_POST['email'];
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error['email'] = 'Your email is not valid <br/>';
            }
        }

        // check title
		if(empty($_POST['title'])){
			$error['title'] = 'A title is required <br />';
		} else{
			$title = $_POST['title'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $title)){
				$error['title'] ='Title must be letters and spaces only';
			}
		}

		// check ingredients
		if(empty($_POST['ingredients'])){
			$error['ingredients'] ='At least one ingredient is required <br />';
		} else{
			$ingredients = $_POST['ingredients'];
			if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)){
				$error['ingredients'] ='Ingredients must be a comma separated list';
			}
		}

        if(!array_filter($error)){
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
           
            // create sql
            $sql = "INSERT INTO pizzas(title,email,ingredients) VALUES('$title', '$email', '$ingredients')";

            // save to db and check

            if(mysqli_query($conn, $sql)) {
                //sucess
                header('Location: index.php');
            }else{
                // error
                echo 'Query error' . mysqli_error($conn);
            }
        }
    }

?>

<!DOCTYPE html>
<html>
	
	<?php include('template/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Add a pizza</h4>
        <form action="add.php" method="POST" class="white">
            <label for="">Email: </label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
            <div class="red-text"><?php echo $error['email'] ?></div>
            <label for="">Pizza Title: </label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title) ?>">
            <div class="red-text"><?php echo $error['title'] ?></div>
            <label for="">Ingredients (comma separated):</label>
            <input type="text" name="ingredients" value="<?php echo htmlspecialchars($ingredients) ?>">
            <div class="red-text"><?php echo $error['ingredients'] ?></div>
            <div class="center">
                <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
            </div>

        </form>
    </section>

	<?php include('template/footer.php'); ?>

</html>