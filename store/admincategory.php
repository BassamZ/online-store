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
	
	
	
	// database connection
	
	function connection(){
		// database connection
	$conn = new mysqli("localhost", "root", "", "storedb");
					
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
	}
	
//-------------------------------------------------Search configuration------------------------------------------------	
		$result=array();
		$modelnumber=0;
	    $name= $error = $final = '';
		$newname ="New name please";
	
	function search(){
		$number  =0;
		$error=  $category='';
		$final='';
		$conn = connection();
		$name= mysqli_real_escape_string($conn,$_POST["cname"]);
		$sql = "SELECT * FROM `category` WHERE `category-name`= LOWER('$name')";
		$check = $conn->query($sql);
		if($check->num_rows == 1 ){
			 if($row = $check->fetch_assoc()) {
				$category= $row['category-id'];
				$sql = "SELECT `id` FROM `model` WHERE `category-id`= '$category' ";
				$result = $conn->query($sql);
				$number = $result->num_rows;
				$final = "The category is exist!";
				}
			}else{
				$error = "The category is not exist!";
			}
			$conn->close();
			return array($number,$name,$error,$final,$category);
	}
	
    if(isset($_POST["search"])){
		$result = search();
		$modelnumber=$result[0];
		$name=$result[1];
		$error=$result[2];
		$final=$result[3];
  }
	
//-------------------------------------------------Add configuration------------------------------------------------			
function add(){
		
	    $category = 0;
		$error='';
		$final='';
		$conn = connection();
		$name= mysqli_real_escape_string($conn,$_POST["cname"]);
		$searchresult = search();
		$category = intval($searchresult[4]);
		
		if($category === 0 ){
			 
			 $sql="INSERT INTO `category` (`category-name`) VALUES (LOWER('$name'))";
			 if($conn->query($sql)=== TRUE){
				$final = "The category is added succefully!";
			}else{	
					die ("Error description: " . $conn->error);
				}			
			}else{
				$error = "The category is exist!";
			}
			$conn->close();
	
			return array($name,$error,$final);
	}
	
	if(isset($_POST["add"])){
	    
		$result = add();
		$name=$result[0];
		$error=$result[1];
		$final=$result[2];
	}		
	//-------------------------------------------------Delete configuration------------------------------------------------	
	function deletecategory(){
	    $category = 0;
		$error='';
		$final='';
		$conn = connection();
		$name= mysqli_real_escape_string($conn,$_POST["cname"]);
		$searchresult = search();
		$category = intval($searchresult[4]);
		
		if($category !== 0 ){
			 $sql="DELETE FROM `category` WHERE `category-name` = LOWER('$name')";
			 if($conn->query($sql)=== TRUE){
				$final = "The category is deleted succefully!";
			}else{	
					die ("Error description: " . $conn->error);
				}			
			}else{
				$error = "The category is not exist!";
			}
			$conn->close();
	
			return array($error,$final);
	
  }
	
	if(isset($_POST["delete"])){
		$result = deletecategory();
		$error=$result[0];
		$final=$result[1];
  }
  //-------------------------------------------------update configuration------------------------------------------------	
  function update(){
	    $category = 0;
		$error='';
		$final='';
		$conn = connection();
		$newname= mysqli_real_escape_string($conn,$_POST["newname"]);
		$name= mysqli_real_escape_string($conn,$_POST["newname"]);
		$searchresult = search();
		$category = intval($searchresult[4]);
	if($newname !=="New name please"){	
		if($category !== 0 ) {
			 $category = $searchresult[4];
			 $sql="UPDATE `category` SET `category-name`=LOWER('$newname') where `category-id`='$category'";
			 if($conn->query($sql)=== TRUE){
				$final = "The category is updated succefully!";
			}else{	
					die ("Error description: " . $conn->error);
				}			
			}else{
				$error = "The category is not exist!";
			}
	} else{
		       $error = "Enter new category name please!";
	}
			$conn->close();
	
			return array($error,$final);
	
  }
  
  if(isset($_POST["update"])){
		$result = update();
		$error=$result[0];
		$final=$result[1];
		
  }
	//-------------------------------------------------logout configuration------------------------------------------------	
	if(isset($_POST["logout"])){
        session_unset(); 
	    session_destroy(); 	
		header('Location: mainpage.php');
	}
	
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
      <button type="submit" class="btn btn-default" name="logout" >Logout</button>
	</form>  
    </ul>

  </div>
</nav>


        
			<div class="container-sm border p-3 mt-3 ">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="needs-validation" novalidate>
					
					
					
					
						
							<div class="form-group">
								<label for="cname">Category Name:</label>
								<input type="text"  class="form-control" id="cname"  name="cname" value = "<?php echo $name;?>"  required>
								<div class="invalid-feedback">Please fill out this field.</div>
	
							</div>
												
							<div class="form-group">
								<label for="newname">New Category Name:</label>
								<input type="text"  class="form-control" id="newname"  name="newname" value = "<?php echo $newname;?>"  required>
								<div class="invalid-feedback">Please fill out this field.</div>
	
							</div>
					
						
							<div class="form-group">
								<label for="modelnum">Model Number:</label>
								<input type="text" class="form-control" id="modelnum"  name="modelnum" value = "<?php echo $modelnumber;?>" pattern="\d*" required>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>
						
					
					
				<div class="row">
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="add">ADD Category</button>
						
						</div>
					</div>
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="search">Search Category</button>
						
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="delete">Delete Category</button>
						
						</div>
					</div>
					<div class="col">
						<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block "  name="update">Update Category</button>
						
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
