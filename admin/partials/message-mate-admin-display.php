<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ownerlistens.com
 * @since      1.0.0
 *
 * @package    Message_Mate
 * @subpackage Message_Mate/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" name="cleanup_options" action="options.php">

    <?php settings_fields($this->plugin_name); ?>


  <?php
        //Grab all options
        $options = get_option($this->plugin_name);
        $mm_id = $options['mm_id'];
    ?>

    <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>


    <?php


        $data = $this->get_data( $mm_id);
        function is_paid_acc($data ){
            if($data){
                return  !$data['settings']['pb'];
            }
            return false;
        }
        $paid_acc = is_paid_acc($data );

    ?>




        <!-- load jQuery from CDN -->
        <?php if(!isset($mm_id) || !$mm_id) { ?>
            <h2 class="mm_welcome">Don't have a Message Mate yet? <a class="trial_link blue" href="https://ownerlistens.com/mate/" target="_blank">Click here to start your 14 day FREE trial</a> </h2>
        <?php } ?>

        <fieldset>

            <fieldset>
                <div class="mm_id_settings">
                    <legend class="screen-reader-text"><span><?php _e('Message Mate token', $this->plugin_name); ?></span></legend>
                    <div class="mm_id_editable" style=" <?php if(isset($mm_id) && $mm_id) {?> display:none; <?php } ?>">
                       <p> Message Mate Token
                        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-mm_id" name="<?php echo $this->plugin_name; ?>[mm_id]" value="<?php if(isset($mm_id) && $mm_id) echo $mm_id; ?>"/>
                        <?php submit_button('Save', 'primary','submit', TRUE); ?>
                         </p>
                    </div>

                    <div class="mm_id_non_editable" style=" <?php if(isset($mm_id) && trim($mm_id)) {?> display:block; <?php } else {  ?> display:none; <?php } ?>">
                        <p>Message Mate Token <b><?php echo $mm_id; ?></b> <a href="#" id="mm_id_edit_link" class="blue">EDIT</a></p>
                    </div>
                </div>
                <div class="mm_help_text">
                 <?php if(isset($mm_id) && $mm_id){ ?>


                    <?php if( $paid_acc) { ?>
                        <p class="secure_plan_options">To see and reply to incoming messages, <a href="https://ownerlistens.com/dashboard/" class="orange" target="_blank">go to the Message Mate Dashboard</a> </p>
                        <p class="secure_plan_options">Need to add users, update your design or make other changes? <a href="https://ownerlistens.com/dashboard/org_settings/" target="_blank"  class="blue" target="_blank">Go to the Message Mate Settings</a></p>
                    <?php } else { ?>
                        <p class="free_plan_options">Message Mate supports adding business users, receiving messages via email and dashboard, and can also keep participant information private. <a href="https://ownerlistens.com/login/?next=/message_mate/?is_upgraded=true" class="blue" target="_blank">Upgrade your Message Mate now</a> (free for 14 days, then only $10/month)</p>
                    <?php } ?>

                 <?php } ?>


                    <p>Questions? Text (<span class="orange">650-825-1166</span>) or email (<a href="mailto:support@ownerlistens.com" class="blue" target="_blank">support@ownerlistens.com</a>) the Message Mate team</p>
                </div>
            </fieldset>

        </fieldset>



    </form>

</div>


