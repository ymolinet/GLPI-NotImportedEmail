<?php
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}
include_once (GLPI_ROOT . "/inc/includes.php");
   
global $DB;
if (isset ($_POST)) {
    $query_existe = "SELECT *
	FROM glpi_plugin_notimportedemail_users WHERE users_id = ".$_POST['id'];
	$result_existe = $DB->query($query_existe);
	if ($DB->numrows($result_existe)) {
		$query ="UPDATE `glpi_plugin_notimportedemail_users` SET `notification` = '".$_POST['notification']."' WHERE `users_id` = ".$_POST['id'];
	} else  {
		$query ="INSERT INTO `glpi_plugin_notimportedemail_users` (`users_id`,`notification`) VALUES ('".$_POST['id']."', '".$_POST['notification']."');";
	}
	$traitement = $DB->query($query) or die("Erreur lors de la modification des tables de droits plugin notimportedemail : ". $DB->error());
} 
Html::back();



?>