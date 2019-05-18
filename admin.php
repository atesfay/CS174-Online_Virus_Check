<?php
    require_once('authentication.php');
    require_once('sanitize_function.php');

    //Variables
    $virusName = "";
    $text_filename = "";
    $virusName_check = "";
    $textfile_check = "";

    session_start();

    if(isset($_SESSION['username']) || !empty($_SESSION['username']) && isset($_SESSION['email']) || !empty($_SESSION['email'])) {
        $username = $_SESSION['username'];
        $password = $_SESSION['password'] ;
        $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
        session_validation($connection, $username, $password);
        $connection->close();
    } else {
        header("location: login.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['text_filename'])){
            $text_filename = sanitizeString($_POST['text_filename']);
        }

        if(isset($_POST['virusName'])) {
            $virusName = sanitizeString($_POST['virusName']);
        }

        $virusName_check = name_validation($virusName);
        $textfile_check = file_validation($text_filename);

        if (empty($textfile_check) && empty($virusName_check)) {
            textFile_validation($text_filename);
            $text = readFromFile($text_filename);
            if(!empty($virusName) && !empty($text_filename)){
                $connection = createConnection($GLOBALS['hn'],$GLOBALS['db'], $GLOBALS['un'],$GLOBALS['pw']);
                saveVirus($connection, $virusName, $text);
                $connection->close();
            }
        }
    }

    function name_validation($input) {
        if (empty($input)) {
            return "Please enter the virus name";
        }
        return "";
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

    function session_validation($connection, $username, $password){
        $name =  mysql_entities_fix_string($connection, $username);
        $mysql = "SELECT * FROM admin_data WHERE admin_name = '$name'";
        $result = $connection->query($mysql);
        if (!$result) die($connection->error);
        elseif ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            if($password != $row[2])  {
                die("Error occured during authentication<a href=login.php>Login</a>");
            }
        }
    }

    function readFromFile($file) {
        if(!file_exists($file)) {
            exit("File does not exist");
        }
      	$file_output = fopen($file, "r") or die("Cannot open the file");
        $nextLine = fread($file_output , 20 );
        fclose($file_output);
      	if($nextLine == null) {
            exit("The text file is empty");
        }
        return $nextLine;
    }

    function saveVirus($connection, $virusName, $virusData) {
        $name = mysql_entities_fix_string($connection, $virusName);
        $data = mysql_entities_fix_string($connection, $virusData);
        $mysql = "INSERT INTO malware_data VALUES (NULL , '$name' , '$data')";
        $result = $connection->query($mysql);
        if (!$result) {
            die ("Database access failed: " . $connection->error);
        } else {
            echo <<<_END
            <html>
                <div class="alert alert-success" role="alert">Data is saved successfully.</div>
            </html>
_END;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
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
                          <a href="admin.php">Home</a>
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
                  <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:425px;height:425px;">
                </div> <!-- end of col-sm-5 -->

              <div class="col-sm-7">
                  <div class="panel panel-default">
                      <div class="panel-body ">
                          <div class="col-xs-12">
                              <h3 class="text-info">Upload Malware File to the Database</h3>
                              <form method="post" onsubmit="return validate(this)" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                  <div class="form-group">
                                      <div style="margin:20px; padding:10px">
                                          <div class="row" style="border: 1.2px solid #eee;border-radius: 5px;padding:10px" >
                                              <div class="column1" style="float: left;">
                                                  <img src="img/scanningFile.gif" alt="Image-icon" class="left-" style="left-align;width:40px;height:40px;">
                                              </div>
                                              <div class="column2" style="margin-left: 60px; padding:10px 0px 0px 0px">
                                                  <label for="name">Malware Name</label>
                                                  <input type="text" name="virusName" placeholder="Enter Name to be inserted" value="<?php echo $virusName;?>" class="form-control" />
                                              </div>
                                              <div class="text-danger"> <?php echo $virusName_check; ?></div>
                                          </div>
                                      </div>

                                  </div>
                                    <div class="form-group">
                                        <div style="margin:10px 20px; padding:0px 10px">
                                            <div class="row" style="border: 1.2px solid #eee;border-radius: 5px;padding:10px" >
                                                <div class="column1" style="float: left;">
                                                    <img src="img/scanningFile.gif" alt="Image-icon" class="left-" style="left-align;width:40px;height:40px;">
                                                </div>

                                                <div class="column2" style="margin-left: 60px; padding:10px 0px 0px 0px">
                                                    <label  for="file">File Upload</label>
                                                    <input class="form-control-file" type="file" name="text_filename"/>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted" >Only Text File Allowed</small><br /><br />
                                            <div class="text-danger"> <?php echo $textfile_check; ?></div>
                                        </div>
                                      <hr style="margin-top: 0px;margin-bottom: 0px;">
                                      <div style="float: right; padding: 10px 20px">
                                          <button type="submit" class="btn btn-primary btn-lg">Submit File</button>
                                      </div>

                                    </div>
                                </form>
                          </div>
                      </div>
                  </div>
              </div><!-- end of col-sm-7 -->

          </div> <!-- end of row -->

        </div> <!-- end of jumbotron-->


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
