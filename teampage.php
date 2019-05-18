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
                          <li>
                              <a href="about.php">About</a>
                          </li>
                          <li class="active">
                              <a href="#">Team</a>
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
                                      <!-- <button type="button" class="btn btn-default navbar-btn">Sign Out</button> -->
                                      <!-- <a href="#">Signout</a> -->
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
                        <img src="img/Kim.jpg" alt="Image-icon" class="center-" style="center-align;width:160px;height:200px;border: 2px solid #5DADE2;border-radius: 6px; position: absolute;top: 45px;left: 85px;">
                        <p style="border: 1.2px; background-color: #eee;font-size: 18px;letter-spacing: 4px;color: #4286f4;border-radius: 4px;padding:5px;position: absolute;top: 85%;left: 24%;">Kimleng Hor</p>
                    </div> <!-- end of col-sm-4 -->

                    <div class="col-sm-4">
                        <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:300px;height:300px;">
                        <img src="img/AbeT.png" alt="Image-icon" class="center-" style="center-align;width:160px;height:200px;border: 2px solid #5DADE2;border-radius: 6px;position: absolute;top: 45px;left: 85px;">
                        <p style="border: 1.2px; background-color: #eee;font-size: 18px;letter-spacing: 4px;color: #4286f4;border-radius: 4px;padding:5px;position: absolute;top: 85%;left: 20.5%;">Abraham Tesfay</p>
                    </div> <!-- end of col-sm-4 -->

                    <div class="col-sm-4">
                        <img src="img/scanningFile.gif" alt="Image-icon" class="center-" style="center-align;width:300px;height:300px;">
                        <img src="img/Galen.png" alt="Image-icon" class="center-" style="center-align;width:160px;height:200px;border: 2px solid #5DADE2;border-radius: 6px;position: absolute;top: 45px;left: 85px;">
                        <p style="border: 1.2px; background-color: #eee;font-size: 18px;letter-spacing: 4px;color: #4286f4;border-radius: 4px;padding:5px;position: absolute;top: 85%;left: 24.5%;">Galen Rivoire</p>
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
