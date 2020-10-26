<?php
//--------------------------------------------------------Session Configuration-----------------------------------------------------------	
	
	session_start();
	
	if((isset($_SESSION["user_name"])) && (isset($_SESSION["password"]))){
       	
		if(time()-$_SESSION["last-update"] >1200){	 //session time
			session_unset(); 
			session_destroy(); 	
			header('Location: loginpage.php');
		}
		else{
			$_SESSION["last-update"]=time();
		}
	}
	else{
		header('Location: loginpage.php');
	}

//--------------------------------------------- Get category name in the dropdownbox--------------------------------	
	
	$final = $error = $name = $category ='';
	$small = $medium = $large = $xlarge = $price = 0;
	$url="image/unkown.jpg";
	// database connection
	$conn = new mysqli("localhost", "root", "", "storedb");
					
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "SELECT * FROM `category`";
	$result = $conn->query($sql);
	
	
//-------------------------------------------------Search configuration------------------------------------------------	
	    if(isset($_POST["search"])){
		$name= mysqli_real_escape_string($conn,$_POST["mname"]);
		$category =$_POST["category"];
		$sql = "SELECT * FROM `model` WHERE `modelname` = '$name' AND `category-id` = '$category'";
		$check = $conn->query($sql);
		if($check->num_rows == 1 ){
			 while($row = $check->fetch_assoc()) {
					$small = $row['smallnumber'];
					$medium =$row['mediumnumber'];
					$large =$row['largenumber'];
					$xlarge =$row['xlargenumber'];
					$price =$row['price'];
					$url = $row['imageurl'];
					$category =$row['category-id'];
				}
			}else{
				$error = "The model is not exist!";
			}
  }
	
//-------------------------------------------------Add configuration------------------------------------------------			
	
	if(isset($_POST["add"])){
	    $name= mysqli_real_escape_string($conn,$_POST["mname"]);
		$small = $_POST["smallsize"];
		$medium =$_POST["mediumsize"];
		$large =$_POST["largesize"];
		$xlarge =$_POST["xlargesize"];
		$price =$_POST["price"];
		$url = mysqli_real_escape_string($conn,$_POST["imageurl"]);
		$category =$_POST["category"];
		$sql = "SELECT id FROM `model` WHERE modelname ='$name'";
		$check = $conn->query($sql);
		if($check->num_rows == 0 ){
			$sql = "INSERT INTO `model` (`modelname`, `smallnumber`, `mediumnumber`, `largenumber`, `xlargenumber`, `price`, `imageurl`, `category-id`) 
					VALUES ('$name', '$small', '$medium', '$large', '$xlarge', '$price', '$url', '$category')";
			if($conn->query($sql)=== TRUE){
				$final = "The model is added succefully!";
			    $name = $category ='';
				$url="image/unkown.jpg";
				$small = $medium = $large = $xlarge = $price = 0;
			}else{	
					die ("Error description: " . $conn->error);
				}			
		}
		else{
				$error = "The model is already exist!";
			}  
	}		
	//-------------------------------------------------Delete configuration------------------------------------------------	
	if(isset($_POST["delete"])){
		$name=mysqli_real_escape_string($conn,$_POST["mname"]);
		$category =$_POST["category"];
		$sql = "SELECT * FROM `model` WHERE `modelname` = '$name' AND `category-id` = '$category'";
		$check = $conn->query($sql);
		if($check->num_rows == 1 ){
			$sql = "DELETE FROM `model` WHERE `modelname` = '$name' AND `category-id` = '$category'";
			if($conn->query($sql)=== TRUE){
				$final = "The model is deleted succefully!";
			    $name = $category ='';
				$url="image/unkown.jpg";
				$small = $medium = $large = $xlarge = $price = 0;
			}else{	
					die ("Error description: " . $conn->error);
				}	 
			}else{
				$error = "The model is not exist!";
			}
  }
  //-------------------------------------------------update configuration------------------------------------------------	
  if(isset($_POST["update"])){
		$name= mysqli_real_escape_string($conn,$_POST["mname"]);
		$small = $_POST["smallsize"];
		$medium =$_POST["mediumsize"];
		$large =$_POST["largesize"];
		$xlarge =$_POST["xlargesize"];
		$price =$_POST["price"];
		$url = mysqli_real_escape_string($conn,$_POST["imageurl"]);
		$category =$_POST["category"];
		$sql = "SELECT * FROM `model` WHERE `modelname` = '$name' AND `category-id` = '$category'";
		$check = $conn->query($sql);
		if($check->num_rows == 1 ){
			$sql = "UPDATE `model` SET `smallnumber`='$small',`mediumnumber`='$medium',`largenumber`='$large',`xlargenumber`='$xlarge',`price`='$price',
					`imageurl`='$url' WHERE `modelname` = '$name' AND `category-id`='$category'";
			if($conn->query($sql)=== TRUE){
				$final = "The model is updated succefully!";
			    $name = $category ='';
				$url="image/unkown.jpg";
				$small = $medium = $large = $xlarge = $price = 0;
			}else{	
					die ("Error description: " . $conn->error);
				}	 
			}else{
				$error = "The model is not exist!";
			}
  }
	//-------------------------------------------------logout configuration------------------------------------------------	
	if(isset($_POST["logout"])){
        session_unset(); 
	    session_destroy(); 	
		header('Location: mainpage.php');
	}
	//-------------------------------------------------Mainpage configuration------------------------------------------------	
	if(isset($_POST["mainpage"])){	
		header('Location: mainpage.php');
	}
	$conn->close();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Admin Access</title>
