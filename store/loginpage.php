

<?php
	//------------------------------------------Session configuration
	$email  = $password  = '';
	$error = array('error'=>'');
	if(isset($_POST["submit"])){
        //create connection 
		$conn = new mysqli("localhost", "root", "", "storedb");
		
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$email = $_POST["email"];
		$password = $_POST["pwd"];
		//query
		$sql = "SELECT id, previlige FROM `account` WHERE email ='$email' AND password = '$password'";
		$result = $conn->query($sql);
		
			if($result->num_rows == 1 ){
				$row = $result->fetch_assoc();
				if($row['previlige'] ==="member"){
					session_start();	
					$_SESSION["user_name"] = $_POST["email"];
					$_SESSION["password"] = $_POST["pwd"];
					$_SESSION["last-update"] = time();	
					header('Location: mainpage.php');
				}else
				{
					session_start();	
					$_SESSION["user_name"] = $_POST["email"];
					$_SESSION["password"] = $_POST["pwd"];
					$_SESSION["last-update"] = time();	
					header('Location: admincategory.php');
				}
			}
			else{
				$error['error'] = "Email or Password is not correct!";
				
			}

$conn->close();
		
		
	}
	
    //------------------------------------------end Session configuration
?>
<!DOCTYPE html>
<html>
<head>
  <title>Member's Login Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/register.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

	<div id="header">
	
	</div>
	
	<div id="menubar">
	
	</div>


	<div class="container-sm border p-3 ">
	  <h2>Sign IN </h2>
	  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="needs-validation" novalidate >
				
		
			
				<div class="form-group">
				  <div class="text-danger"> <?php echo $error['error']; ?></div>
				  <label for="email">Email:</label>
				  <input type="email" class="form-control" id="email"  name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value = "<?php echo $email; ?>"required>
				  <div class="invalid-feedback" >Please fill out this field.</div>
				  
				</div>
			
		
			
			
				<div class="form-group">
				  <label for="pwd">Password:</label>
				  <input type="password" class="form-control" id="pwd"  name="pwd" value = "<?php echo $password; ?>" required>
				  <div class="invalid-feedback">Please fill out this field.</div>
				</div>
				
		
		<button type="submit" class="btn btn-primary"  name="submit">Sign IN</button>
	  </form>
	</div>

<script>
// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();



</script>


</body>
</html>
