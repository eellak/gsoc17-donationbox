/*
 * This source file contains, the functionality of the page to changing the donation projects.
 * You can use the left or right arrow keys to switch between the donation projects.
 *
 */

$(document).ready(function()
{

    $('body').keypress(function (e)
    {
        var full_location = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '');
        var args = "/project/" ;

        if ( e.keyCode == 39) // Right arrow key
        {
            var next_project = $('#next_donation_project_id').val();

            args += next_project + "/";
            full_location += args;
        }

        if ( e.keyCode == 37) // Left arrow key
        {
            var previous_project = $('#previous_donation_project_id').val();

            args += previous_project + "/";
            full_location += args;
        }

        $(location).attr( 'href', full_location );

    });

});
