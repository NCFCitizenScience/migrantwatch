<?
include("db.php");
$id= $_GET['id'];

$sql = "delete from migwatch_species_set where id='$id'";
mysql_query($sql);

header("Location: addspecies.php");
?>
