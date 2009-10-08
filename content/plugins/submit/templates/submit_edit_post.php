<?php
/**
 * Template for Submit: Edit Post
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
 
if ($hotaru->cage->post->getAlpha('edit_post') == 'true') {
    // Submitted this form...
    $title_check = $hotaru->cage->post->noTags('post_title');    
    $content_check = sanitize($hotaru->cage->post->getHtmLawed('post_content'), 2, $hotaru->post->allowableTags);
    $content_check = stripslashes($content_check);
    if ($hotaru->cage->post->keyExists('post_subscribe')) { $subscribe_check = 'checked'; } else { $subscribe_check = ''; }   
    $status_check = $hotaru->cage->post->testAlnumLines('post_status');
    $post_orig_url = $hotaru->cage->post->testUri('post_orig_url');
    $post_id = $hotaru->cage->post->getInt('post_id');    
    $hotaru->post->id = $post_id;
    
} elseif ($hotaru->cage->get->testInt('post_id'))  {
    $post_id = $hotaru->cage->get->testInt('post_id');
    $hotaru->post->readPost($post_id);
    $title_check = $hotaru->post->title;
    $content_check = $hotaru->post->content;
    if ($hotaru->post->subscribe == 1) { $subscribe_check = 'checked'; } else { $subscribe_check = ''; }
    $status_check = $hotaru->post->status;
    $post_orig_url = $hotaru->post->origUrl;
    $post_id = $hotaru->post->id;
}

$user = new UserBase($hotaru);
$user->getUserBasic($hotaru->post->author);
if ($hotaru->current_user->role != 'admin' && ($hotaru->current_user->id != $user->id)) { 
    $hotaru->message = "You don't have permission to edit this post.";
    $hotaru->messageType = "red";
    $hotaru->showMessage();
    return false;
    die();
}

$hotaru->plugins->pluginHook('submit_form_2_assign');

?>

    <div id="breadcrumbs">
        <a href='<?php echo BASEURL; ?>'><?php echo $hotaru->lang['submit_form_home']; ?></a> &raquo; 
        <?php echo $hotaru->lang["submit_edit_post_title"]; ?> &raquo; 
        <a href='<?php echo $hotaru->url(array('page'=>$hotaru->post->id)); ?>'><?php echo $hotaru->post->title; ?></a>
    </div>
        
    <?php echo $hotaru->showMessages(); ?>
            
    
    <?php echo $hotaru->lang["submit_edit_post_instructions"]; ?>

    <form name='submit_edit_post' action='<?php BASEURL; ?>index.php?page=edit_post&sourceurl=<?php echo $post_orig_url; ?>' method='post'>
    <table>
    <tr>
        <td><?php echo $hotaru->lang["submit_form_url"]; ?>&nbsp; </td>
        <td><?php echo $post_orig_url; ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo $hotaru->lang["submit_form_title"]; ?>&nbsp; </td>
        <td><input type='text' size=50 id='post_title' name='post_title' value='<?php echo $title_check; ?>'></td>
        <td>&nbsp;</td>
    </tr>
    
    <?php if ($hotaru->post->useContent) { ?>
    <tr>
        <td style='vertical-align: top;'><?php echo $hotaru->lang["submit_form_content"]; ?>&nbsp; </td>
        <td colspan=2><textarea id='post_content' name='post_content' rows='6' maxlength='<?php $hotaru->post->contentLength; ?>' style='width: 32em;'><?php echo $content_check; ?></textarea></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan=2 style='vertical-align: top;' class="submit_instructions"><?php echo $hotaru->lang['submit_form_allowable_tags']; ?><?php echo htmlentities($hotaru->post->allowableTags); ?></td>
    </tr>
    <?php } ?>
    
    <?php $hotaru->plugins->pluginHook('submit_form_2_fields'); ?>
        
    <?php if ($hotaru->current_user->role == 'admin') { ?>
    <!-- Admin only options -->
    
    <tr><td colspan=3><u><?php echo $hotaru->lang["submit_edit_post_admin_only"]; ?></u></td></tr>
    
    <tr>
        <td><?php echo $hotaru->lang["submit_form_url"]; ?>&nbsp; </td>
        <td><input type='text' size=50 id='post_orig_url' name='post_orig_url' value='<?php echo $post_orig_url; ?>'></td>
        <td>&nbsp;</td>
    </tr>
    
    <tr>
        <td style='vertical-align: top;'><?php echo $hotaru->lang["submit_edit_post_status"]; ?>&nbsp; </td>
        <td><select name='post_status'>
            <option value="<?php echo $status_check; ?>"><?php echo $status_check; ?></option>
            <?php 
            $statuses = $hotaru->post->getUniqueStatuses(); 
            if ($statuses) {
                foreach ($statuses as $status) {
                    if ($status != 'unsaved') { 
                        echo "<option value=" . $status . ">" . $status . "</option>\n";
                    }
                }
            }
            ?>
        </td>
    </tr>
    <!-- END Admin only options -->
    <?php } ?>
        
    <?php if ($hotaru->current_user->role != 'admin') { ?>
        <input type='hidden' name='post_orig_url' value='<?php echo $post_orig_url; ?>' />
    <?php } ?>
    <input type='hidden' name='post_id' value='<?php echo $post_id ?>' />
    <input type='hidden' name='edit_post' value='true' />
    
    <tr><td colspan=3>&nbsp;</td></tr>
    <tr><td>&nbsp; </td><td>&nbsp; </td><td style='text-align:right;'><input type='submit' class='submit' name='submit_edit_post' value='<?php echo $hotaru->lang["submit_edit_post_save"]; ?>' /></td></tr>    
    </table>
    </form>
