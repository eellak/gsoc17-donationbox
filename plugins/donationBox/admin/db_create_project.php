<div class="wrap">
    
    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" action="options.php">
        <?php


            settings_fields("create_project_section");

            do_settings_sections("db-create-project-submenu");

            // Add the submit button to serialize the options
            submit_button('Add new project');

        ?>         
    </form>
    
</div>

