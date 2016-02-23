"FluxBB Patch" by Rodrigo Sepúlveda Heerwagen
https://distropress.org

== admin_groups.php
find = 'UPDATE '.$db->prefix.'users SET group_id='.$move_to_group.' WHERE group_id='.$group_id
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$move_to_group.' WHERE meta_key=\'fluxbb-group_id\' AND meta_value='.$group_id

find = 'UPDATE '.$db->prefix.'users SET group_id = '.$promote_next_group.' WHERE group_id = '.intval($_POST['group_id']).' AND num_posts >= '.$promote_min_posts
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$promote_next_group.' WHERE meta_key=\'fluxbb-group_id\' AND user_id IN (SELECT user_id FROM (SELECT user_id FROM '.$GLOBALS['table_prefix'].'usermeta WHERE meta_key=\'fluxbb-num_posts\' AND meta_value >= '.$promote_min_posts.') AS user_id)'

== admin_users.php
find = 'UPDATE '.$db->prefix.'users SET group_id='.$new_group.' WHERE id IN ('.implode(',', $user_ids).')'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$new_group.' WHERE meta_key=\'fluxbb-group_id\' AND user_id IN ('.implode(',', $user_ids-1).')'

== db_update.php
find = 'UPDATE '.$db->prefix.'users SET group_id = 0 WHERE group_id = 32000'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=0 WHERE meta_key=\'fluxbb-group_id\' AND meta_value=32000'

find = 'UPDATE '.$db->prefix.'users SET group_id = '.$temp_id.' WHERE group_id = '.$mod_gid
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$temp_id.' WHERE meta_key=\'fluxbb-group_id\' AND meta_value='.$mod_gid

find = 'UPDATE '.$db->prefix.'users SET group_id = '.$mod_gid.' WHERE group_id = 3'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$mod_gid.' WHERE meta_key=\'fluxbb-group_id\' AND meta_value=3'

find = 'UPDATE '.$db->prefix.'users SET group_id = 3 WHERE group_id = 2'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=3 WHERE meta_key=\'fluxbb-group_id\' AND meta_value=2'

find = 'UPDATE '.$db->prefix.'users SET group_id = 2 WHERE group_id = '.$temp_id
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=2 WHERE meta_key=\'fluxbb-group_id\' AND meta_value='.$temp_id

find = 'UPDATE '.$db->prefix.'users SET group_id = '.$temp_id.' WHERE group_id = '.$member_gid
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$temp_id.' WHERE meta_key=\'fluxbb-group_id\' AND meta_value='.$member_gid

find = 'UPDATE '.$db->prefix.'users SET group_id = '.$member_gid.' WHERE group_id = 4'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$member_gid.' WHERE meta_key=\'fluxbb-group_id\' AND meta_value=4'

find = 'UPDATE '.$db->prefix.'users SET group_id = 4 WHERE group_id = '.$temp_id
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=4 WHERE meta_key=\'fluxbb-group_id\' AND meta_value='.$temp_id

find = 'UPDATE '.$db->prefix.'users SET group_id=0 WHERE group_id=32000'
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=0 WHERE meta_key=\'fluxbb-group_id\' AND meta_value=32000'

