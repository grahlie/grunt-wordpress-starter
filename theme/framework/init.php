<?php
/**
 *	Grahlie Framework 1.0
 */

/*	Directories and paths */
define('GRAHLIE_DIR', get_template_directory() .'/framework');
define('GRAHLIE_URL', get_template_directory_uri() .'/framework');

/*	Load Framework Components */
require_once(GRAHLIE_DIR .'/grahlie-admin-init.php');
require_once(GRAHLIE_DIR .'/grahlie-admin-functions.php');
require_once(GRAHLIE_DIR .'/grahlie-admin-input.php');
require_once(GRAHLIE_DIR .'/grahlie-admin-page.php');

/* Pages */
require_once(GRAHLIE_DIR .'/pages/theme-settings.php');
require_once(GRAHLIE_DIR .'/pages/frontpage-settings.php');
require_once(GRAHLIE_DIR .'/pages/multisite-settings.php');


?>
