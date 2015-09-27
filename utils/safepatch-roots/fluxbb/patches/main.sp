"FluxBB Patch" by Rodrigo Sepúlveda Heerwagen
http://distropress.org

== header.php
find regexp = 	~\t\$links\[\](.*)navregister(.*)';~U
replace =

find regexp = 	~\t\$links\[\](.*)navlogin(.*)';~U
replace =

find regexp = 	~\t\$links\[\](.*)navlogout(.*)';~U
replace =

== footer.php
find = exit($tpl_main);
replace =

== include/functions.php
find regexp = ~function\ check_cookie(.*)\n\}~sU
replace = {
if (defined('DISTROPRESS_SCRIPT'))
	require_once DISTROPRESS__PLUGIN_DIR.'includes/'.DISTROPRESS_SCRIPT.'-cookies.php';
}

== include/dblayer/common_db.php
find regexp = ~switch\ \(\$db_type\)\n\{~sU
add = {

	case 'distropress':
		require_once DISTROPRESS__PLUGIN_DIR.'includes/'.DISTROPRESS_SCRIPT.'-dblayer.php';
		break;

}
