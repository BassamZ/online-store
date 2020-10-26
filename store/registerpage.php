
<?php
    $fname = $lname = $email = $confemail = $password = $confpassword = '';
	$error = array('email'=>'','confemail'=>'','confpassword'=>'');
	if(isset($_POST["submit"])){
	$fname= $_POST["fname"];
	$lname= $_POST["lname"];	
	$email= $_POST["email"];
	$confemail= $_POST["confemail"];
	$password= $_POST["pwd"];
	$confpassword= $_POST["confpwd"];	
		  
			// database connection

			$conn = new mysqli("localhost", "root", "", "storedb");
					
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
					} 
			//insert member's information in the table account in database
			$fname= $_POST["fname"];
			$lname= $_POST["lname"];
			$email= mysqli_real_escape_string($conn,$_POST["email"]);
			$password= mysqli_real_escape_string($conn,$_POST["pwd"]);
			//check if the email is unique in the database
			$sql = "SELECT id FROM `account` WHERE email ='$email'";
			$result = $conn->query($sql);
			if($result->num_rows == 0 ){
				$sql = "INSERT INTO account (fname, lname, email, password,previlige) VALUES ('$fname', '$lname', '$email', '$password', 'member')";
				$conn->query($sql) or die ("Error description: " . $conn->error);	
				//go to login page			
				$conn->close();
				header('Location: loginpage.php');
				}
				else{
					$error['email']= "Email is already exist!";
				}
		
					
	}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Member's Register Page</title>
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
	  <h2>Sign UP </h2>
	  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="needs-validation" novalidate >
		<div class="row">
			<div class="col">
				<div class="form-group">
				  <label for="fname">First Name:</label>
				  <input type="text" class="form-control" id="fname"  name="fname" value = "<?php echo $fname ?>" required>
				  <div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
				  <label for="lname">Last name:</label>
				  <input type="text" class="form-control" id="lname"  name="lname" value = "<?php echo $lname ?>"required>
				  <div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="form-group">
					
				  <label for="email">Email:</label>
				  <input type="email" class="form-control" id="email"  name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value = "<?php echo $email ?>"required>
				  <div class="invalid-feedback" >Please fill out this field.</div>
				  <div class="text-danger"> <?php echo $error['email']; ?></div>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
				  <label for="confemail">Confirm Email:</label>
				  <input type="email" class="form-control" id="confemail"  name="confemail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value = "<?php echo $confemail ?>"  required>
				  <div class="invalid-feedback" >Please fill out this field.</div>
				  <div class="text-danger" id="emailmatch"> <?php echo $error['confemail']; ?></div>
				</div>
			</div>
		</div>
		
		<div class="row">	
			<div class="col">
				<div class="form-group">
				  <label for="pwd">Password:</label>
				  <input type="password" class="form-control" id="pwd"  name="pwd" value = "<?php echo $password ?>" required>
				  <div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>	
			<div class="col">
				<div class="form-group">
				  <label for="confpwd">Confirm Password:</label>
				  <input type="password" class="form-control" id="confpwd"  name="confpwd" value = "<?php echo $confpassword ?>" required>
				  <div class="invalid-feedback" >Please fill out this field.</div>
				  <div class="text-danger" id="passwordmatch" > <?php echo $error['confpassword']; ?></div>
				</div>	
			</div>	
		</div>		
		<div class="form-group text-muted">
		  <p>By creating an account I agree to my personal data being processed in accordance with <span>Privacy Policy</span></p>
		</div>
		<button type="submit" class="btn btn-primary"  name="submit">Save</button>
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


(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if ($('#pwd').val() !== $('#confpwd').val()) {
          event.preventDefault();
          event.stopPropagation();
		  $('#passwordmatch').html('Password does not match!').css('color', 'red');
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if ($('#email').val() !== $('#confemail').val()) {
          event.preventDefault();
          event.stopPropagation();
		  $('#emailmatch').html('Email does not match!').css('color', 'red');
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();


/*

$('#pwd, #confpwd').on('keyup', function () {
  if ($('#pwd').val() == $('#confpwd').val()) {
    $('#passwordmatch').html('Matching').css('color', 'green');
  } else 
    $('#passwordmatch').html('Not Matching').css('color', 'red');
});

*/
</script>


</body>
</html>
