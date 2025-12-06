<?php
include('includes/functions.php');
include('includes/json_utils.php');
$table_name = "user";

$superusername = getmodsbyid('1') ?? 'superadmin';
$superpassword = getmodsbyid('2') ?? 'superadmin';
$weblink = getmodsbyid('3') ?? 'https://t.me/used4';
$brandname = getmodsbyid('4') ?? '𝑼𝒏𝒌𝒏𝒐𝒘𝒏';

$icon32x32 = getmodsbyid('8') ?? 'img/ficon/favicon-32x32.png';
$icon16x16 = getmodsbyid('9') ?? 'img/ficon/favicon-16x16.png';
$iconaplle = getmodsbyid('10') ?? 'img/ficon/apple-touch-icon.png';
$iconmain = getmodsbyid('11') ?? 'img/ficon/site.webmanifest';


$log_check = $db->select($table_name, '*', 'id = :id', '', [':id' => 1]);
$loggedinuser = !empty($log_check) ? $log_check[0]['username'] : null;

if (!empty($loggedinuser) && isset($_SESSION['name']) && $_SESSION['name'] === $loggedinuser) {
    header("Location: main.php");
    exit;
}

$data = ['id' => '1','username' => 'admin','password' => 'admin',];
$db->insertIfEmpty($table_name, $data);

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // ✅ Check superadmin login first
    if ($username === $superusername && $password === $superpassword) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $username;
        $_SESSION['passwordxyz'] = $password;
        header('Location: admin.php');
        exit;
    }

    // 🔁 Then check DB user login
    $userData = $db->select($table_name, '*', 'username = :username', '', [':username' => $username]);
    if ($userData && $password === $userData[0]['password']) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $username;
        $_SESSION['passwordxyz'] = $password;
        if ($username === 'admin') {
            header('Location: user.php');
        } else {
            header('Location: main.php');
        }
        exit;
    } else {
        // ❌ invalid login
        header('Location: ./api/index.php');
        exit;
    }
    $db->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="<?php echo $brandname; ?>">
	
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $iconaplle ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $icon32x32 ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $icon16x16 ?>">
    <link rel="manifest" href="<?php echo $iconmain ?>">
    
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/css.css">
    <title><?php echo $brandname; ?></title>
</head>
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #131419;
}

.form {
  position: relative;
  width: 350px;
  padding: 40px 40px 60px;
  background: #131419;
  border-radius: 10px;
  text-align: center;
  box-shadow: -5px -5px 10px rgba(255, 255, 255, 0.05),
    5px 5px 15px rgba(0,0,0,0.5);
}

.form h2 {
  color: #c7c7c7;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 4px;
}

.form .input {
  text-align: left;
  margin-top: 40px;
}

.form .input .inputBox {
  margin-top: 20px;
}

.form .input .inputBox label {
  display: block;
  color: #868686;
  margin-bottom: 5px;
  font-size: 18px;
}

.form .input .inputBox input {
  width: 100%;
  height: 50px;
  background: #131419;
  border: none;
  outline: none;
  border-radius: 40px;
  padding: 5px 15px;
  color: #fff;
  font-size: 18px;
  color: #03a9f4;
  box-shadow: inset -2px -2px 6px rgba(255, 255, 255, 0.1),
   inset 2px 2px 6px rgba(0,0,0,0.8);
}

.form .input .inputBox input[type="submit"] {
  margin-top: 20px;
  box-shadow: -2px -2px 6px rgba(255, 255, 255, 0.1),
   2px 2px 6px rgba(0,0,0,0.8);
}

.form .input .inputBox input[type="submit"]:active {
  color: #006c9c;
  margin-top: 20px;
  box-shadow: inset -2px -2px 6px rgba(255, 255, 255, 0.1),
  inset 2px 2px 6px rgba(0,0,0,0.8);
}

.form .input .inputBox input::placeholder {
  color: #555;
  font-size: 18px;
}

.forget {
  margin-top: 30px;
  color: #555;
}

.forget a {
  color: #ff0047;
}
 
</style>

<br><br>
<div class="form">
  <h2>Login</h2>
  <form method="post">
    <div class="input">
      <div class="inputBox">
        <label>Username</label>
        <input type="text" name="username" placeholder="Username"/>
      </div>
      <div class="inputBox">
        <label>Password</label>
        <input type="password" name="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"/>
      </div>
      <div class="inputBox">
        <input type="submit" name="login" value="LOGIN"/>
      </div>
    </div>
  </form>
  <p class="forget"><a href="<?php echo $weblink; ?>"><?php echo $brandname; ?></a></p>
</div>
<br><br>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>



