<?php include('header.php'); ?>
<?php
include_once('controller/connect.php');

$dbs = new database();
$db = $dbs->connection();
$Statusl = "Pending";
$leavedetails = mysqli_query($db, "SELECT * FROM leavedetails WHERE LeaveStatus='$Statusl'");

if (isset($_GET['id'])) {
    $acceptid = $_GET['id'];
    $accept = "Accept";
    mysqli_query($db, "UPDATE leavedetails SET LeaveStatus='$accept' WHERE Detail_Id='$acceptid'");
    echo "<script>window.location='leaverequest.php';</script>";
} else if (isset($_GET['msg'])) {
    $deniedid = $_GET['msg'];
    $denied = "Denied";
    mysqli_query($db, "UPDATE leavedetails SET LeaveStatus='$denied' WHERE Detail_Id='$deniedid'");
    echo "<script>window.location='leaverequest.php';</script>";
}

$laccept = mysqli_query($db, "SELECT l.*, e.FirstName, e.LastName, lt.Type_of_Name FROM leavedetails l JOIN employee e ON l.EmpId = e.EmployeeId JOIN type_of_leave lt ON l.TypesLeaveId = lt.LeaveId WHERE LeaveStatus='Accept'");
$ldenied = mysqli_query($db, "SELECT l.*, e.FirstName, e.LastName, lt.Type_of_Name FROM leavedetails l JOIN employee e ON l.EmpId = e.EmployeeId JOIN type_of_leave lt ON l.TypesLeaveId = lt.LeaveId WHERE LeaveStatus='Denied'");
?>
<ol class="breadcrumb" style="margin: 10px 0px !important;">
    <li class="breadcrumb-item"><a href="Home.php">Home</a><i class="fa fa-angle-right"></i>Leave<i class="fa fa-angle-right"></i>Leave</li>
</ol>
<form method="POST">
<div class="validation-form">
  <h2>Request Leave</h2>
  <div class="row" style="color: white; margin-right: 0; margin-left: 0;">
    <div style="background: #202a29;" class="col-md-1 control-label">
        <h5>ID</h5>
    </div>
    <div style="background: #202a29;" class="col-md-2 control-label">
        <h5>Name</h5>
    </div>
    <div style="background: #202a29; text-align: center;" class="col-md-1 control-label">
        <h5>Emp ID</h5>
    </div>
    <div style="background: #202a29;" class="col-md-2 control-label">
        <h5>Leave Status</h5>
    </div>
    <div style="background: #202a29;" class="col-md-1 control-label">
        <h5>Reason</h5>
    </div>
    <div style="background: #202a29; text-align: center;" class="col-md-2 control-label">
        <h5>Start Date</h5>
    </div>
    <div style="background: #202a29; text-align: center;" class="col-md-2 control-label">
        <h5>End Date</h5>
    </div>
    <div style="background: #202a29; text-align: center;" class="col-md-1 control-label">
        <h5>Action</h5>
    </div>
  </div>
    
  <?php $i = 1; while ($row = mysqli_fetch_assoc($leavedetails)) {
      $empid = $row['EmpId'];
      $name = mysqli_query($db, "SELECT * FROM employee WHERE EmployeeId='$empid'");

      if ($empname = mysqli_fetch_assoc($name)) {
          $namem = ucfirst($empname['FirstName']) . "&nbsp;" . ucfirst($empname['LastName']);
      } else {
          $namem = "Unknown Employee";
      }

      $typen = $row['TypesLeaveId'];
      $typeid = mysqli_query($db, "SELECT * FROM type_of_leave WHERE LeaveId='$typen'");

      if ($typename = mysqli_fetch_assoc($typeid)) {
          $leaveType = ucfirst($typename['Type_of_Name']);
      } else {
          $leaveType = "Unknown Leave Type";
      }
  ?>
  <div class="row" style="margin-right: 0; margin-top: 10px; margin-left: 0;">
    <div class="col-md-1"><?php echo $i++; ?></div>
    <div class="col-md-2"><?php echo $namem; ?></div>
    <div class="col-md-1" style="text-align: center;"><?php echo $row['EmpId']; ?></div>
    <div class="col-md-2"><?php echo ucfirst($leaveType); ?></div>
    <div class="col-md-1"><?php echo ucfirst($row['Reason'] ?? ""); ?></div>
    <div class="col-md-2" style="text-align: center;"><?php echo $row['StartDate'] ?? ""; ?></div>
    <div class="col-md-2" style="text-align: center;"><?php echo $row['EndDate'] ?? ""; ?></div>
    <div class="col-md-1" style="text-align: center;">
      <a href="?id=<?php echo $row['Detail_Id']; ?>" title="Accept"><i class="fa fa-check" aria-hidden="true"></i></a>&nbsp;&nbsp;
      <a href="?msg=<?php echo $row['Detail_Id']; ?>" title="Denied"><i class="fa fa-times" style="color: #202a29;" aria-hidden="true"></i></a>
    </div>
  </div>
  <hr style="margin-bottom: 0px; margin-top: 0px; border-top: 1px solid #eee;">
  <?php } ?>    
