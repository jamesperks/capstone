<?php
require('inc/header.php');
require('inc/auth.php');

if(isset($_GET['id']) && !isset($_POST['submit'])) {
    $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $db->prepare('SELECT COUNT(*) FROM class WHERE id=? AND studentID=?');
    $stmt->execute(array($_GET['id'], $_SESSION['userid']));
    $count = $stmt->fetch();
    if($count[0][0] == 1) {
        $stmt = $db->prepare('SELECT * FROM class WHERE id=? AND studentID=? LIMIT 1');
        $stmt->execute(array($_GET['id'], $_SESSION['userid']));
        $row = $stmt->fetch();
        $_POST['name']          = $row['name'];
        $_POST['location']      = $row['location'];
        $_POST['day']           = $row['day'];
        $_POST['startHour']     = date('g', $row['start']);
        $_POST['startMinute']   = date('i', $row['start']);
        $_POST['startAMPM']     = ((date('A', $row['start']) == 'PM') ? 2:1);
        $_POST['endHour']       = date('g', $row['end']);
        $_POST['endMinute']     = date('i', $row['end']);
        $_POST['endAMPM']       = ((date('A', $row['end']) == 'PM') ? 2:1);
    }
}

function genTestForm ($name=NULL, $location=NULL, $day=NULL, $startHour=NULL, $startMinute=NULL, $startAMPM=NULL, $endHour=NULL, $endMinute=NULL, $endAMPM=NULL, $errors=NULL) {
    if($name===NULL && $location===NULL && $day===NULL && $startHour===NULL && $startMinute===NULL && $startAMPM===NULL && $endHour===NULL && $endMinute===NULL && $endAMPM===NULL && $errors===NULL) {
        return '<form role="form" class="addclass-form" method="post" action="class.php">
                <div class="form-group">
                  <label>Class Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter class name">
                </div>
                <div class="form-group">
                  <label>Class Location</label>
                  <input type="text" name="location" class="form-control" placeholder="Enter class location">
                </div>
                <div class="form-group">
                  <label>Class Day</label>
                  <select class="form-control day" name="day">
                    <option></option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                  </select>
                </div>
                <label>Class Start Time</label>
                <div class="form-group class-time">
                    <input type="text" name="startHour" class="form-control time" placeholder="H">
                    <span class="classTimeColon">:</span>
                    <input type="text" name="startMinute" class="form-control time" placeholder="M">
                    <select name="startAMPM" class="form-control ampm">
                        <option value="1">AM</option>
                        <option value="2">PM</option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <label style="margin-top:15px;">Class End Time</label>
                <div class="form-group class-time">
                    <input type="text" name="endHour" class="form-control time" placeholder="H"> 
                    <span class="classTimeColon">:</span> 
                    <input type="text" name="endMinute" class="form-control time" placeholder="M"> 
                    <select class="form-control ampm" name="endAMPM">
                        <option value="1">AM</option>
                        <option value="2">PM</option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="form-group" style="margin-top:20px;">
                    <input type="reset" class="btn btn-danger btn-lg" value="Clear" /> <input type="submit" name="submit" class="btn btn-primary btn-lg pull-right" value="Add Class" />
                </div>
            </form>';
    } else {
        return '<form role="form" class="addclass-form" method="post" action="class.php'.((isset($_GET['id'])) ? '?id='.$_GET['id']:NULL).'">
                <div class="form-group'.((!isset($errors['name'])) ? NULL:' has-error').'">
                  <label>Class Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter class name" value="'.$name.'">
                  '.((!isset($errors['name'])) ? NULL:'<p class="help-block error">'.$errors['name'].'</p>').'
                </div>
                <div class="form-group'.((!isset($errors['location'])) ? NULL:' has-error').'">
                  <label>Class Location</label>
                  <input type="text" name="location" class="form-control" placeholder="Enter class location" value="'.$location.'">
                  '.((!isset($errors['location'])) ? NULL:'<p class="help-block error">'.$errors['location'].'</p>').'
                </div>
                <div class="form-group'.((!isset($errors['day'])) ? NULL:' has-error').'">
                  <label>Class Day</label>
                  <select class="form-control day" name="day">
                    <option></option>
                    <option value="1"'.(($day=='1') ? ' selected':NULL).'>Monday</option>
                    <option value="2"'.(($day=='2') ? ' selected':NULL).'>Tuesday</option>
                    <option value="3"'.(($day=='3') ? ' selected':NULL).'>Wednesday</option>
                    <option value="4"'.(($day=='4') ? ' selected':NULL).'>Thursday</option>
                    <option value="5"'.(($day=='5') ? ' selected':NULL).'>Friday</option>
                    <option value="6"'.(($day=='6') ? ' selected':NULL).'>Saturday</option>
                  </select>
                  '.((!isset($errors['day'])) ? NULL:'<p class="help-block error">'.$errors['day'].'</p>').'
                </div>
                <label>Class Start Time</label>
                <div class="form-group class-time'.((!isset($errors['startTime'])) ? NULL:' has-error').'">
                    <input type="text" name="startHour" class="form-control time" placeholder="H" value="'.$startHour.'">
                    <span class="classTimeColon">:</span>
                    <input type="text" name="startMinute" class="form-control time" placeholder="M" value="'.$startMinute.'">
                    <select name="startAMPM" class="form-control ampm">
                        <option value="1"'.(($startAMPM=='1') ? ' selected':NULL).'>AM</option>
                        <option value="2"'.(($startAMPM=='2') ? ' selected':NULL).'>PM</option>
                    </select>
                    '.((!isset($errors['startTime'])) ? NULL:'<br /><p class="help-block error">'.$errors['startTime'].'</p>').'
                </div>
                <div class="clearfix"></div>
                <label style="margin-top:15px;">Class End Time</label>
                <div class="form-group class-time'.((!isset($errors['endTime'])) ? NULL:' has-error').'">
                    <input type="text" name="endHour" class="form-control time" placeholder="H" value="'.$endHour.'"> 
                    <span class="classTimeColon">:</span> 
                    <input type="text" name="endMinute" class="form-control time" placeholder="M" value="'.$endMinute.'"> 
                    <select class="form-control ampm" name="endAMPM">
                        <option value="1"'.(($endAMPM=='1') ? ' selected':NULL).'>AM</option>
                        <option value="2"'.(($endAMPM=='2') ? ' selected':NULL).'>PM</option>
                    </select>
                    '.((!isset($errors['endTime'])) ? NULL:'<p class="help-block error">'.$errors['endTime'].'</p>').'
                </div>
                <div class="clearfix"></div>
                <div class="form-group" style="margin-top:20px;">
                    '.((isset($_GET['id'])) ? '<a href="hours.php?id='.$_GET['id'].'">Update Study Hours</a><br /><br />':NULL).'
                    <input type="reset" class="btn btn-danger btn-lg" value="Clear" /> <input type="submit" name="submit" class="btn btn-primary btn-lg pull-right" value="'.((isset($_GET['id'])) ? 'Update':'Add').' Class" />
                </div>
            </form>';
    }
}
if($_POST['submit']) {
    $errors = array();
    if(empty($_POST['name'])) {
        $errors['name'] = "Please enter a class name";
    } if(empty($_POST['location'])) {
        $errors['location'] = "Please enter a location";
    } if(empty($_POST['day'])) {
        $errors['day'] = "Please select a class day";
    } else {
        if($_POST['day'] < 1 || $_POST['day'] > 6) {
            $errors['day'] = "Please select a valid day";
        }
    } if(empty($_POST['startHour']) || empty($_POST['startMinute']) || empty($_POST['startAMPM'])) {
        $errors['startTime'] = "Please enter a start time";
    } else {
        if($_POST['startHour'] > 12 || $_POST['startHour'] < 1 || $_POST['startMinute'] > 59 || $_POST['startMinute'] < 0) {
            $errors['startTime'] = "Please enter a valid start time";
        } else {
            if(empty($_POST['endHour']) || empty($_POST['endMinute']) || empty($_POST['endAMPM'])) {
                $errors['endTime'] = "Please enter an end time";
            } else {
                if($_POST['endHour'] > 12 || $_POST['endHour'] < 1 || $_POST['endMinute'] > 59 || $_POST['endMinute'] < 0) {
                    $errors['endTime'] = "Please enter a valid end time";
                } else {
                    if($_POST['startAMPM'] == 2) $_POST['startHour'] = ($_POST['startHour'] + 12);
                    if($_POST['endAMPM'] == 2) $_POST['endHour'] = ($_POST['endHour'] + 12);
                    $startTime = mktime($_POST['startHour'], $_POST['startMinute'], 0, 0, 0, 0);
                    $endTime = mktime($_POST['endHour'], $_POST['endMinute'], 0, 0, 0, 0);
                    if($startTime > $endTime) {
                        $errors['startTime'] = "Your class ends before it starts!";
                        $errors['endTime'] = "Your class ends before it starts!";
                    }
                }
            }
        }
    }
    if(!empty($errors)) {
        /*$output = '<ul class="errorList center">';
        foreach($errors as $error) {
            $output .= "<li><span class=\"error\">".$error."</span></li>";
        }
        $output .= "</ul>";*/
        $output .= genTestForm($_POST['name'],$_POST['location'],$_POST['day'],$_POST['startHour'],$_POST['startMinute'],$_POST['startAMPM'],$_POST['endHour'],$_POST['endMinute'],$_POST['endAMPM'], $errors);
    } else {
       try {
            $_POST['name']      = strip_tags(trim($_POST['name']));
            $_POST['location']  = strip_tags(trim($_POST['location']));
            $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            if(!empty($_GET['id'])) {
                $stmt = $db->prepare("UPDATE class SET day=?, start=?, end=?, name=?, location=? WHERE id=? AND studentID=?");
                $stmt->execute(array($_POST['day'], $startTime, $endTime, $_POST['name'], $_POST['location'], $_GET['id'], $_SESSION['userid']));
                $output .= '<span class="success bg-success center-block">Saved</span>';
                if($_POST['startHour'] > 12) $_POST['startHour'] = ($_POST['startHour']-12);
                if($_POST['endHour'] > 12) $_POST['endHour'] = ($_POST['endHour']-12);
                $output .= genTestForm($_POST['name'],$_POST['location'],$_POST['day'],$_POST['startHour'],$_POST['startMinute'],$_POST['startAMPM'],$_POST['endHour'],$_POST['endMinute'],$_POST['endAMPM'], $errors);
            } else {
                $stmt = $db->prepare("INSERT INTO class(`studentID`,`day`,`start`,`end`,`name`,`location`) VALUES(?,?,?,?,?,?)");
                $stmt->execute(array($_SESSION['userid'], $_POST['day'], $startTime, $endTime, $_POST['name'], $_POST['location']));
                $last_id = $db->lastInsertId();
                //$output = "<span class=\"success bg-success\">You added ".$_POST['name']." to your schedule.</span><br />
                //<a href=\"hours.php?id=".$last_id."\">Click here to add study and homework hours</a>.</span>";
                header('Location:hours.php?id='.$last_id);
            }
        } catch(Exception $e) {
            $output = "<span class=\"error bg-warning\">Something went wrong. We're working on it, sit tight.</span>";
            $output .= $e->getMessage();
        }
    }
} else {
    $output = genTestForm($_POST['name'],$_POST['location'],$_POST['day'],$_POST['startHour'],$_POST['startMinute'],$_POST['startAMPM'],$_POST['endHour'],$_POST['endMinute'],$_POST['endAMPM'], $errors);
}
//print_r($errors);
//print_r($_POST);
//print_r($_SESSION);
$db=null;
?>
    <div class="container">
        <div class="s40 center-block">
            <?php echo $output; ?>
        </div>
    </div>