</head>
<body >
    
       
		
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-sm ">
  <span class="navbar-brand">Admin Page</span>
  
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      
      <li class="nav-item">
        <a class="nav-link" href="admincategory.php">Category</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="adminmodel.php">Model</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="adminuser.php">Manage User</a>
      </li>
		  
    </ul>
	
  </div>
<ul class="nav navbar-nav navbar-right">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" >
      <button type="submit" class="btn btn-default" name="mainpage" >Mainpage</button>
	</form>  
    </ul>

<ul class="nav navbar-nav navbar-right">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" >
      <button type="submit" class="btn btn-default" name="logout" >Logout</button>
	</form>  
    </ul>
  </div>
</nav>


        
			<div class="container-sm border p-3 mt-3 ">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="needs-validation" novalidate>
					
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="category">Category:</label>
								<select name="category" id="category" class="form-control" required>
									<option value="">Select Category</option>
									<?php
										while($data =mysqli_fetch_assoc($result)){
												echo "<option value=" . $data['category-id']. ">" . $data['category-name'] . "</option>";
										}
									?>
								</select>
							<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="mname">Model Name:</label>
								<input type="text" class="form-control" id="mname"  name="mname" value = "<?php echo $name;?>" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>	
					</div>
					
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="smallsize">Small Size:</label>
								<input type="text"  class="form-control" id="smallsize"  name="smallsize" value = "<?php echo $small;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>					
						<div class="col">
							<div class="form-group">
								<label for="mediumsize">Medium Size:</label>
								<input type="text" class="form-control" id="mediumsize"  name="mediumsize" value = "<?php echo $medium;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>
					</div>
					<div class="row">	
						<div class="col">
							<div class="form-group">
								<label for="largesize">Large Size:</label>
								<input type="text"  class="form-control" id="largesize"  name="largesize" value = "<?php echo $large;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="xlargesize">XLarge Size:</label>
								<input type="text" class="form-control" id="xlargesize"  name="xlargesize" value = "<?php echo $xlarge;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>
					</div>
				<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="price">Price:</label>
								<input type="text"  class="form-control" id="price"  name="price" value = "<?php echo $price;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						</div>		
						<div class="col">
							<label for="imageurl">Image Url:</label>
								<input type="text"  class="form-control" id="imageurl"  name="imageurl" value = "<?php echo $url;?>" required>
								<div class="invalid-feedback">Please fill out this field.</div>
						</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="add">ADD Model</button>
						
						</div>
					</div>
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="search">Search Model</button>
						
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="delete">Delete Model</button>
						
						</div>
					</div>
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="update">Update Model</button>
						
						</div>
					</div>
				</div>
				
				<div class="text-center text-success">
				   <?php echo $final; ?>
				</div>
				<div class="text-center text-danger">
				   <?php echo $error; ?>
				</div>
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
