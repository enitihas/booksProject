<html>
<body>
<?php
setcookie("user", "", time() - 600,"/");
header('Location:userlogin.php');
?>
</body>
</html>