</div>

<div class="validation-form" style="margin-bottom: 0px; margin-top: 10px;">
<h2>Accepted Leave</h2>
<div class="row" style="color: white; margin-right: 0; margin-left: 0;">
  <div class="col-md-1" style="background-color: #202a29;">
    <h5>ID</h5>
  </div>
  <div class="col-md-4" style="background-color: #202a29;">
    <h5>Name</h5>
  </div>
  <div class="col-md-3" style="background-color: #202a29;">
    <h5>Leave Type</h5>
  </div>
  <div class="col-md-2" style="background-color: #202a29;">
    <h5>Start Date</h5>
  </div>
  <div class="col-md-2" style="background-color: #202a29;">
    <h5>End Date</h5>
  </div>
</div>

<?php $i = 1; while ($row = mysqli_fetch_assoc($laccept)) {
    $name = ucfirst($row['FirstName'] . " " . $row['LastName']);
?>
<div class="row" style="margin-right: 0; margin-left: 0;">
  <div class="col-md-1">
    <h5><?php echo $i++; ?></h5>
  </div>
  <div class="col-md-4">
    <h5><?php echo $name; ?></h5>
  </div>
  <div class="col-md-3">
    <h5><?php echo $row['Type_of_Name']; ?></h5>
  </div>
  <div class="col-md-2">
    <h5><?php echo $row['StartDate']; ?></h5>
  </div>
  <div class="col-md-2">
    <h5><?php echo $row['EndDate']; ?></h5>
  </div>
</div>
<hr style="margin-bottom: 0px; margin-top: 0px; border-top: 1px solid #eee;">
<?php } ?>
<div class="clearfix"></div>
</div>

<div class="validation-form" style="margin-bottom: 30px; margin-top: 10px;">
<h2>Denied Leave</h2>
<div class="row" style="color: white; margin-right: 0; margin-left: 0;">
  <div class="col-md-1" style="background-color: #202a29;">
    <h5>ID</h5>
  </div>
  <div class="col-md-4" style="background-color: #202a29;">
    <h5>Name</h5>
  </div>
  <div class="col-md-3" style="background-color: #202a29;">
    <h5>Leave Type</h5>
  </div>
  <div class="col-md-2" style="background-color: #202a29;">
    <h5>Start Date</h5>
  </div>
  <div class="col-md-2" style="background-color: #202a29;">
    <h5>End Date</h5>
  </div>
</div>

<?php $i = 1; while ($row = mysqli_fetch_assoc($ldenied)) {
    $name = ucfirst($row['FirstName'] . " " . $row['LastName']);
?>
<div class="row" style="margin-right: 0; margin-left: 0;">
  <div class="col-md-1">
    <h5><?php echo $i++; ?></h5>
  </div>
  <div class="col-md-4">
    <h5><?php echo $name; ?></h5>
  </div>
  <div class="col-md-3">
    <h5><?php echo $row['Type_of_Name']; ?></h5>
  </div>
  <div class="col-md-2">
    <h5><?php echo $row['StartDate']; ?></h5>
  </div>
  <div class="col-md-2">
    <h5><?php echo $row['EndDate']; ?></h5>
  </div>
</div>
<hr style="margin-bottom: 0px; margin-top: 0px; border-top: 1px solid #eee;">
<?php } ?>
</div>
<div class="clearfix"></div>
</form>
<?php include('footer.php'); ?>
