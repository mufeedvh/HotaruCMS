<?php
/* **************************************************************************************************** 
 * ADMIN LANGUAGE
 *
 * PHP version 5
 *
 * LICENSE: Hotaru CMS is free software: you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License as 
 * published by the Free Software Foundation, either version 3 of 
 * the License, or (at your option) any later version. 
 *
 * Hotaru CMS is distributed in the hope that it will be useful, but WITHOUT 
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * You should have received a copy of the GNU General Public License along 
 * with Hotaru CMS. If not, see http://www.gnu.org/licenses/.
 * 
 * @category  Content Management System
 * @package   HotaruCMS
 * @author    Nick Ramsay <admin@hotarucms.org>
 * @copyright Copyright (c) 2009, Hotaru CMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link      http://www.hotarucms.org/
 */

/* Title */
$lang["admin"] = "Admin";

/* Breadcrumbs */
$lang["admin_breadcrumbs_home"] = "Home";
$lang["admin_breadcrumbs_login"] = "Login";

/* Login */
$lang["admin_login_failed"] = "Login failed";
$lang["admin_login_error_cookie"] = "Error setting cookie. Username not provided";
$lang["admin_login_email_invalid"] = "Email invalid";
$lang["admin_theme_login"] = "Login";
$lang["admin_theme_login_username"] = "Username";
$lang["admin_theme_login_password"] = "Password";
$lang["admin_theme_login_instructions"] = "Enter your username and password:";
$lang["admin_theme_login_form_submit"] = "Login";
$lang["admin_theme_login_forgot_password"] = "Forgot your password?";
$lang['admin_theme_login_forgot_password_submit'] = "Submit";
$lang['admin_theme_login_forgot_password_submit_instruct_1'] = "Enter the email address for your Admin account:";
$lang['admin_theme_login_forgot_password_submit_instruct_2'] = "You will be emailed a confirmation code. Click that confirmation code to be sent a new, random password. Use that to access your account and change it to something you can remember.";

/* News */
$lang["admin_news_posted_by"] = "Posted by";
$lang["admin_news_on"] = "on";
$lang["admin_news_read_more"] = "Read more";
$lang["admin_news_more_threads"] = "More recent threads...";

/* Announcements /class.hotaru.php */
$lang['admin_announcement_delete_install'] = "Please delete the install folder before someone deletes your database!";
$lang['admin_announcement_plugins_disabled'] = "Go to Plugin Management to enable some plugins.";
$lang['admin_announcement_users_disabled'] = "Please enable the Users plugin in Plugin Management.";
$lang['admin_announcement_change_site_email'] = "Please change the site email address in the Settings page.";
$lang['admin_announcement_site_closed'] = SITE_NAME . " is currently closed!";

/* Plugins */
$lang["admin_plugins_install_done"] = "Plugin successfully installed and activated";
$lang["admin_plugins_install_sorry"] = "Sorry,";
$lang["admin_plugins_install_requires"] = "requires";
$lang["admin_plugins_uninstall_done"] = "Plugin uninstalled";
$lang["admin_plugins_uninstall_all_done"] = "All plugins uninstalled";
$lang["admin_plugins_upgrade_done"] = "Plugin upgraded and activated";
$lang["admin_plugins_page_refresh"] = "Refresh this page";
$lang["admin_plugins_activated"] = "Plugin activated";
$lang["admin_plugins_deactivated"] = "Plugin deactivated";
$lang['admin_plugins_on'] = "On";
$lang['admin_plugins_off'] = "Off";
$lang['admin_plugins_install'] = "Install";
$lang['admin_plugins_uninstall'] = "Uninstall";
$lang['admin_plugins_upgrade'] = "Upgrade";
$lang['admin_plugins_installed'] = "Installed";
$lang['admin_plugins_order_updated'] = "Order updated";
$lang['admin_plugins_order_last'] = "is already last.";
$lang['admin_plugins_order_zero'] = "Error: The order value is zero.";
$lang['admin_plugins_order_first'] = "is already first.";
$lang['admin_plugins_order_last'] = "is already last.";
$lang['admin_plugins_order_above'] = "Error: The plugin to move above has the same order value.";
$lang['admin_plugins_order_below'] = "Error: The plugin to move below has the same order value.";
$lang["admin_theme_plugins"] = "Plugin Management";
$lang["admin_theme_plugins_installed"] = "Installed";
$lang["admin_theme_plugins_not_installed"] = "Not installed";
$lang["admin_theme_plugins_on_off"] = "On/Off";
$lang["admin_theme_plugins_active"] = "On";
$lang["admin_theme_plugins_inactive"] = "Off";
$lang["admin_theme_plugins_switch"] = "Switch";
$lang["admin_theme_plugins_plugin"] = "Plugin";
$lang["admin_theme_plugins_install"] = "Install";
$lang["admin_theme_plugins_uninstall"] = "Uninstall";
$lang["admin_theme_plugins_order_up"] = "Move up";
$lang["admin_theme_plugins_order_down"] = "Move down";
$lang["admin_theme_plugins_details"] = "Details";
$lang["admin_theme_plugins_requires"] = "Requires";
$lang["admin_theme_plugins_description"] = "Description";
$lang["admin_theme_plugins_close"] = "Close";
$lang["admin_theme_plugins_no_plugins"] = "No additional plugins needed.";
$lang["admin_theme_plugins_guide"] = "Plugin Management Guide";
$lang["admin_theme_plugins_guide1"] = "To upgrade a plugin, simply turn it off, overwrite the plugin files and turn it back on.";
$lang["admin_theme_plugins_guide2"] = "The order column is used to determine which plugins are checked for hooks first.";
$lang["admin_theme_plugins_guide3"] = "Uninstalling a plugin will delete it from the <i>plugins</i> and <i>pluginhooks</i> tables, but not <i>pluginsettings</i>.";
$lang["admin_theme_plugins_guide4"] = "Any other database entries created by the plugin will not be removed.";
$lang["admin_theme_plugins_deactivate_all"] = "Turn OFF all plugins";
$lang["admin_theme_plugins_activate_all"] = "Turn ON (upgrade) all plugins";
$lang["admin_theme_plugins_uninstall_all"] = "Uninstall all plugins";

