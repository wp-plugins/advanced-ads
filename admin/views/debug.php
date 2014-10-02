<?php
/**
 * the view for the debug page
 */
?>

<div class="wrap">
    <h2 style="color:red;"><?php _e('Work in progress', $this->plugin_slug); ?></h2>
    <p><?php _e('This screen is work in progress. You can use the information if you understand them, but there is nothing to do here yet.', $this->plugin_slug); ?></p>
    <?php screen_icon(); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <h2><?php _e('Settings', $this->plugin_slug); ?></h2>
    <pre><?php print_r($plugin_options); ?></pre>

    <h2><?php _e('Ad Condition Overview', $this->plugin_slug); ?></h2>
    <pre><?php print_r($ads_by_conditions); ?></pre>

    <h2><?php _e('Ad Placements', $this->plugin_slug); ?></h2>
    <pre><?php print_r($ad_placements); ?></pre>

</div>
