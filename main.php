<?php
require('inc/header.php');
require('inc/auth.php');
?>
    <div class="container">

        <div class="starter-template">
            <?php
                $output = '';
                $db = new PDO('mysql:host='.$database['host'].';dbname='.$database['name'].';charset=utf8', $database['user'], $database['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $stmt = $db->prepare("SELECT COUNT(*) FROM class WHERE studentID=?");
                $stmt->execute(array($_SESSION['userid']));
                $count = $stmt->fetch();
                if($count[0][0] != 0) {
                    $output .= '<a class="btn btn-lg btn-primary pull-right" href="class.php">Add class</a><br /><br />
                    <table class="table table-bordered main-table tablesorter" id="mainTable">
                        <thead><tr><th>Name</th><th>Location</th><th>Day</th><th>Start</th><th>End</th></tr></thead><tbody>';
                    $stmt = $db->prepare("SELECT * FROM class WHERE studentID=?");
                    $stmt->execute(array($_SESSION['userid']));
                    $data = $stmt->fetchAll();
                    foreach($data as $row) {
                        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
                        $row['day'] = $days[($row['day']-1)];
                        $startTime  = date('g:i A', $row['start']);
                        $endTime    = date('g:i A', $row['end']);
                        $output .= '<tr>
                            <td style="display:none;"><a href="class.php?id='.$row['id'].'">'.$row['name'].'</a></td>
                            <td>'.$row['name'].'</td>
                            <td>'.$row['location'].'</td>
                            <td>'.$row['day'].'</td>
                            <td>'.$startTime.'</td>
                            <td>'.$endTime.'</td>
                        </tr>';
                    }
                    $output .= '</tbody></table>';
                } else {
                    $output .= '<h1>Welcome</h1><br /><a href="class.php" class="btn btn-lg btn-primary">Add a class</a>';
                }
                echo $output;
            ?>
        </div>
    </div>
    <script>
        $(document).ready(function() 
            { 
                $("#mainTable").tablesorter({sortList: [[0,0]]});
            } 
        );
        $('tr').click( function() {
            window.location = $(this).find('a').attr('href');
        }).hover( function() {
            $(this).toggleClass('hover');
        });
    </script>
<?php require('inc/footer.php'); ?>