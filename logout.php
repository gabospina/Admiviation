<?php
session_start();
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session
header("Location: admviationHome.php"); // Redirect to login page
exit();
?>

<?php
// logou_v7
	// if (session_status() == PHP_SESSION_NONE) {
	//   session_start();
	// }
	// unset($_SESSION);
	// session_destroy();
	// setcookie("HeliUser", "", time()-1000000, "/");
	// setcookie("admin", "", time()-1000000, "/");
	// setcookie("account", "", time()-1000000, "/");
	// header("Location: admviationHome.php");
?>