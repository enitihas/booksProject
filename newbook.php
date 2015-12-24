<!DOCTYPE HTML>

<?php
if(!isset($_COOKIE['user']))
	header('Location:userlogin.php');
?>

<html>
<head><title>Float a new book</title>
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

<script>

function allDigits()  
{   
    var x = document.forms["book"]["isbn"].value;
    var find = /^\d+$/.test(x);
    if(!find) alert("Enter ISBN with digits only !");
    return find; 
}  
          
</script>

<?php

// define variables and set to empty values
$anameErr = $bnameErr = $isbnErr = "";
$email = $pass = $isbn = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aname=test_input($_POST["aname"]);
    $bname=test_input($_POST["bname"]);
    $isbn=test_input($_POST["isbn"]);
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2><center>Float a new book</center></h2>
<p><center><span class="error">* required field.</span></center></p>
<form name="book" method="post" onsubmit="return allDigits()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

   Name of the Book: <input type="text" name="bname" placeholder = "Book-Name" required>
   <span class="error">* <?php echo $bnameErr;?></span>
   <br><br>
   
   Author: <input type="text" name="aname" placeholder = "Author-Name" required>
   <span class="error">* <?php echo $anameErr;?></span>
   <br><br>
   
   ISBN: <input type="text" name="isbn" placeholder = "13-digit ISBN" pattern=".{13,}" maxlength="13" required title="13 characters only">
   <span class="error">* <?php echo $isbnErr;?></span>
   <br><br>
   
   <input type="submit" name="submit" value="Submit" id="submit">
</form>

<?php

if($isbn and $isbn!="")
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
    
    $sql="SELECT userid FROM users WHERE username=\"$_COOKIE[user]\"";
    $resulta = $conn->query($sql);
    $row = $resulta->fetch_assoc();
    $owner = $row["userid"];    
    
    $sql="INSERT INTO books (ISBN,Name,Author,current,Owner,lastout) VALUES (\"$isbn\",\"$bname\",\"$aname\",$owner,$owner,null)";
    $resulta = $conn->query($sql);
    if($resulta) 
    {
        echo "<script>alert(\"New book made public !\")</script>";
        $sqlt = "SELECT * FROM books WHERE ISBN = '$isbn'";
        $result = $conn->query($sqlt);
        while($row = $result->fetch_assoc()) { echo "<br><br><center>Book: " . $row["Name"]. "<br>Author: " . $row["Author"]. "<br></center>"; }
    }
    else echo "<script>alert(\"Error. Try again.\")</script>";
    $conn->close();
}
    
    else { echo"<br><center>Enter valid information.</center>"; }
?> 

<br><br>
<a href = "user.php" class="button" id="b1"> Back to User Home </a>

</body>
</html>
