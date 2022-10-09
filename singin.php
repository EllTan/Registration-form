<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php 
    include "classes\File.php";
    include "classes\User.php";
    include "classes\Errors.php";    

    $user = new User;
    $myerror = new Errors;
    $file = new File;   

    $file->nameFile = "files\DB.json";
    $jsonArray = [];

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (file_exists('files\DB.json')){
        $json = file_get_contents('files\DB.json');
        $jsonArray = json_decode($json, true);
      } 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["login"])) {
          $myerror->loginErr = "Введите логин";
        } 
        else {
            $user->loginUser = test_input($_POST["login"]);  
        }

        if (empty($_POST["password"])) {
            $myerror->passwordErr = "Введите пароль";
        } else {
            $user->passUser = test_input($_POST["password"]);   
        } 
        if ($jsonArray != null){
            foreach ($jsonArray as $el){
              if($el[0] == test_input($_POST["login"]) and $el[2] == test_input($_POST["password"])){
                echo "Добро пожаловать $user->loginUser";
              } 
              
            }
        }
    }
?>

<div class="modal">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <input type="text" minlength="6" name="login" placeholder="Введите логин"  value="<?php echo $user->loginUser;?>">
  <span class="error">* <?php echo $myerror->loginErr;?></span>
  <br><br>
  <input type="text" name="password" placeholder="Введите пароль" minlength="6" value="<?php echo $user->passUser;?>">
  <span class="error">* <?php echo $myerror->passwordErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Войти"> 
</body>
</html>