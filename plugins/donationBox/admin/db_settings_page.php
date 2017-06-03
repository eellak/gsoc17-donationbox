<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" action="options.php">
        <?php

            settings_fields("general_section"); // τα δεδομένα ποιανού section να εμφανίσει.

            do_settings_sections("db-settings-menu");
            
            submit_button();

        ?>         
    </form>
</div>




