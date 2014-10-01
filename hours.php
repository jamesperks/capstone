<?php
require('inc/header.php');
require('inc/auth.php');
$output='';
if(isset($_GET['id'])) {
    $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $db->prepare("SELECT COUNT(*) FROM class WHERE studentID=? AND id=? LIMIT 1");
    $stmt->execute(array($_SESSION['userid'], $_GET['id']));
    $count = $stmt->fetch();
    if($count[0][0] != 1) {
        header('Location:main.php');
        exit;
    }
    $count=0;
    if(!isset($_POST['submit'])) {
        $stmt = $db->prepare("SELECT * FROM class WHERE studentID=? AND id=? LIMIT 1");
        $stmt->execute(array($_SESSION['userid'], $_GET['id']));
        $class = $stmt->fetch();
        $stmt = $db->prepare("SELECT * FROM hours WHERE classID=? LIMIT 1");
        $stmt->execute(array($_GET['id']));
        $hours = $stmt->fetch();
    } else {
        $errors = array();
        if($_POST['study'] > 8 || $_POST['study'] < 1) {
            $errors[] = "Please enter a valid study time";
        } if($_POST['homework'] > 8 || $_POST['homework'] < 1) {
            $errors[] = "Please enter a valid homework time";
        }
        if(!empty($errors)) {
            $output .= '<ul class="errorList center">';
            foreach($errors as $error) {
                $output .= "<li><span class=\"error\">".$error."</span></li>";
            }
            $output .= "</ul>";
        } else {
            try {
                $stmt = $db->prepare("SELECT COUNT(*) FROM hours WHERE classID=? LIMIT 1");
                $stmt->execute(array($_GET['id']));
                $count = $stmt->fetch();
                if($count[0][0] != 1) {
                    $stmt = $db->prepare("INSERT INTO hours(`classID`,`studyHours`,`homeworkHours`) VALUES(?,?,?)");
                    $stmt->execute(array($_GET['id'], $_POST['study'], $_POST['homework']));
                } else {
                    $stmt = $db->prepare("UPDATE hours SET studyHours=?, homeworkHours=? WHERE classID=?");
                    $stmt->execute(array($_POST['study'], $_POST['homework'], $_GET['id']));
                }
                $output .= '<span class="success center-block" style="text-align:center;">Success!</span>';
            } catch (Exception $e) {
                $output .= $e->getMessage();
            }
        }
        $hours["studyHours"] = $_POST["study"];
        $hours["homeworkHours"] = $_POST["homework"];
    }
} else {
    header('Location:main.php');
    exit;
}

$output .= '<script>
    $(document).ready( function(){
        $("#radios").radiosToSlider();
        $("#radios2").radiosToSlider();
    });
</script>';
$output .= '<div class="s60 center-block">
    <h1 style="text-align:center;">'.$class['name'].'</h1>
</div>
<div class="s60 center-block">
    <h3 style="text-align:center;">How many hours do you need to study each week?</h3>
    <form action="hours.php?id='.$_GET['id'].'" method="post">
    <div id="radios" class="center-block" >
        <input id="option1" name="study" type="radio" value="1"'.(($hours['studyHours'] == 1) ? ' checked':NULL).'>
        <label for="option1">1 <br>hour</label>
     
        <input id="option2" name="study" type="radio" value="2"'.(($hours['studyHours'] == 2) ? ' checked':NULL).'>
        <label for="option2">2 hours</label>
     
        <input id="option3" name="study" type="radio" value="3"'.(($hours['studyHours'] == 3) ? ' checked':NULL).'>
        <label for="option3">3 hours</label>
     
        <input id="option4" name="study" type="radio" value="4"'.(($hours['studyHours'] == 4 || !isset($hours['studyHours'])) ? ' checked':NULL).'>
        <label for="option4">4 hours</label>
     
        <input id="option5" name="study" type="radio" value="5"'.(($hours['studyHours'] == 5) ? ' checked':NULL).'>
        <label for="option5">5 hours</label>
        
        <input id="option6" name="study" type="radio" value="6"'.(($hours['studyHours'] == 6) ? ' checked':NULL).'>
        <label for="option6">6 hours</label>
        
        <input id="option7" name="study" type="radio" value="7"'.(($hours['studyHours'] == 7) ? ' checked':NULL).'>
        <label for="option7">7 hours</label>
        
        <input id="option8" name="study" type="radio" value="8"'.(($hours['studyHours'] == 8) ? ' checked':NULL).'>
        <label for="option8">8 hours</label>
    </div>
</div>

<div class="s60 center-block"><h3 style="text-align:center;">How many hours do you need to do homework each week?</h3>
    <div id="radios2" class="center-block" >
        <input id="option11" name="homework" type="radio" value="1"'.(($hours['homeworkHours'] == 1) ? ' checked':NULL).'>
        <label for="option11">1 <br>hour</label>
     
        <input id="option22" name="homework" type="radio" value="2"'.(($hours['homeworkHours'] == 2) ? ' checked':NULL).'>
        <label for="option22">2 hours</label>
     
        <input id="option33" name="homework" type="radio" value="3"'.(($hours['homeworkHours'] == 3) ? ' checked':NULL).'>
        <label for="option33">3 hours</label>
     
        <input id="option44" name="homework" type="radio" value="4"'.(($hours['homeworkHours'] == 4 || !isset($hours['homeworkHours'])) ? ' checked':NULL).'>
        <label for="option44">4 hours</label>
     
        <input id="option55" name="homework" type="radio" value="5"'.(($hours['homeworkHours'] == 5) ? ' checked':NULL).'>
        <label for="option55">5 hours</label>
        
        <input id="option66" name="homework" type="radio" value="6"'.(($hours['homeworkHours'] == 6) ? ' checked':NULL).'>
        <label for="option66">6 hours</label>
        
        <input id="option77" name="homework" type="radio" value="7"'.(($hours['homeworkHours'] == 7) ? ' checked':NULL).'>
        <label for="option77">7 hours</label>
        
        <input id="option88" name="homework" type="radio" value="8"'.(($hours['homeworkHours'] == 8) ? ' checked':NULL).'>
        <label for="option88">8 hours</label>
    </div>
</div>

<div class="s20 center-block">
    <a href="main.php" class="btn btn-lg btn-danger">Go Back</a>
    <input type="submit" name="submit" class="btn btn-lg btn-primary pull-right" value="Save" />
</div>
</form>';
print_r($errors);
print_r($_POST);
?>
    <div class="container">
            <?php echo $output; ?>
        </div>
    </div>