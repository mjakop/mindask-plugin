<div class="wrap">
<?php include_once('header.php'); ?>

<form method="post" action="?page=mindask-index&action=save">
    <?php settings_fields( 'mobile-news-app-settings-group' ); ?>
    <?php do_settings_sections( 'mobile-news-app-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
             <th scope="row"><?php echo __('Enabled','mindask') ?></th>
             <td><input name="enabled" value="1" type="checkbox" <?php if(get_option('mindask_enabled') == True) {?>checked="checked" <?php } ?>/></td>
        </tr>
        <tr>
             <th scope="row"><?php echo __('Selected survey','mindask') ?></th>
             <td>
                <select name="survey">
                    <option value="none"><?php echo __('Select survey for your webpage.','mindask') ?></option>
                    <?php
                    $selected = get_option('mindask_selected_survey');
                    foreach($surveys as $survey){
                    ?>
                    <option<?php if ($selected == $survey->public_id){?> selected="selected"<?php }?> value="<?php echo $survey->public_id; ?>"><?php echo $survey->name; ?></option>
                    <?php 
                    }
                    ?>
                </select>
             </td>
        </tr>
        <tr valign="top">
             <th scope="row"><?php echo __('Editing','mindask') ?></th>
             <td>
                <a href="http://app.mindask.com/api/plugins/open/?action=survey&code=<?php echo get_option('mindask_api_code'); ?>" target="_blank" class="button">Open editor</a><br />
             </td>
        </tr>
        <tr>
             <th scope="row"><?php echo __('Reports','mindask') ?></th>
             <td>
                <a href="http://app.mindask.com/api/plugins/open/?action=report&code=<?php echo get_option('mindask_api_code'); ?>" target="_blank" class="button">Open reports</a>
             </td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php include('copyright.php'); ?>