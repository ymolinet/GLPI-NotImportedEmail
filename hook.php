<?php 

function plugin_notimportedemail_install() {
	global $DB;


	// Création de la table uniquement lors de la première installation
	if (!TableExists("glpi_plugin_notimportedemail_users")) {

		// requete de création de la table    
		$query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_notimportedemail_users` (
          `users_id` int(11) NOT NULL,
          `notification` tinyint(1) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;" ; 

		$DB->query($query) or die("Erreur lors de la création de la table <strong>users</strong> pour notimportedemail : ". $DB->error());
	}

    return true;
}

function plugin_notimportedemail_uninstall() {
    global $DB;

    $tables = array("glpi_plugin_notimportedemail_users");

    foreach($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`;");
    }

    return true;
}


?>