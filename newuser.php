<!DOCTYPE HTML>
<html>
<head>
<title>Add User</title>
<style>
.error {color: #FF0000;}

a.button {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;
    
    display: block;
    width: 220px;
    text-align: center;
    text-decoration: none;
    color: initial;
    margin-left: 527.5px;
}

a.button:hover {
    background: #3d7a80;
    background: -webkit-linear-gradient(top, #3d7a80, #2f5f63);
    background: -moz-linear-gradient(top, #3d7a80, #2f5f63);
    background: -o-linear-gradient(top, #3d7a80, #2f5f63);
    background: -ms-linear-gradient(top, #3d7a80, #2f5f63);
    background: linear-gradient(top, #3d7a80, #2f5f63);
}


form    {
background: -webkit-gradient(linear, bottom, left 175px, from(#CCCCCC), to(#EEEEEE));
background: -moz-linear-gradient(bottom, #CCCCCC, #EEEEEE 175px);
margin:auto;
position:relative;
width:550px;
height:230px;
font-family: Tahoma, Geneva, sans-serif;
font-size: 14px;
font-style: italic;
line-height: 24px;
font-weight: bold;
color: #5d2500;
text-decoration: none;
-webkit-border-radius: 10px;
-moz-border-radius: 10px;
border-radius: 10px;
padding:10px;
border: 1px solid #999;
border: inset 1px solid #333;
-webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
-moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
}

#submit {
    background-color: #009900;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius:6px;
    color: #fff;
    font-family: 'Oswald';
    font-size: 20px;
    text-decoration: none;
    cursor: pointer;
    border:none;
}

#submit:hover {
    border: none;
    background:red;
    box-shadow: 0px 0px 1px #777;
}

</style>
</head>
<body>

<?php
// define variables and set to empty values
$emailErr = $passErr = "";
$email = $pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     $email = test_input($_POST["email"]);
     list($userhandle,$domain) = explode('@',$email);
     if($domain!="faculty.iitmandi.ac.in" and $domain!="iitmandi.ac.in" and $domain!="students.iitmandi.ac.in") 
     {
        echo "<script>alert('Only iitmandi emails are allowed.')</script>";
        $email = "";
     }
     $pass = $_POST["pass"];
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2><center>Add new customer</center></h2>
<p><center><span class="error">* required field.</span></center></p>
<form name="customer" method="post" onsubmit="return allLetter()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   
   E-mail: <input type="email" name="email" placeholder = "email (max 100 characters)" maxlength="100" required>
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   
   Password: <input type="password" name="pass" placeholder = "enter password" pattern=".{8,}" maxlength="20" required title="min 8 characters">
   <span class="error">* <?php echo $passErr;?></span>
   <br><br>
   
   <input type="submit" name="submit" value="Submit" id="submit">
</form>

<?php

if($_POST['email'] and $email!="") 
{ 

    $servername = "localhost";
    $handle = fopen("pass.txt", "r");
    $userinfo = fscanf($handle, "%s\t%s\t%s\n");
    list ($username,$password,$dbname) = $userinfo;
    fclose($handle);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
    
    /*$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?,?)");
    $stmt->bind_param("ss", $email, $pass);

    $stmt->execute();
    $stmt->close();*/
    
    $sql="INSERT INTO users (username, password) VALUES (\"$email\",MD5('$pass'))";
    $resulta = $conn->query($sql);
    if($resulta) 
    {
        echo "<script>alert(\"User created sucessfully\")</script>";
        $sqlt = "SELECT * FROM users ORDER BY userid DESC LIMIT 1";
        $result = $conn->query($sqlt);
        while($row = $result->fetch_assoc()) { echo "<br><br><center>User ID: " . $row["userid"]. "<br>username: " . $row["username"]. "<br></center>"; }
            $conn->close();
    }
    else echo "<script>alert(\"Error. Try again.\")</script>";
}
    
    else { echo"<br><center>Enter valid information.</center>"; }
?>

<br><br>
<a href = "home.php" class="button"> Sign Out </a>
<br>
<a href = "home.php" class="button"> Back to Home </a>

</body>
</html>