/* Settings */
$lang['admin_settings_update_success'] = "Updated successfully.";
$lang["admin_theme_settings"] = "Settings";
$lang["admin_theme_settings_title"] = "Hotaru Settings";
$lang["admin_theme_settings_setting"] = "Setting";
$lang["admin_theme_settings_value"] = "Value";
$lang["admin_theme_settings_default"] = "Default";
$lang["admin_theme_settings_notes"] = "Notes";
$lang["admin_plugin_settings_inactive"] = "This plugin is currently inactive.";

/* Maintenance */
$lang['admin_maintenance_clear_cache_success'] = "Cache successfully deleted";
$lang['admin_maintenance_clear_cache_failure'] = "No cache files were found";
$lang['admin_maintenance_optimize_success'] = "All database tables optimized";
$lang['admin_maintenance_table_emptied'] = "Table emptied";
$lang['admin_maintenance_table_deleted'] = "Table deleted";
$lang['admin_maintenance_settings_removed'] = "Settings removed";
$lang["admin_maintenance_site_closed"] = SITE_NAME . " will be closed next page view";
$lang["admin_maintenance_site_opened"] = SITE_NAME . " will be opened next page view";
$lang["admin_theme_maintenance"] = "Maintenance";
$lang["admin_theme_maintenance_title"] = "Hotaru Maintenance";
$lang["admin_theme_maintenance_site"] = "Site:";
$lang["admin_theme_maintenance_close_site"] = "Close " . SITE_NAME . " for maintenance";
$lang["admin_theme_maintenance_open_site"] = "Open " . SITE_NAME . " to the public";
$lang["admin_theme_maintenance_close_site_desc"] = "Only users with \"access admin\" permissions will be able to view the site.";
$lang["admin_theme_maintenance_open_site_desc"] = "Finished maintenance? Allow everyone back in.";
$lang["admin_theme_maintenance_cache"] = "Cache:";
$lang["admin_theme_maintenance_database"] = "Database:";
$lang["admin_theme_maintenance_plugin_settings"] = "Plugin settings:";
$lang["admin_theme_maintenance_plugin_tables"] = "Plugin tables:";
$lang["admin_theme_maintenance_settings"] = "settings";
$lang["admin_theme_maintenance_db_cache"] = "Clear database cache";
$lang["admin_theme_maintenance_db_cache_desc"] = "deletes cached database queries.";
$lang["admin_theme_maintenance_css_js_cache"] = "Clear css/js cache";
$lang["admin_theme_maintenance_css_js_cache_desc"] = "deletes cached CSS and JavaScript files from plugins.";
$lang["admin_theme_maintenance_html_cache"] = "Clear HTML cache";
$lang["admin_theme_maintenance_html_cache_desc"] = "deletes cached blocks of HTML code, e.g. sidebar widgets";
$lang["admin_theme_maintenance_rss_cache"] = "Clear rss cache";
$lang["admin_theme_maintenance_rss_cache_desc"] = "deletes cached RSS feeds.";
$lang["admin_theme_maintenance_optimize"] = "Optimize database";
$lang["admin_theme_maintenance_optimize_desc"] = "Optimize all the database tables.";
$lang["admin_theme_maintenance_empty"] = "Empty";
$lang["admin_theme_maintenance_remove"] = "Remove";
$lang["admin_theme_maintenance_drop"] = "Delete";
$lang["admin_theme_maintenance_plugin_table_warning"] = "<b>Warning: Use these with extreme caution!</b>";
$lang["admin_theme_maintenance_plugin_table_warning2"] = "Note: Uninstall the plugins before emptying or deleting their tables.";
$lang["admin_theme_maintenance_plugin_settings_explanation"] = "Some Hotaru CMS plugins add settings to the database. To save you from having to reconfigure your plugins every time you upgrade, those settings are not removed, even when uninstalling the plugins. If for any reason, you want to delete those settings, you can do it here. It's highly recommended to uninstall each plugin first.";
$lang["admin_theme_maintenance_plugin_table_explanation"] = "Some Hotaru CMS plugins create their own database tables. These options allow you to easily clear or remove them <b>if absolutely necessary</b>.";
$lang["admin_theme_maintenance_empty_explanation"] = "Emptying tables will remove any data, but retain the structure. Remember, other plugins may rely on the data in these tables so removing them may cause problems with your site.";
$lang["admin_theme_maintenance_drop_explanation"] = "Deleting tables can <b>really mess up your site!</b> Only do it if you are starting your site completely from scratch, or if required by a plugin's instructions or developer.";
$lang["admin_theme_maintenance_no_plugin_tables_to_empty"] = "No plugin tables to empty.";
$lang["admin_theme_maintenance_no_plugin_tables_to_drop"] = "No plugin tables to delete.";
$lang["admin_theme_maintenance_no_plugin_settings_to_delete"] = "No plugin settings to delete.";

