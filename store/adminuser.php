<?php
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

if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header('Location: loginpage.php');
}

$conn = new mysqli("localhost", "root", "", "storedb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM account WHERE previlige='member'";
$result = $conn->query($sql);

#-------------------------------------------Delete a User--------------------------------
if(isset($_POST['remove'])){
    $remove=$_POST['user_select'];
    $sql="SELECT * FROM account WHERE id='$remove';";
    $check = $conn->query($sql);
    if($check->num_rows == 1 ){
        $sql="DELETE FROM account WHERE id='$remove';";

        if($conn->query($sql)=== TRUE){
            #echo "success";
        }else{
            die("Issues with database query". $conn->error);
        }
    }
    header("Location:adminuser.php");
}
#-------------------------------------------Upgrade to Admin--------------------------------
if(isset($_POST['upgrade'])){
    $upgrade=$_POST['user_select'];
    $sql="SELECT * FROM account WHERE id='$upgrade';";
    $check = $conn->query($sql);
    if($check->num_rows == 1 ){
        $sql="UPDATE account SET previlige = 'admin' WHERE Id = '$upgrade';";
        if($conn->query($sql)=== TRUE){
            #echo "success";
        }else{
            die("Issues with database query". $conn->error);
        }
    }
    header("Location:adminuser.php");
}
#-------------------------------------------Change Password--------------------------------
if(isset($_POST['change_pw'])) {
    $change_pw=$_POST["user_select"];
    $new_pw=$_POST["password"];
    $sql="SELECT * FROM account WHERE id='$change_pw';";
    $check = $conn->query($sql);
    if($check->num_rows == 1 ){
    $sql="UPDATE account SET password ='$new_pw' WHERE Id = '$change_pw';";
        if($conn->query($sql)=== TRUE){
            #echo "success";
        }else{
            die("Issues with database query". $conn->error);
        }
    }
    header("Location:adminuser.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="style.css">

    <title>Admin Access</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-sm ">
        <span class="navbar-brand">Admin Page</span>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="admincategory.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminmodel">Model</a>
                </li>
                <li class="nav-item">
                    <b><a class="nav-link" href="adminuser.php">Manage User</a></b>
                </li>

            </ul>

        </div>

        <ul class="nav navbar-nav navbar-right">
            <form method="POST">
            <button type="submit" class="btn btn-default "  name="logout">Logout</button>
            </form>
        </ul>

    </div>
</nav>



<div class="container-sm border p-3 mt-3">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="needs-validation" novalidate>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="category">User:</label>
                    <select id="user_select" name="user_select" onclick="someFunction()" class="form-control" required>
                        <option value="">Select User</option>
                        <?php
                            while($data =mysqli_fetch_assoc($result)){
                                echo "<option value=" . (string)$data['id']. ">" . $data['email'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block removeuser"  name="remove">Remove User</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block "  name="upgrade">Upgrade to Admin</button>
                    </div>
                </div>
                <div class="col">
                    <div class=""><!--form-group-->
                        <button id="submit_pw" type="submit" class="btn btn-primary btn-lg btn-block "  name="change_pw">Change Password</button>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="pwd">New Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="pwd">New Password Again:</label>
                            <input type="password" class="form-control" id="password_again" name="repassword" placeholder="Confirm Password">
                        </div>
                    </div>
                    <script>
                        // Admin set new password validation
                        $("#submit_pw").attr("disabled",true); //disables 'change password'-button until both input fields are identical
                        $('input').blur(function() {
                            var pass = $('input[name=password]').val();
                            var repass = $('input[name=repassword]').val();
                            if(($('input[name=password]').val().length == 0) || ($('input[name=repassword]').val().length == 0)){
                                $("#submit_pw").attr("disabled",true);
                                $('#password').addClass('error');
                                $('#password_again').addClass('error');
                            }
                            else if (pass != repass) {
                                $("#submit_pw").attr("disabled",true);
                                $('#password_again').addClass("error");
                            }
                            else {
                                $('#password').removeClass('error');
                                $('#password_again').removeClass('error');
                                $("#submit_pw").attr("disabled",false);
                            }
                        });
                    </script>
                </div>
            </div>
    </form>
</div>
</body>
</html>
