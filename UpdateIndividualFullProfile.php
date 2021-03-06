<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit</title>
</head>

<body>

<?php
$expertID = $_POST["ExpertID"];
$expertPrefix = str_replace("'","''",strip_tags($_POST["ExpertPrefix"]));
$expertFirstName = str_replace("'","''",strip_tags($_POST["ExpertFirstName"]));
$expertMiddleName = str_replace("'","''",strip_tags($_POST["ExpertMiddleName"]));
$expertLastName = str_replace("'","''",strip_tags($_POST["ExpertLastName"]));
$expertSuffix = str_replace("'","''",strip_tags($_POST["ExpertSuffix"]));
$expertDegree = str_replace("'","''",strip_tags($_POST["ExpertDegree"]));
$expertTitle = str_replace("'","''",strip_tags($_POST["ExpertTitle"]));
$expertProfileDesc = str_replace("'","''",strip_tags($_POST["ExpertProfileDesc"]));
$expertAddressLine1 = str_replace("'","''",strip_tags($_POST["ExpertAddressLine1"]));
$expertAddressLine2 = str_replace("'","''",strip_tags($_POST["ExpertAddressLine2"]));
$expertAddressLine3 = str_replace("'","''",strip_tags($_POST["ExpertAddressLine3"]));

require_once 'DatabaseConnection.php';

//Query to update Expert Bio data
$updateExpertDataQuery = 'UPDATE ExpertBioData SET Prefix=\''. $expertPrefix .'\', FirstName=\''. $expertFirstName .'\', MiddleName=\''. $expertMiddleName .'\', LastName=\''. $expertLastName .'\', Suffix=\''. $expertSuffix .'\', Degree=\''. $expertDegree .'\', Title=\''. $expertTitle .'\', ProfileDesc=\''. $expertProfileDesc .'\', AddressLine1=\''. $expertAddressLine1 .'\', AddressLine2=\''. $expertAddressLine2 .'\', AddressLine3=\''. $expertAddressLine3 .'\' WHERE ExpertID='.$expertID.';';

$expertData = sqlsrv_query( $connection, $updateExpertDataQuery);
if( !$expertData ) {
    die( print_r( sqlsrv_errors(), true));
}

//Query to delete contact
$updateExpertContactQuery = "DELETE FROM Contact WHERE ExpertID = '" . $expertID . "';";
$contactData = sqlsrv_query( $connection, $updateExpertContactQuery);
if( !$contactData ) {
	die( print_r( sqlsrv_errors(), true));
}

//Query to delete social media
$updateExpertContactQuery = "DELETE FROM SocialMedia WHERE ExpertID = '" . $expertID . "';";
$contactData = sqlsrv_query( $connection, $updateExpertContactQuery);
if( !$contactData ) {
	die( print_r( sqlsrv_errors(), true));
}

//Query to insert contact
$contactLoopCount = $_POST["ContactLoopCount"];
for($i = 1; $i <= $contactLoopCount; $i++)
{	
	if($_POST['ExpertContactType'.$i] == null){
		continue;
	}
	if($_POST['ExpertContactType'.$i] == "Email" || $_POST['ExpertContactType'.$i] == "Phone"){
		//$updateExpertContactQuery = 'UPDATE Contact SET ContactType=\''. $_POST['ExpertContactType'.$i] .'\', ContactDesc=\''. $_POST['ExpertContactDesc'.$i] .'\', ContactTimings=\''. $_POST['ExpertContactTimings'.$i] .'\' WHERE ExpertID='.$expertID.';';
		$updateExpertContactQuery = "INSERT INTO Contact (ExpertID, ContactType, ContactDesc) VALUES ('" . $expertID . "', '" . $_POST['ExpertContactType'.$i] ."', '" . $_POST['ExpertContactDesc' . $i] ."');";
	}
	else{
		$updateExpertContactQuery = "INSERT INTO SocialMedia (ExpertID, SocialMediaType, SocialMediaDesc) VALUES ('" . $expertID . "', '" . $_POST['ExpertContactType'.$i] ."', '" . $_POST['ExpertContactDesc' . $i] ."');";
	}
	$contactData = sqlsrv_query( $connection, $updateExpertContactQuery);
	
	if( !$contactData ) {
    	die( print_r( sqlsrv_errors(), true));
	}
	sqlsrv_commit($connection);	
}

//Query to delete E_Aoe
$updateExpertExpertiseQuery = "DELETE FROM Expert_AreaOfExpertise WHERE ExpertID = '" . $expertID . "';";
$expertiseData = sqlsrv_query( $connection, $updateExpertExpertiseQuery);
if( !$expertiseData ) {
	die( print_r( sqlsrv_errors(), true));
}

//Query to insert E_Aoe
$expertiseLoopCount = $_POST["ExpertiseLoopCount"];
for($i = 1; $i <= 10; $i++)//$expertiseLoopCount
{	
	if($_POST['Expertise'.$i] == null){
		continue;
	}
	$updateExpertExpertiseQuery = "IF NOT EXISTS (SELECT * FROM Expert_AreaOfExpertise WHERE ExpertID = " . $expertID . " AND AreaOfExpertiseID = " . $_POST['Expertise'.$i] . ") INSERT INTO Expert_AreaOfExpertise (ExpertID, AreaOfExpertiseID) VALUES ('" . $expertID . "', '" . $_POST['Expertise'.$i] ."');";
	
	$expertiseData = sqlsrv_query( $connection, $updateExpertExpertiseQuery);
	
	if( !$expertiseData ) {
    	die( print_r( sqlsrv_errors(), true));
	}
	sqlsrv_commit($connection);	
}

sqlsrv_close($connection);

header("Location: IndividualFullProfile.php?expert_id=" . $expertID);
?>

</body>

</html>