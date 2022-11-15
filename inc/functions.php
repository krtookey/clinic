<?php

// Force the user to login or redirects them
function ForceLogin() {
	if (isset($_SESSION['user_id'])) {
		//User is allowed here
	}else {
		//user aint allowed here
		header("Location: /login.php"); exit;
	}
}

function ForceDashboard() {
	if(isset($_SESSION['user_id'])){
		//The user is allowed here but is redirected anyway
		header("Location: /dashboard.php"); exit;
	}else{
		//The user is not allowed here
	}
}

function FindUser($con, $email, $return_assoc = false) {
	//Make sure the user exists 
	$findUser = $con->prepare("SELECT user_id, password FROM users WHERE email = LOWER(:email) LIMIT 1");
	$findUser->bindParam(':email'. $email, PDO::PARAM_STR);
	$findUser->execute();

	if ($return_assoc) {
		return $findUser->fetch(PDO::FETCH_ASSOC);
	}

	$user_found = (boolean) $findUser->rowCount();

	return $user_found;  
}

?>