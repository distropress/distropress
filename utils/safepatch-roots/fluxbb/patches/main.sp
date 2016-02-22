"FluxBB Patch" by Rodrigo Sepúlveda Heerwagen
https://distropress.org

== header.php
find regexp = 	~\t\$links\[\](.*)navregister(.*)';~U
replace =

find regexp = 	~\t\$links\[\](.*)navlogin(.*)';~U
replace =

find regexp = 	~\t\$links\[\](.*)navlogout(.*)';~U
replace =

== footer.php
find = exit($tpl_main);
replace = exit;

== profile.php
find regexp = ~\t\t\t\t\'realname(.*)\),\n~sU
add before = //

find regexp = ~\t\t\t\t\'url(.*)\),\n~sU
add before = //