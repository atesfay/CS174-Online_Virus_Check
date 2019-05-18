<?php
    require_once('authentication.php');
    require_once('sanitize_function.php');

    //Variables
    $email = "";
    $username = "";
    $password = "";
    $confirm_password = "";
    $email_check = "";
    $username_check = "";
    $password_check = "";
    $confirm_password_check = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['email'])) {
            $email = sanitizeString($_POST['email']);
        }

        if(isset($_POST['username'])) {
            $username = sanitizeString($_POST['username']);
        }

        if(isset($_POST['password'])){
            $password = sanitizeString($_POST['password']);
        }

        if(isset($_POST['confirm_password'])) {
            $confirm_password = sanitizeString($_POST['confirm_password']);
        }

        $email_check = email_validation($email);
        $username_check = username_validation($username);
        $password_check = password_validation($password);
        $confirm_password_check = confrimPassword_validation($password,$confirm_password);

        if(empty($email_check) && empty($username_check) && empty($password_check) && empty($confirm_password_check)) {
            $salt1 = "l&hga@1"; $salt2 = "pg!@";
            $token = hash('ripemd128', "$salt1$password$salt2");
            if($email!= "" && $username!= "" && $password != "" && $confirm_password != "" ) {
                $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
                register($connection,$email,$username,$token);
                $connection->close();
            }
        }
    }

    function email_validation($input) {
        if (empty($input)) {
            return "Please check your email";
        }
        return "";
    }

    function password_validation($input) {
        if (empty($input))
            return "Please check your password<br>";
        else if (!preg_match("/[a-z]/", $input) ||
                !preg_match("/[A-Z]/", $input) ||
                !preg_match("/[0-9]/", $input))
            return "Passwords must include a-z, A-Z and 0-9<br>";
        else if (strlen($input) < 5)
            return "Password requires at least 5 characters<br>";
        else return "";
    }

    function username_validation($input) {
        if (empty($input))
            return "Please check your username<br>";
        else if (preg_match("/[^a-zA-Z0-9_-]/", $input))
            return "Only numbers, letters, dash and underscore are allowed for username<br>";
        else return "";
    }

    function confrimPassword_validation($password, $confirm_password) {
        if(empty($confirm_password))
            return 'Please check your confirm password';
        else if($password != $confirm_password)
            return 'Password does not match.';
        else return "";
    }

    function register($connection, $email, $username, $token) {
        $my_name =  mysql_entities_fix_string($connection, $email);
        $my_username =  mysql_entities_fix_string($connection, $username);
        $my_token =  mysql_entities_fix_string($connection, $token);
        $my_sql = "INSERT INTO user_data VALUES ('$my_name','$my_username', '$my_token')";
        $result = $connection->query($my_sql);
        if (!$result) die ("connect to the database failed: " . $connection->error);
        else {
            echo <<<_END
            <html>
                <div class="alert alert-success" role="alert">You are successfully registered</div>
            </html>
_END;
        }
    }
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Signup Account</title>
    <!-- Latest compiled and minified CSS -->

    <link href="/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/navbar.css">
    <linl rel "canonical"  href="https://getbootstrap.com/docs/3.4/examples/navbar/">
    <!-- <script src="js/register.js"></script> -->
  </head>
  <body data-gr-c-s-loaded="true">
    <div class="container">

      <nav class="navbar navbar-default">
          <div class="container-fluid">
              <div class="navbar-header">
                  <ul class="nav navbar-nav">
                      <li><img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:50px;height:50px;"></li>
                      <li><a class="navbar-brand" style="padding-left: 25px"href="homepage.php">Virus Scan</a></li>
                  <ul>
              </div>
              <div id="navbar" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                      <li>
                          <a href="about.php">About</a>
                      </li>
                      <li>
                          <a href="teampage.php">Team</a>
                      </li>
                  </ul>
              </div>

          </div>

      </nav>
      <!-- end of nav -->
      <div class="jumbotron">

          <div  class="row">
            <div class="col-sm-5">
                <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:376px;height:376px;">
            </div>
            <div class="col-sm-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign up New Account</h3>
                    </div>
                    <div class="panel-body ">
                        <div style="margin:5px 60px; padding:20px">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validate(this)" >
                              <div class="form-group">
                                    <input class="form-control" type="text" name="email" placeholder="Enter Email Address" value="<?php echo $email; ?>">
                                      <span class="help-block text-danger"><?php echo $email_check; ?></span>
                              </div>
                                <div class="form-group">
                                  <input class="form-control" type="text" name="username" placeholder="Enter Username" value="<?php echo $username; ?>">
                                    <span class="help-block text-danger"><?php echo $username_check; ?></span>
                              </div>
                              <div class="form-group">
                                  <input class="form-control" type="password" id="password" name="password" name="Enter Password" placeholder="Enter password" value="<?php echo $password; ?>">
                                    <span class="help-block text-danger"><?php echo $password_check; ?></span>
                              </div>
                              <div class="form-group">
                                  <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password " value="<?php echo $confirm_password; ?>">
                                      <span class="help-block text-danger"><?php echo $confirm_password_check; ?></span>
                              </div>
                              <hr>
                              <div class="form-group" style="float: right">
                                  <input type="submit" class="btn btn-primary btn-lg" value="Submit" style="margin: 0px 10px">
                              </div>
                            </form>

                        </div>
                    </div><!--/ end of panel-body -->
                </div> <!-- end of panel -->

            </div>

          </div>
        </div>

      </div> <!-- end of jumbotron -->


    <footer >
        <div class="footer-copyright m-5" >
            <div class="container-fluid text-center" style="border: 1.2px solid #eee;padding:10px;color: #777" >
             <p style="letter-spacing: 4px">/ SJSU / Spring 2019 / CS166 Final Project / Virus Scan / Copyright (C) 2019 /</p>
            </div>
        </div>
    </footer>

  </body>
</html>
