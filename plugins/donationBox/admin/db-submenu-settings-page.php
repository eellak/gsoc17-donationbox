<div class="wrap">

    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <?php
    settings_errors();
    ?>
    
    <form method="post" action="options.php">
        <?php

            settings_fields("general_section");

            do_settings_sections("db-settings-menu");

            submit_button();

        ?>
    </form>
</div>
