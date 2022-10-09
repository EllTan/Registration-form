<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP task</title>
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

//? Переменные для записи в файл...
    $file->nameFile = "files\DB.json";
    $jsonArray = [];
    $recLog = 0;
    $recEmail = 0;   
    $recPass = 0;
    $recConfPass = 0;
    $recName = 0;
    $regOK = 0;

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
//! Получаем данные из файла...
//!_______________________________________________

//? Если файл существует - получаем его содержимое
if (file_exists('files\DB.json')){
  $json = file_get_contents('files\DB.json');
  $jsonArray = json_decode($json, true);
} 
/* print_r($jsonArray);
if ($jsonArray != null){
  foreach ($jsonArray as $el){
    echo $el[0] . "<br>";
  }
} */


//!Валидация
//!_____________________________________________

//!?Валидация логина...
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["login"])) {
      $myerror->loginErr = "Введите логин";
    } 
    else {
        $user->loginUser = test_input($_POST["login"]);  
      // проверяем, содержит ли имя только буквы и пробелы
      if (!preg_match("/^[a-zA-Z-' ]*$/",$user->loginUser)) {
        $myerror->loginErr = "Логин должен содержать только буквы и пробелы";
      }
      elseif($jsonArray != null){
        foreach ($jsonArray as $el){
          if($el[0] == test_input($_POST["login"])){
            $myerror->loginErr = "Данное имя занято"; 
          }
          else {     
            $recLog = 1;
          }
        }
      }
     
    }

//? Валидация е-мэил...
      if (empty($_POST["email"])) {
        $myerror->emailErr = "Введите Email";
      } else {
        $user->emailUser = test_input($_POST["email"]);        
        // проверьте, правильно ли сформирован адрес электронной почты
        if (!filter_var($user->emailUser, FILTER_VALIDATE_EMAIL)) {
            $myerror->emailErr = "Неверный формат электронной почты";            
        } else {
            $recEmail = 1;
        }
      } 
//? Валидация пароля...
    if (empty($_POST["password"])) {
        $myerror->passwordErr = "Введите пароль";
    } else {
        $user->passUser = test_input($_POST["password"]);

        if(!preg_match("/^\S*(?=\S{6,25})(?=\S*[a-z])(?=\S*[\d])\S*$/", $user->passUser)){
            $myerror->passwordErr = "Пароль должен содержать буквы и цифры";
        } else {
            $recPass = 1;
        }
    }   
//? Повтор пароля...    
    if(empty($_POST["confirm_password"])) {
      $myerror->confirmPasswordErr = "Введите пароль";
    } else {
      $user->confirmPassword = test_input($_POST["confirm_password"]);
      if ($user->confirmPassword != $_POST["password"]){
        $myerror->confirmPasswordErr = "Пароли не совпадают";
    } else {
      $recConfPass = 1;
    }
  }   
//? Валидация имени...  
    if (empty($_POST["name"])) {
      $myerror->nameErr = "Введите имя";
    } else {
        $user->nameUser = test_input($_POST["name"]);
      } 
      if (!preg_match("/^[a-zA-Z]*$/",$user->nameUser)){
        $myerror->nameErr = "Имя должно содержать только буквы";
      } else {
        $recName = 1;
      }

//! Запись в базу данных...
//!_________________________________________________

//? Записываем данные в файл
      if ($recLog == 1 and $recEmail == 1 and $recPass == 1 and $recConfPass == 1 and $recName == 1){
        $jsonArray[] = [$user->loginUser, $user->emailUser, $user->passUser, $user->nameUser
      ];
         file_put_contents('files\DB.json', json_encode($jsonArray, JSON_FORCE_OBJECT));
         $regOK = 1;
      }
}
?>

<div class="modal">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <input type="text" minlength="6" name="login" placeholder="Введите логин"  value="<?php echo $user->loginUser;?>">
  <span class="error">* <?php echo $myerror->loginErr;?></span>
  <br><br> 
  <input type="text" name="email" placeholder="Введите email"  value="<?php echo $user->emailUser;?>">
  <span class="error">* <?php echo $myerror->emailErr;?></span>
  <br><br>
  <input type="text" name="password" placeholder="Введите пароль" minlength="6" value="<?php echo $user->passUser;?>">
  <span class="error">* <?php echo $myerror->passwordErr;?></span>
  <br><br>
  <input type="text" name="confirm_password" placeholder="Повторите пароль" value="<?php echo $user->confirmPassword;?>">
  <span class="error">* <?php echo $myerror->confirmPasswordErr;?></span>
  <br><br>
  <input type="text" name="name" placeholder="Введите имя" minlength="2" value="<?php echo $user->nameUser;?>">
  <span class="error">* <?php echo $myerror->nameErr;?></span>
  <br><br>
        
  <input type="submit" name="submit" value="Отправить"> 
    </form>
</div>
<?php 
  if ($recLog == 1 and $recEmail == 1 and $recPass == 1 and $recConfPass == 1 and $recName == 1){
    echo "<h2>Вы успешно зарегистрировались $user->loginUser</h2>";
  }  
?>

</body>
</html>