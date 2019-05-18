<?php
    session_start();
    if(isset($_SESSION['username']) || !empty($_SESSION['username']) && isset($_SESSION['name']) || !empty($_SESSION['name'])) {
      $username = $_SESSION['username'];
      $password = $_SESSION['password'] ;
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
                          <li><a class="navbar-brand" style="padding-left: 25px"href="homepage.php">Virus Scan</a></li>
                      <ul>
                  </div>
                  <div id="navbar" class="navbar-collapse collapse">
                      <ul class="nav navbar-nav">
                          <?php if(isset($_SESSION['username'])) {
                              if($_SESSION['isAdmin'] == true) {
                        echo <<<_END
                        <html>
                          <li>
                              <a href="admin.php">Home</a>
                          </li>
                        </html>
_END;
                    } else {
                        echo <<<_END
                        <html>
                          <li>
                              <a href="homepage.php">Home</a>
                          </li>
                        </html>
_END;
                        }
                    }
                    ?>
                          <li class="active">
                              <a href="#">About</a>
                          </li>
                          <li >
                              <a href="teampage.php">Team</a>
                          </li>
                      </ul>
                      <?php if(isset($_SESSION['username'])) {
                          echo <<<_END
                          <html>
                              <ul class="nav navbar-nav navbar-right">
                                  <li class="active">
                                      <a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
                                  </li>
                                  <li>
                                    <a href="#">
_END;
                           echo $_SESSION['username'];
                           echo <<<_END
                                    </a>
                                  </li>
                                  <li>
                                      <div style="padding: 7px 0px">
                                        <a class="btn btn-danger" href="logout.php">Sign out</a>
                                      </div>
                                  </li>
                               </ul>
                           </html>
_END;
                        }
                         ?>
                  </div>

              </div>

            </nav>  <!-- end of nav -->
            <!-- main jumbotron  -->
            <div class="jumbotron">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:300px;height:300px;">
                    </div> <!-- end of col-sm-4 -->

                    <div class="col-sm-8">
                        <div class="row" style="display:inline-block;">
                            <p style="border: 1.2px; background-color: #eee;font-size: 22px;letter-spacing: 4px;color: #4286f4;border-radius: 4px;padding:5px;">About the Project</p>
                            <p style="font-size: 14px;display:inline-block;">The project is Web-based Antivirus application that allows users to upload a file to check if it contains malicious Malware.</p>
                        </div>

                        <div class="row" style="display:inline-block;">
                            <div class="col-sm-4">
                                <p style="border: 1.2px; background-color: #eee;font-size: 14px;letter-spacing: 2px;color: #4286f4;padding:2px;">Web Application Features</p>
                                <p style="font-size: 12px;display:inline-block;">Ensures a secure Session mechanism.</p>
                                <p style="font-size: 12px;display:inline-block;">Reads the file in input, per bytes. If the file is Malware, stores the sequence of 20 bytes into the database.</p>
                                <p style="font-size: 12px;display:inline-block;">Reads the file in input, per bytes, and, if it is a putative infected file, searches within the file for one of the strings stored in the database</p>


                            </div>
                            <div class="col-sm-4">
                                <p style="border: 1.2px; background-color: #eee;font-size: 14px;letter-spacing: 2px;color: #4286f4;padding:2px;">Database Features</p>
                                <p style="font-size: 12px;display:inline-block;">Save user’s information – user name, email and password.</p>
                                <p style="font-size: 12px;display:inline-block;">Stores the information regarding the infected files in input, such as name of the malware (not the name of the file) and the sequence of bytes.</p>
                                <p style="font-size: 12px;display:inline-block;">Allow user to Sign up for new Virus Scan Account.</p>

                            </div>

                            <div class="col-sm-4">
                                <p style="border: 1.2px; background-color: #eee;font-size: 14px;letter-spacing: 2px;color: #4286f4;padding:2px;">User's Account Features</p>
                                <p style="font-size: 12px;display:inline-block;">Allows the user to submit a putative infected file and shows if it is infected or not.</p>
                                <p style="font-size: 12px;display:inline-block;">Authenticate and allows an Admin to submit a Malware file and save the name of the uploaded Malware to a database.</p>
                                <p style="font-size: 12px;display:inline-block;">When an Admin adds the name of a malware during the uploading of a Malware file, it ensures that the string contains only English letters and digits. </p>

                            </div>

                        </div>
                    </div> <!-- end of col-sm-4 -->

                </div> <!-- end of roe -->

            </div><!-- end of jumbotron-->

        </div><!-- end of container -->

        <footer >
            <div class="footer-copyright m-5" >
                <div class="container-fluid text-center" style="border: 1.2px solid #eee;padding:10px;color: #777" >
                 <p style="letter-spacing: 4px">/ SJSU / Spring 2019 / CS166 Final Project / Virus Scan / Copyright (C) 2019 /</p>
                </div>
            </div>
        </footer>

    </body><!-- end of body -->
</html>
