<?php 

  // Allow the config
  define('__CONFIG__', true);
  // Require the config
  include "inc/config.php"; 
 

  Page::ForceDashboard();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="follow">
    <link rel="stylesheet" type="text/css" href="assets/style.css">

    <title>Page Title</title>

    <base href="/" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
  </head>

  <body style="background-color: #9A616D;">

    <section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="/inc/img/doctor.png"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                <br>
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="register.php" method="post" id="registerform">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0">Logo</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register your new account</h5>
                  <?php
                    include_once "dbConnection.php";
                    include_once 'testinput.php';
                    //echo("We are in the fun zone");
                    if(isset($_POST['submitbutton']) && ($_POST['submitbutton'] == 'Register')){
                      $_POST['submitbutton'] = '';
                      $fname = $_POST['fname'];
                      $lname = $_POST['lname'];
                      $uname = $_POST['uname'];
                      $title = $_POST['title'];
                      $phone = $_POST['phone'];
                      $email = $_POST['email'];
                      $permlevel = '3';
                      $password=$_POST['password'];
                      $query = <<<INSERTUSER
                      INSERT INTO Users (user_name, permission, job_title, phone, email, first_name, last_name, pwd) 
                      VALUES ("$uname", "$permlevel", "$title", "$phone", "$email", "$fname", "$lname", "$password");
                      INSERTUSER;
                      if($conn->query($query) === TRUE){
                          echo("User successfully created");
                          header("Location: ./login.php");
                          exit();
                      } else {
                        echo("<span style='color:red'>The data was not inserted into the database correctly. Please contact an administrator.</span>");
                        $fname = "";
                        $lname = "";
                        $uname = "";
                        $title = "";
                        $phone = "";
                        $email = "";
                        $password= "";
                      }
                    }
                  ?>
                  <div class="form-outline mb-4">
                    <input type="text" name="fname" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">First Name</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="text" name="lname" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Last Name</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="email" name="email" id="form2Example17" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example17">Email Address</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="text" name="uname" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Username</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="phone" name="phone" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Phone Number</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="text" name="title" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Job Title</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Password</label>
                  </div>
                  <div class="pt-1 mb-4">
                    <input class="btn btn-dark btn-lg btn-block" type="submit" name="submitbutton" value="Register">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <?php require_once "inc/footer.php"; ?> 
  </body>
</html>