find = 'UPDATE '.$db->prefix.'users SET signature = \''.$db->escape(preparse_bbcode($cur_item['signature'], $temp, true)).'\' WHERE id = '.$cur_item['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value=\''.$db->escape(preparse_bbcode($cur_item['signature'], $temp, true)).'\' WHERE WHERE meta_key=\'fluxbb-signature\' AND user_id='.$cur_item['id']-1

== login.php
find = 'UPDATE '.$db->prefix.'users SET password=\''.$form_password_hash.'\', salt=NULL WHERE id='.$cur_user['id']
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET password=\''.$form_password_hash.'\' WHERE id='.$cur_user['id']
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET group_id='.$pun_config['o_default_user_group'].' WHERE id='.$cur_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$pun_config['o_default_user_group'].' WHERE meta_key=\'fluxbb-group_id\' AND user_id='.$cur_user['id']-1

find = 'UPDATE '.$db->prefix.'users SET last_visit='.$pun_user['logged'].' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$pun_user['logged'].' WHERE meta_key=\'fluxbb-last_visit\' AND user_id='.($pun_user['id']-1)

find = 'UPDATE '.$db->prefix.'users SET activate_string=\''.pun_hash($new_password).'\', activate_key=\''.$new_password_key.'\', last_email_sent = '.time().' WHERE id='.$cur_hit['id']
replace = 'SELECT NULL'

== misc.php
find = 'UPDATE '.$db->prefix.'users SET last_visit='.$pun_user['logged'].' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$pun_user['logged'].' WHERE meta_key=\'fluxbb-last_visit\' AND user_id='.($pun_user['id']-1)

find = 'UPDATE '.$db->prefix.'users SET last_email_sent='.time().' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.time().' WHERE meta_key=\'fluxbb-last_email_sent\' AND user_id='.($pun_user['id']-1)

find = 'UPDATE '.$db->prefix.'users SET last_report_sent='.time().' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.time().' WHERE meta_key=\'fluxbb-last_report_sent\' AND user_id='.($pun_user['id']-1)

== post.php
find = 'UPDATE '.$db->prefix.'users SET num_posts=num_posts+1, last_post='.$now.' WHERE id='.$pun_user['id']
replace = 'UPDATE wp_usermeta SET meta_value=CASE WHEN meta_key=\'fluxbb-num_posts\' THEN meta_value+1 WHEN meta_key=\'fluxbb-last_post\' THEN '.$now.' ELSE meta_value END WHERE user_id='.($pun_user['id']-1)

find = 'UPDATE '.$db->prefix.'users SET group_id='.$new_group_id.' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$new_group_id.' WHERE meta_key=\'fluxbb-group_id\' AND user_id='.($pun_user['id']-1)

== profile.php
find = 'UPDATE '.$db->prefix.'users SET password=\''.$db->escape($cur_user['activate_string']).'\', activate_string=NULL, activate_key=NULL'.(!empty($cur_user['salt']) ? ', salt=NULL' : '').' WHERE id='.$id
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET password=\''.$new_password_hash.'\''.(!empty($cur_user['salt']) ? ', salt=NULL' : '').' WHERE id='.$id
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET email=activate_string, activate_string=NULL, activate_key=NULL WHERE id='.$id
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET activate_string=\''.$db->escape($new_email).'\', activate_key=\''.$new_email_key.'\' WHERE id='.$id
replace = 'SELECT NULL'

find = 'UPDATE '.$db->prefix.'users SET group_id='.$new_group_id.' WHERE id='.$id
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$new_group_id.' WHERE meta_key=\'fluxbb-group_id\' AND user_id='.($id-1)

find = 'UPDATE '.$db->prefix.'users SET '.implode(',', $temp).' WHERE id='.$id
replace = 'UPDATE wp_usermeta SET meta_value=CASE '.implode(' ', preg_replace(array('~^(title|icq|msn|location|timezone|dst|time_format|date_format|registration_ip)=(.*)~U', '~^(group_id|signature|disp_topics|disp_posts|email_setting|notify_with_post|auto_notify|show_smilies|show_img|show_img_sig|show_avatars|show_sig|language|style|num_posts|last_post|last_search|last_email_sent|last_report_sent|last_visit|admin_note|activate_string|activate_key)=(.*)~U', '~^(yahoo)=(.*)~U', '~^(realname|url)=(.*)~U', '~^(\w)=(.*)~U'), array('WHEN meta_key=\'distropress-\1\' THEN \2', 'WHEN meta_key=\'fluxbb-\1\' THEN \2', 'WHEN meta_key=yim THEN \2', '', 'WHEN meta_key=\1 THEN \2 '), $temp )).' ELSE meta_value END WHERE user_id='.($id-1)

find = 'UPDATE '.$db->prefix.'users SET group_id='.$next_group_id.' WHERE id='.$id
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$next_group_id.' WHERE meta_key=\'fluxbb-group_id\' AND user_id='.($id-1)

== search.php
find = 'UPDATE '.$db->prefix.'users SET last_search='.time().' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.time().' WHERE meta_key=\'fluxbb-last_search\' AND user_id='.($pun_user['id']-1)

== include/functions.php
find = 'UPDATE '.$db->prefix.'users SET last_visit='.$pun_user['logged'].' WHERE id='.$pun_user['id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$pun_user['logged'].' WHERE meta_key=\'fluxbb-last_visit\' AND user_id='.($pun_user['id']-1)

find = 'UPDATE '.$db->prefix.'users SET last_visit='.$cur_user['logged'].' WHERE id='.$cur_user['user_id']
replace = 'UPDATE '.$GLOBALS['table_prefix'].'usermeta SET meta_value='.$cur_user['logged'].' WHERE meta_key=\'fluxbb-last_visit\' AND user_id='.($cur_user['user_id']-1)
