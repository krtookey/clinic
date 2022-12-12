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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
  </head>

  <body>

  	<section class="vh-100" style="background-color: #9A616D;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                <br>
                <?php 
                include_once "dbConnection.php";
                include_once 'testinput.php';
                if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
                  $user_id = $_POST['user_id'];
                }
                $user_id = $_POST['user_id'] ?? ''; 
                if(isset($_POST['submitbutton']) && ($_POST['submitbutton'] == 'Login')){
                    $_POST['submitbutton'] = '';
                    $email = test_input($_POST['email']);
                    //echo("Email " . $email);
                    $password = test_input($_POST['password']);
                    //echo("Pass " . $password);
                    $login_query = <<<LOGINQUERY
                    SELECT user_id FROM Users WHERE email='$email' AND pwd='$password'; 
                    LOGINQUERY;
                    $login_result = $conn->query($login_query);
                    if ($login_row = $login_result->fetch_assoc()){
                        $user_id = $login_row['user_id'];
                        $_POST['user_id'] = $user_id;
                        echo("User id = " . $user_id);
                        header("Location: ./index.php?user_id=" . $user_id);
                        exit();
                    } else {
                      $_POST['email'] = '';
                      $_POST['password'] = '';
                      echo("Invalid username and password.");
                    }
                }
                ?>
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="./login.php" id="loginform" method="post">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0">Logo</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                  <div class="form-outline mb-4">
                    <input type="email" name="email" id="form2Example17" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example17">Email address</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="form2Example27" class="form-control form-control-lg" required/>
                    <label class="form-label" for="form2Example27">Password</label>
                  </div>

                  <div class="pt-1 mb-4">
                    <input class="btn btn-dark btn-lg btn-block" type="submit" name="submitbutton" value="Login"L>
                  </div>

                  <input type="text" id="user_id" name="user_id" value="" hidden>

                  <a class="small text-muted" href="#!">Forgot password?</a>
                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="register.php"
                      style="color: #393f81;">Register here</a></p>
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
