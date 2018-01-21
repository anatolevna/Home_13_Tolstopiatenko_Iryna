<?php
require 'data.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = mysqli_real_escape_string($mysqli, $_POST['UserName']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // not empty name
    if (empty(trim($UserName))) {
        $UserName_error = 'Please enter User Name';
        echo 'POST';
    } else {
        $UserName = trim($UserName);
    }
    // not empty passvord
    if (empty(trim($password))) {
        $password_error = 'Please enter your password';
    } else {
        $password = trim($password);
    }

    $result = $mysqli->query("SELECT * FROM `Persons` WHERE `UserName` = '$UserName' AND `password` = '$hashed_password'");
    $user = $result->fetch_array();
    if ($user) {
        $isLoggedIn = true;
    } else {
        $isLoggedIn = false;
    }
    // close conection
    $mysqli->close();
}
?>

<?php include('index.php'); ?>

<?php
if (isset($log_msg) && $log_msg) :
    echo "<p>$log_msg</p>";
endif;
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <ul>
        <li>
            <label for="user-login">Enter your UserName:</label>
            <input type="text" name="UserName" id="user-login">
            <?php if (isset($UserName_error) && $UserName_error) :
                $msg = '<p class="error">' . $UserName_error . '</p>';
                echo $msg;
            endif;
            $UserName_error = null;
            ?>
        </li>
        <li>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <?php if (isset($password_error) && $password_error) :
                echo "<p class='error'>" . $password_error . "</p>";
                $password_error = null;
            endif;
            ?>
        </li>
        <li>
            <input type="submit" value="Submit">
        </li>
    </ul>
</form>
</body>
</html>