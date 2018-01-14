<?php
require 'data.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = mysqli_real_escape_string($conn, $_POST['UserName']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
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

    // Verifying
    if (empty($UserName_error) && empty($password_error)) {

        $sql = "SELECT UserName, Password FROM Persons WHERE UserName = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the parameters of the query being prepared
            mysqli_stmt_bind_param($stmt, "s", $param_UserName);
            // set parameters
            $param_UserName = $UserName;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $UserName, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION['UserName'] = $UserName;
                            $log_msg = 'You are logged in';
                        } else {
                            $password_error = 'The password you entered was not valid';
                        }
                    }
                } else {
                    $UserName_error = 'No account found with that UserName';
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    // close conection
    mysqli_close($conn);
}
?>

<?php include('main.php'); ?>

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