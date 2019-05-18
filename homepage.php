<?php

    require_once('authentication.php');
    require_once('sanitize_function.php');

    //Variables
    $filename = "";
    $file_check = "";

    session_start();

    if(isset($_SESSION['username']) || !empty($_SESSION['username']) && isset($_SESSION['email']) || !empty($_SESSION['email'])) {
        $username = $_SESSION['username'];
        $password = $_SESSION['password'] ;
        $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
        session_validation($connection,$username,$password);
        $connection->close();
    } else {
        header("location: login.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['filename'])){
            $filename = sanitizeString($_POST['filename']);
        }

        $file_check = file_validation($filename);

        if(empty($file_check)) {
            textFile_validation($filename);
            readFromFile($filename);
        }
    }

    function session_validation($connection,$username,$password){
        $name =  mysql_entities_fix_string($connection, $username);
        $sql = "SELECT * FROM user_data WHERE user_name = '$name'";
        $result = $connection->query($sql);
        if (!$result) die($connection->error);
        elseif ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            if($password != $row[2])  {
                die("Error occured during authentication<a href=login.php>Login</a>");
            }
        }
    }

    function file_validation($input){
        if (empty($input)) {
            return "Please choose a file";
        }
        return "";
    }

    function textFile_validation($file) {
        if($file == null) {
            exit("Please choose a text file");
        }
        $position = strpos($file , '.');
        if(substr($file, $position + 1) != "txt" ) {
            exit("Only text file is allowed");
        }
    }

    function readFromFile($file){
        $virus = false;
        if(!file_exists($file)) {
            exit("File does not exist");
        }
        $file_output = fopen($file, "r") or die("Cannot open the file");
        $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
        while(!feof($file_output)) {
            $text = fread($file_output, 20);
            $mysql = "SELECT * FROM malware_data WHERE File_Contents LIKE '$text'";
            $result = $connection->query($mysql);
            if($result->num_rows) {
                $virus=true;
                $row =  $result->fetch_array(MYSQLI_NUM);
                echo <<<_END
                <html>
                    <div class="alert alert-danger" role="alert">Virus Detected : $row[1]</div>
                </html>
_END;
            }
        }
        if(!$virus) {
        echo <<<_END
        <html>
            <div class="alert alert-success" role="alert">There is no virus detected</div>
        </html>
_END;
        fclose($file_output);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
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
                          <li class="active">
                              <a href="#">Home</a>
                          </li>
                          <li>
                              <a href="about.php">About</a>
                          </li>
                          <li>
                              <a href="teampage.php">Team</a>
                          </li>
                      </ul>
                      <ul class="nav navbar-nav navbar-right">
                          <li class="active">
                              <a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
                          </li>
                          <li>
                              <a href="#"><?php echo $_SESSION['username']; ?></a>
                          </li>
                          <li>
                            <div style="padding: 7px 0px">
                              <a class="btn btn-danger" href="logout.php">Sign out</a>
                            </div>
                          </li>
                      </ul>
                  </div>

              </div>

          </nav> <!-- end of nav -->

          <!-- main jumbotron  -->
          <div class="jumbotron">
              <div class="row">
                  <div class="col-sm-5">
                    <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:300px;height:300px;">
                  </div> <!-- end of col-sm-5 -->

                  <div class="col-sm-7">
                      <div class="panel panel-default">
                          <div class="panel-body ">
                              <div class="col-xs-12">
                                    <h3 class="text-info">Upload a File for Virus Scan</h3>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
                                      <div class="form-group">
                                          <div style="margin:20px; padding:10px">
                                              <div class="row" style="border: 1.2px solid #eee;border-radius: 5px;padding:10px" >
                                                  <div class="column1" style="float: left;">
                                                      <img src="img/scanningFile.gif" alt="Image-icon" class="left-" style="left-align;width:40px;height:40px;">
                                                  </div>
                                                  <div class="column2" style="margin-left: 60px; padding:10px 0px 0px 0px">
                                                      <input type="file" class="form-control-file" name="filename"  />
                                                  </div>

                                              </div>

                                              <small class="form-text text-muted" >Text File Only</small>
                                              <div class="text-danger"> <?php echo $file_check;?> </div>
                                          </div>
                                              <hr>
                                              <div style="float: right; padding: 7px 20px">
                                                  <button type="submit" class="btn btn-primary btn-lg">Scan File</button>
                                              </div>
                                      </div>

                                    </form>
                              </div> <!-- end of col-sm-12 -->

                          </div>
                      </div>

                  </div> <!-- end of col-sm-7 -->
      </div> <!-- end of row -->
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
