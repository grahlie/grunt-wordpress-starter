<?php
/**
 *	Grahlie Framework 1.0
 */

/*	Directories and paths */
define('GRAHLIE_DIR', get_template_directory() .'/framework');
define('GRAHLIE_URL', get_template_directory_uri() .'/framework');


/* Load Frontend Components */
require_once(GRAHLIE_DIR .'/grahlie-frontend-functions.php');

/*	Load Framework Components */
require_once(GRAHLIE_DIR .'/grahlie-admin-init.php');
require_once(GRAHLIE_DIR .'/grahlie-admin-functions.php');
require_once(GRAHLIE_DIR .'/grahlie-admin-page.php');

require_once(GRAHLIE_DIR .'/grahlie-input-init.php');
require_once(GRAHLIE_DIR .'/grahlie-input-create.php');


?>
