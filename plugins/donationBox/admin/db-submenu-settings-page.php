<div class="wrap">

    <div id="icon-options-general" class="icon32"></div>
    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" action="options.php">
        <?php

            //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
            settings_fields("general_section");

            // all the add_settings_field callbacks is displayed here
            do_settings_sections("db-settings-menu");

            // Add the submit button to serialize the options
            submit_button();

        ?>         
    </form>
</div>
