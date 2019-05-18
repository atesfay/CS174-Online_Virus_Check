<?php
    require_once('authentication.php');
    require_once('sanitize_function.php');

    //Variables
    $username = "";
    $password = "";
    $username_check = "";
    $password_check = "";
    $type = "off";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = sanitizeString($_POST['username']);
        $password = sanitizeString($_POST['password']);
        if (isset($_POST['type'])) {
            $type = sanitizeString($_POST['type']);
        }
        $username_check = username_validation($username);
        $password_check = password_validation($password);
        $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
        if($type == 'on')   {
            admin($connection, $username, $password);
        } else {
            user($connection, $username, $password);
        }
        $connection->close();
    }

    function username_validation($input) {
        if ($input == "") {
            return "Please enter your username.\n";
        }
        return "";
    }

    function password_validation($input) {
        if ($input == "") {
            return "Please enter your password.\n";
        }
        return "";
    }

    function user($connection, $username, $password) {
        $sql_username =  mysql_entities_fix_string($connection, $username);
        $sql_password =  mysql_entities_fix_string($connection, $password);
        $sql = "SELECT * FROM user_data WHERE user_name = '$sql_username'";
        $result = $connection->query($sql);
        if (!$result) die($connection->error);
            elseif ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_NUM);
                $result->close();
                $salt1 = "l&hga@1"; $salt2 = "pg!@";
                $token = hash('ripemd128', "$salt1$sql_password$salt2");
                if($token == $row[2])  {
                  session_start();
                  $_SESSION['username'] = $username;
                  $_SESSION['email'] = $row[0];
                  $_SESSION['password']=$token;
                  $_SESSION['isAdmin'] = false;
                  header("location: homepage.php");
                }
                echo <<<_END
                <html>
                    <div class="alert alert-danger" role="alert">Please check your username and password</div>
                </html>
_END;
            }
            echo <<<_END
            <html>
                <div class="alert alert-danger" role="alert">Please check your username and password</div>
            </html>
_END;
    }

    function admin($connection, $username, $password){
        $sql_username =  mysql_entities_fix_string($connection, $username);
        $sql_password =  mysql_entities_fix_string($connection, $password);
        $sql = "SELECT * FROM admin_data WHERE admin_name = '$sql_username'";
        $result = $connection->query($sql);
        if (!$result) die($connection->error);
            else if ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_NUM);
                $result->close();
                $salt1 = "l&hga@1"; $salt2 = "pg!@";
                $token = hash('ripemd128', "$salt1$sql_password$salt2");
                $token = $sql_password;
                if($token == $row[2]) {
                  session_start();
                  $_SESSION['username'] = $username;
                  $_SESSION['email'] = $row[0];
                  $_SESSION['password']=$token;
                  $_SESSION['isAdmin'] = true;
                  header("location: admin.php");
                }
                echo <<<_END
                <html>
                    <div class="alert alert-danger" role="alert">Invalid username/password combination</div>
                </html>
_END;
            }
            echo <<<_END
            <html>
                <div class="alert alert-danger" role="alert">Invalid username/password combination</div>
            </html>
_END;
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Virus Scan</title>

    <!-- Latest compiled and minified CSS -->

    <link href="/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/navbar.css">
    <linl rel "canonical"  href="https://getbootstrap.com/docs/3.4/examples/navbar/">
  </head>
  <body data-gr-c-s-loaded="true">
    <div class="container">
      <nav class="navbar navbar-default">
          <div class="container-fluid">
              <div class="navbar-header">
                  <ul class="nav navbar-nav">
                      <li><img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:50px;height:50px;"></li>
                      <li><a class="navbar-brand" style="padding-left: 25px"href="#">Virus Scan</a></li>
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

      <!-- main jumbotron  -->
      <div class="jumbotron">
          <div class="row">
              <div class="col-sm-7">
                <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:376px;height:376px;">
              </div>

              <div class="col-sm-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login</h3>
                    </div>
                    <div class="panel-body ">
                        <div style="margin:0px 40px 0px 40px; padding:10px">

                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  onsubmit="return validate(this)">
                              <div class="form-group <?php echo (!empty($username_check)) ? 'has-error' : ''; ?>">

                                  <label>Username:<sup class="text-danger">*</sup></label>
                                  <!-- Username -->
                                  <div class="input-group" >
                                      <span class="input-group-addon" id="basic-addon1">
                                          <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                      </span>
                                      <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                                      <span class="help-block text-danger"><?php echo $username_check; ?></span>
                                  </div>
                              </div>
                              <div class="form-group <?php echo (!empty($password_check)) ? 'has-error' : ''; ?>">
                                  <label>Password:<sup class="text-danger">*</sup></label>
                                  <div class="input-group" >
                                      <span class="input-group-addon" id="basic-addon1">
                                          <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                      </span>
                                      <input type="password" name="password" class="form-control">
                                      <span class="help-block text-danger"><?php echo $password_check; ?></span>
                                  </div>
                              </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                    </div><!-- /.col-lg-6 -->

                                  <div class="col-lg-6" style="padding:10px 15px">
                                        <div class="input-group">
                                          <span class="input-group-addon">
                                            <input type="checkbox" name="type" aria-label="...">
                                          </span>
                                          <label type="text" class="form-control" aria-label="...">Admin</label>
                                        </div><!-- /input-group -->
                                  </div><!-- /.col-lg-6 -->
                                </div><!-- /.row -->
                                <hr>
                                <div class="row">
                                      <div class="col-lg-6">
                                        <button type="submit" name="signin" class="btn btn-primary  btn-block btn-lg">Login</button>
                                      </div><!-- /.col-lg-6 -->
                                      <div class="col-lg-6">
                                        <a class="btn btn-primary  btn-block btn-lg" href="register.php" role="button">Register</a>
                                      </div><!-- /.col-lg-6 -->


                                </div><!-- /.row -->
                                </div><!-- /.row -->
                            </form>

                      </div>
                    </div><!-- /.panel-body -->
              </div>

            </div>
        </div>
    </div>
  </div>

  <footer >
      <div class="footer-copyright m-5" >
          <div class="container-fluid text-center" style="border: 1.2px solid #eee;padding:10px;color: #777" >
           <p style="letter-spacing: 4px">/ SJSU / Spring 2019 / CS166 Final Project / Virus Scan / Copyright (C) 2019 /</p>
          </div>
      </div>
  </footer>

  </body>
</html>
