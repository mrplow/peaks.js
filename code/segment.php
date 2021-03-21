<?php
include ('/var/connect.php');

$dbconnect = mysqli_connect($hostname, $username, $password, $db);

if ($dbconnect->connect_error)
{
    die("Database connection failed: " . $dbconnect->connect_error);
}

if (isset($_POST['Save']))
{
    if (is_numeric($_POST['Save']))
    {
        $upd_by = 1;
        $upd_comment = htmlspecialchars($_POST['Comment']);
        $upd_start = $_POST['StartTime'];
        $upd_end = $_POST['EndTime'];
        $upd_rowid = $_POST['Save'];
        $UpdSegStm = $dbconnect->prepare('UPDATE segments SET sg_mby = ?, sg_mdate = CURDATE(), sg_comment = ?, sg_starttime = ?, sg_endtime = ? WHERE sg_rowid = ?');
        $UpdSegStm->bind_param('isddi', $upd_by, $upd_comment, $upd_start, $upd_end, $upd_rowid);
        $UpdSegStm->execute();
    }
    else
    {
        $cr_eprowid = $_POST['EpisodeRowid'];
        $cr_by = 1;
        $cr_comment = htmlspecialchars($_POST['Comment']);
        $cr_start = $_POST['StartTime'];
        $cr_end = $_POST['EndTime'];
        $CrSegStm = $dbconnect->prepare('INSERT INTO segments (sg_rowid_episode, sg_cby, sg_cdate, sg_comment, sg_starttime, sg_endtime) VALUES( ?, ?, CURDATE(), ?, ?, ? )');
        $CrSegStm->bind_param('iisdd', $cr_eprowid, $cr_by, $cr_comment, $cr_start, $cr_end);
        $CrSegStm->execute();
    }
}
if (isset($_POST['Delete']))
{
    $del_rowid = $_POST['Delete'];

    $DelSegStm = $dbconnect->prepare('DELETE segments FROM segments WHERE sg_rowid = ?');
    $DelSegStm->bind_param('i', $del_rowid);
    $DelSegStm->execute();

}

?>

