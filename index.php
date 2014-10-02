<?php
require('inc/header.php');

if($_SESSION['logged_in'] == 1) {
    header('Location:main.php');
}

$createActive   = ' class="active"';
$createActive2  = ' active in';

//print_r($_POST);
if(!empty($_POST)) {
    if(isset($_POST['create'])) {
        $createActive   = ' class="active"';
        $createActive2  = ' active in';
        $errors = array();
        if(!empty($_POST['createUCID'])) {
            if(strlen($_POST['createUCID']) > 5) {
                $errors[] = "Please enter a UCID less than 5 characters";
            }
        } else {
            $errors[] = "Please enter a UCID";
        } if(!filter_var($_POST['createEmail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        } if(!empty($_POST['createPassword']) && !empty($_POST['createVerifyPassword'])) {
            if(strlen($_POST['createPassword']) > 5) {
                if($_POST['createPassword'] !== $_POST['createVerifyPassword']) {
                    $errors[] = "Passwords didn't match";
                }
            } else {
                $errors[] = "Please enter a password longer than five characters";
            }
        } else {
            $errors[] = "Please enter a password and verification";
        }
        if(empty($errors)) {
            try {
                $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $_POST['createPassword']    = md5($_POST['createPassword']."dX4.%xc@c>;");
                $_POST['createUCID']        = strip_tags(trim($_POST['createUCID']));
                $_POST['createEmail']       = strip_tags(trim($_POST['createEmail']));
                $stmt = $db->prepare("INSERT INTO student(`ucid`,`email`,`password`) VALUES(?,?,?)");
                $stmt->execute(array($_POST['createUCID'], $_POST['createEmail'], $_POST['createPassword']));
                $createOutput = "<span class=\"success\">Thanks for registering! You may now login using your UCID and password</span>";
            } catch(Exception $e) {
                $createOutput = "<span class=\"error\">Something went wrong. We're working on it, sit tight.</span>";
            }
        } else {
            $createOutput = "<ul class=\"errorList\">";
            foreach($errors as $error) {
                $createOutput .= "<li><span class=\"error\">".$error."</span></li>";
            }
            $createOutput .= "</ul>";
        }
    } else {
        $loginActive   = ' class="active"';
        $loginActive2  = ' active in';
        $createActive   = '';
        $createActive2  = ' in';
        if(!empty($_POST['loginUCID']) && !empty($_POST['loginPassword'])) {
            try {
                $_POST['loginPassword'] = md5($_POST['loginPassword']."dX4.%xc@c>;");
                $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $stmt = $db->prepare("SELECT COUNT(*) FROM student WHERE ucid=? AND password=?");
                $stmt->execute(array($_POST['loginUCID'], $_POST['loginPassword']));
                $count = $stmt->fetchAll();
                if($count[0][0] == 1) {
                    $stmt = $db->prepare("SELECT id FROM student WHERE ucid=? LIMIT 1");
                    $stmt->execute(array($_POST['loginUCID']));
                    $row = $stmt->fetch();
                    $_SESSION['logged_in'] = TRUE;
                    $_SESSION['ucid']      = $_POST['loginUCID'];     //= $row['ucid'];
                    $_SESSION['userid']    = $row['id'];
                    $db=null;
                    header('Location:main.php');
                    //print_r($row);
                    exit;
                } else {
                    $loginOutput = "<span class=\"error\">Incorrect username/password combination</span>";
                }
            } catch(Exception $e) {
                $createOutput = "<span class=\"error\">Something went wrong. We're working on it, sit tight.</span>";
            }
        } else {
            $loginOutput = "<span class=\"error\">Please enter a UCID and password</span>";
        }
    }
}
$db=null;
?>

    <div class="container">

        <div class="starter-template s40 center-block">
            <h1 class="welcome">Welcome to NJIT Schedule Maker</h1>
            <h3>Create an account or log in below</h3>
            <ul class="nav nav-pills" role="tablist" id="myTab">
                <li<?php echo $createActive; ?>><a href="#create" role="tab" data-toggle="tab">Create Account</a></li>
                <li<?php echo $loginActive; ?>><a href="#login" role="tab" data-toggle="tab">Login</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade<?php echo $createActive2; ?>" id="create">
                    <?php echo $createOutput; ?>
                    <form id="tab" method="post" class="form-horizontal signup-form" action="index.php">
                        <div class="form-group">
                            <input type="text" name="createUCID" placeholder="UCID" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="text" name="createEmail" placeholder="E-mail" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="password" name="createPassword" placeholder="Password" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="password" name="createVerifyPassword" placeholder="Verify Password" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default btn-lg btn-block" value="Create Account" name="create" /><br />
                            <span class="pull-left"><a href="#">Need help?</a></span>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade<?php echo $loginActive2; ?>" id="login">
                    <?php echo $loginOutput; ?>
                    <form id="tab" method="post" class="form-horizontal login-form" action="index.php">
                        <div class="form-group">
                            <input type="text" name="loginUCID" placeholder="UCID" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="password" name="loginPassword" placeholder="Password" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default btn-lg btn-block" value="Login" name="login" /><br />
                            <span class="pull-left"><a href="#">Forgot password?</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
<script>
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
</script>
<?php require('inc/footer.php'); ?>