<?php
require 'data.php';

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = mysqli_real_escape_string($mysqli, test_input($_POST['user_name']));
    $last_name = mysqli_real_escape_string($mysqli, test_input($_POST['last_name']));
    $first_name = mysqli_real_escape_string($mysqli, test_input($_POST ['first_name']));
    $age = mysqli_real_escape_string($mysqli, test_input($_POST ['age']));
    $birthday = mysqli_real_escape_string($mysqli, test_input($_POST['birthday']));
    $gender = mysqli_real_escape_string($mysqli, test_input($_POST['gender']));
    $hobbies = mysqli_real_escape_string($mysqli, test_input($_POST['hobbies']));
    $banking_card = mysqli_real_escape_string($mysqli, test_input($_POST['banking_card']));
    $password = mysqli_real_escape_string($mysqli, test_input($_POST['password']));
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $about_myself = mysqli_real_escape_string($mysqli, test_input($_POST['about_myself']));
    $ListChoise_category = mysqli_real_escape_string($mysqli, test_input($_POST['ListChoise_category']));

    if (!preg_match("/^[a-zA-Z ]*$/", $user_name)) {
        $user_name_err = "Only letters and white space";
    }
    if (empty($user_name)) {
        $user_name_err = 'Please enter your name';
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
        $last_name_err = "Only letters and white space";
    }
    if (empty($last_name)) {
        $last_name_err = 'Please enter your last name';
    }

    if (empty($first_name)) {
        $first_name_err = 'Please enter your first name';
    }

    if (strlen($password) < 6) {
        $password_err = 'Password is at least 6 characters long';
    }
    if (empty($password)) {
        $password_err = 'Please enter your password';
    }
    $mysqli->query('CREATE TABLE IF NOT EXISTS`Persons` (
    `PersonID` int(11) NOT NULL AUTO_INCREMENT,
    `UserName` varchar(255) NOT NULL,
    `LastName` varchar(255) NOT NULL,
    `FirstName` varchar(255) ,
    `Age` int(3) DEFAULT NULL,
    `Gender` varchar(255),
    `Hobbies` varchar(255),
    `Password` varchar(255) NOT NULL,
    `Birthday` date DEFAULT NULL,
    `BankingCard` bigint(20) DEFAULT NULL,
    `AboutMyself` VARCHAR,
    `ListChoiseCategory` varchar(255) NOT NULL,
    PRIMARY KEY (`PersonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    $hide = "hide";
    $sql = "INSERT INTO Persons (UserName, LastName, FirstName, Age, Gender, Hobbies, Password, Birthday, 
            BankingCard, AboutMyself, ListChoiseCategory) 
            VALUES ('$user_name', '$last_name', '$first_name', '$age', '$gender', '$hobbies', '$password', '$birthday',
             '$banking_card', '$about_myself', '$ListChoise_category');";
    if (mysqli_query($mysqli, $sql)) {
        echo "Records added successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($mysqli);
    }

// close connection
    mysqli_close($mysqli);
}
?>

<?php include('index.php'); ?>
<?php if (isset ($success_msg) && $success_msg):
    echo $success_msg;
endif;
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <ul>
        <li class="item">
            <label for="user-name">Name:
                <input type="text" name="user_name" required="true" id="user-name"
                       value="<?php if (isset ($user_name)) echo $user_name; ?>">
                <?php if (isset($user_name_err) && $user_name_err) :
                    echo "<p class='err'>$user_name_err</p>";
                endif;
                ?>*</label>
        </li>
        <li class="item">
            <label for="last-name">Last name:
                <input type="text" name="last_name" required="true" id="last-name"
                       value="<?php if (isset ($last_name)) echo $last_name; ?>">
                <?php if (isset ($last_name_err) && $last_name_err) :
                    echo "<p class='err'>$last_name_err</p>";
                endif;
                ?>*</label>
        </li>
        <li class="item">
            <label for="first_name">FirstName:
                <input type="text" name="first_name" required="true" id="first_name"
                       value="<?php if (isset ($first_name)) echo $first_name; ?>">
                <?php if (isset ($user_name_err) && $user_name_err) :
                    echo "<p class='err'>$first_name_err</p>";
                endif;
                ?>*</label>
        </li>
        <li class="item">
            <label for="password">Password:
                <input type="password" name="password" required="true" id="password"
                       value="<?php if (isset ($password)) echo $password; ?>">
                <?php if (isset ($password_err) && $password_err) :
                    echo "<p class='err'>$password_err</p>";
                endif;
                ?>*</label>
        </li>
        <li>
            <label for="user-age">Age:
            <input type="text" name="age" id="user-age" value="<?php if (isset ($age)) echo $age; ?>">*</label>
        </li>
        <li>
            <label><input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?>
                          value="male" checked="checked">male gender</label>
            <label><input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?>
                          value="female">female gender</label>
        </li>
        <li>
            <label for="hobbies">list of hobbies</label>
            <select name="hobbies" id="hobbies">
                <option value="">choose something</option>
                <option value="sport">danse</option>
                <option value="recreation">horseback riding</option>
                <option value="art">art</option>
                <option value="dance">painting</option>
                <option value="dance">music</option>
            </select>
        </li>
        <li>
            <label for="birthday">Birthday:
            <input type="date" name="birthday" id="birthday" value="<?php echo $birthday; ?>">*</label>
        </li>
        <li>
            <label for="banking_card">Banking Card:
            <input type="text" name="banking_card" id="banking_card"
                   value="<?php if (isset ($banking_card)) echo $banking_card; ?>">*</label>

        </li>
        <li>
            <label for="about_myself">Tell something about yourself</label>
            <textarea name="about_myself" cols="50" rows="8" id="about_myself"
                      value="<?php echo $about_myself; ?>"></textarea>
        </li>
        <li>
            <label for="ListChoise_category">list of category
                <select name="ListChoise_category" required="true" id="ListChoise_category">
                    <option value="">choose something</option>
                    <option value="sport">danse</option>
                    <option value="recreation">horseback riding</option>
                    <option value="art">art</option>
                    <option value="dance">painting</option>
                    <option value="dance">music</option>
                </select>*</label>
            <button type="submit">Submit</button>
        </li>
    </ul>
    <p class="note"><span class="note">*</span> - required field</p>
</form>
</body>