/* Blocked List */
$lang['admin_blocked_list_empty'] = "No value entered";
$lang['admin_blocked_list_added'] = "New item added";
$lang['admin_blocked_list_updated'] = "Item updated";
$lang["admin_blocked_list_removed"] = "Item removed";
$lang['admin_blocked_list_exists'] = "Item already exists";
$lang['admin_blocked_list_update'] = "Update";
$lang["admin_theme_blocked_desc"] = "By itself, this list doesn't do anything, but plugins can use it to block users during registration, post submission, etc.";
$lang["admin_theme_blocked_list"] = "Blocked List";
$lang["admin_theme_blocked_type"] = "Type";
$lang["admin_theme_blocked_value"] = "Value";
$lang["admin_theme_blocked_edit"] = "Edit";
$lang["admin_theme_blocked_remove"] = "Remove";
$lang["admin_theme_blocked_new"] = "Block a new item:";
$lang["admin_theme_blocked_search"] = "Search for an item:";
$lang["admin_theme_blocked_filter"] = "Filter items:";
$lang["admin_theme_blocked_submit_add"] = "Add";
$lang["admin_theme_blocked_submit_search"] = "Search";
$lang["admin_theme_blocked_submit_filter"] = "Filter";
$lang["admin_theme_blocked_ip"] = "IP address";
$lang["admin_theme_blocked_email"] = "Email address/domain";
$lang["admin_theme_blocked_url"] = "URL";
$lang["admin_theme_blocked_username"] = "Username";
$lang["admin_theme_blocked_all"] = "All";

/* Pagination */
$lang['pagination_first'] = "First";
$lang['pagination_last'] = "Last";
$lang['pagination_previous'] = "Previous";
$lang['pagination_next'] = "Next";

/* navigation */
$lang["admin_theme_navigation_home"] = "Home";
$lang["admin_theme_navigation_admin"] = "Admin";
$lang["admin_theme_navigation_login"] = "Login";
$lang["admin_theme_navigation_logout"] = "Logout";

/* main */
$lang["admin_theme_main_admin_cp"] = "Admin Control Panel";
$lang["admin_theme_main_admin_home"] = "Admin Home";
$lang["admin_theme_main_latest"] = "Latest from Hotaru CMS";
$lang["admin_theme_main_stats"] = "Stats";

/* plugin settings */
$lang["admin_theme_plugin_settings"] = "Plugin Settings";

/* footer */
$lang["admin_theme_footer_having_trouble_vist_forums"] = "Having trouble? Visit the forums at";
$lang["admin_theme_footer_for_help"] = "for help.";

/* 404 */
$lang["admin_theme_404_page_not_found"] = "Page not found.";

/* Account */
$lang["admin_theme_account"] = "Account";

?>