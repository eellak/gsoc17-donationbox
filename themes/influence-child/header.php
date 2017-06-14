<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package influence
 * @since influence 1.0
 * @license GPL 2.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <script src="/jquery.min.js"></script>
  <script src="/jquery.bpopup.min.js"></script>
  <script src="/jquery.plugin.js"></script>
  <script src="/jquery.countdown.min.js"></script>
  <link href="/pro-bars.min.css" rel="stylesheet" type="text/css" media="all" />
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
<script>

var ShowPopUps = false;
var DonationPopUp = true;
var ShowTimer = true;
var ProBar = true;
var GameActive = true;
var playgame = false;
var projects = 2;
var sel_project = 0;
var home_url = "http://localhost/Project-1";
var timeout = 500;//120; //timeout in seconds
var str_href_project_id = "Project";
var wait_for_print_timeout = 11000; //timeout for pop up when printing receipt

function startTimer(duration, display) {
    var start = Date.now(),
        diff,
        minutes,
        seconds;
    function timer() {
        // get the number of seconds that have elapsed since
        //startTimer() was called
        diff = duration - (((Date.now() - start) / 1000) | 0);
        // does the same job as parseInt truncates the float
        minutes = (diff / 60) | 0;
        seconds = (diff % 60) | 0;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        display.textContent = minutes + ":" + seconds;
        if (diff <= 0) {
            // add one second so that the count down starts at the full duration
            // example 05:00 not 04:59
            start = Date.now() + 1000;
        }
    };
    // we don't want to wait a full second before the timer starts
    timer();
    setInterval(timer, 1000);
}

window.onload = function () {
  href = window.location.href;
  //If we are on a project page, set timer to return to home page
  if ((href.includes(str_href_project_id)) && (ShowTimer)) {
    display = document.querySelector('#time');
    startTimer(timeout, display);
    setTimeout(function(){ document.location.href=home_url; }, timeout*1000)
  }

  $( ".item:eq(0)" ).find( "a" ).focus();
  $( ".item:eq(0)" ).css("border-radius","10px");
  $( ".item:eq(0)" ).css("border","8px solid red");

  for (i=1; i <= projects; i++)
  {
    $( ".item:eq("+i+")" ).css("opacity","0.4");
  }
};

$(document).ready(function(){
    /*Show loading gif when Language Image is clicked*/
    $(".qtranxs_image").click(function(){
        $('#loading').show();
    });
    $('.osc-res-tab a').click(function() {
        $(this) === this;
        var activetab = $(this).attr('href');
        if (activetab == '#ert_pane1-0') {
        var player0 = document.getElementById('videoplayer0');
        var player1 = document.getElementById('videoplayer1');
        var player2 = document.getElementById('videoplayer2');
            player0.play();SetTimer
            player1.pause();
            player2.pause();
        } else if (activetab == '#ert_pane1-1') {
        var player0 = document.getElementById('videoplayer0');
        var player1 = document.getElementById('videoplayer1');
        var player2 = document.getElementById('videoplayer2');
            player0.pause();
            player1.play();
            player2.pause();
        } else if (activetab == '#ert_pane1-2') {
        var player0 = document.getElementById('videoplayer0');
        var player1 = document.getElementById('videoplayer1');
        var player2 = document.getElementById('videoplayer2');
            player0.pause();
            player1.pause();
            player2.play();
        }
    });
});

  function SetTimer() {
        var oldDateObj = new Date();
        var newDateObj = new Date(oldDateObj.getTime() + 3*60000);
        $('#defaultCountdown').countdown({until: newDateObj, layout: '{mn} {snn}', expiryUrl: '/'});
  }
  function ResetTimer() {
        var oldDateObj = new Date();
        var newDateObj = new Date(oldDateObj.getTime() + 3*60000);
        $('#defaultCountdown').countdown('option',{until: newDateObj, layout: '{mn} {snn}', expiryUrl: '/'});
  }


$(function() {

        SetTimer();

        var ws;
        var donationdata;
        //Set progressbar
        var SetProgressBar = function() {
            sum = parseFloat($('#totalamount').html());
            goal = parseFloat($('#goal').html());
            if (sum == 0) {
                percent = 0;
            } else {
                percent = (sum/goal)*100;
            }
            if (document.getElementById('probar') != null)
            {
                document.getElementById('probar').setAttribute("data-pro-bar-percent", String(percent));
                document.getElementById('probar').style.width = String(percent)+"%";
            }

            $('#percentvalue').html(parseFloat(percent).toFixed(2)+"%");
        }

        var logger = function(msg){
          var now = new Date();
          var sec = now.getSeconds();
          var min = now.getMinutes();
          var hr = now.getHours();
          $("#logger").html($("#logger").html() + "<br>" + hr + ":" + min + ":" + sec + " ___ " +  msg);
          $('#logger').scrollTop($('#logger')[0].scrollHeight);
        }

        var sender = function() {
          var msg = $("#msg").val();
          if (msg.length > 0)
            ws.send(msg);
          $("#msg").val(msg);
        }
        var keyboard = function() {
          //ws.send('KEYBOARD');
        }

        var donate = function() {
            donationdata = $('#donationvalue_side').html();//+$('#currency_side').html();
            //Send Donation Data to WebSocket Server
            //NAME|EMAIL|PUBLIC|PROJECT NAME|PROJECT ID|DONATION
            url = window.location.href
        	//projectdetails = url.split('/');
            projectdetails = url.split('-');
            //alert(projectdetails);
            //check for project id
            pid = projectdetails[projectdetails.length-1]
            if (isNaN(pid)) {
                //Split donation among available projects
                var donation = donationdata / projects;
                for (i = 1; i <= projects; i++) {
                    //alert('DONATION|||0|COOPBOX|'+i+'|'+donation+'EUR');
                    ws.send('DONATION|||0|COOPBOX|'+i+'|'+donation+'EUR');
                }
            } else {
                if ( (GameActive) && (playgame) ) {
                    //ws.send('PLAY|||0|MSF|1|'+donationdata)
                    ws.send('PLAY|||0|COOPBOX|'+pid+'|'+donationdata+'EUR')
                } else {
                    //ws.send('DONATION|||0|MSF|1|'+donationdata)
                    ws.send('DONATION|||0|COOPBOX|'+pid+'|'+donationdata+'EUR')
                }
            }
            //MSF-DONATION
            //ws.send('DONATION|'+donationdata)
            //Add to total amount
            amount = parseFloat($('#totalamount').html());
            amount = parseFloat(amount)+parseFloat(donationdata);
            //alert("donate" + amount);
            $('#totalamount').html(parseFloat(amount).toFixed(2));
            //Clear fields
            $('#donationvalue_side').html(parseFloat(0).toFixed(2));
            //$('#donationvalue_pop').html(parseFloat(0).toFixed(2));
            //Disable Donation button
            //$('#donationbutton').attr("disabled", "disabled");

            if (GameActive) {
              $('#playbutton').attr("disabled", "disabled");
            }

            if (ProBar) {
              SetProgressBar();
            }
        }
        /*
        var registration = function() {
		//Validate Email & Name
            if (document.getElementById('name') == null) {
                name = '';
            } else {
                name = document.getElementById('name').value;
            }
            if (document.getElementById('email') == null) {
                email = '';
            } else {
                email = document.getElementById('email').value;
            }
            var re = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a€-zA-Z0-9-]+)*$/;
            if ((email =='') || (re.test(email) == false)){
                //$('#email').css("background","#FF9999");
                //alert('Please enter a valid e-mail address.');
                if (ShowPopUps) {
                    $('#registration_error_pop_up').bPopup({
                        autoClose: 2000
                    });
                }
            } else {
                //Send Registration Data to WebSocket Server
                //NAME|EMAILeasy responsive tabs
                ws.send('NEWSLETTER|'+name+"|"+email)
                //clear form fields
                document.getElementById('email').value = '';
                //document.getElementById('name').value = '';

                if (ShowPopUps) {
                    $('#registration_success_pop_up').bPopup({
                        autoClose: 2000
                    });
                }
            }
        }
        /**/

        ws = new WebSocket("ws://localhost:8888/ws");
        ws.onmessage = function(evt) {
            //alert(evt.data);
            //logger(evt.data);
            if (evt.data == 'SUCCESS') {
              /*if (ShowPopUps) {
                $('#success_to_pop_up').bPopup();
              }  */
            } else if (String(evt.data).indexOf('|TOTAL|') > -1) {
                values = String(evt.data).split('|')
                //check PID
                $('#totalamount').html(parseFloat(values[3]).toFixed(2));
                if (ProBar) {
                  SetProgressBar();
                }
            } else if (evt.data == 'ERROR') {
              /*if (ShowPopUps) {
                $('#error_to_pop_up').bPopup();
              } */
            } else if (String(evt.data).indexOf('COINS|') > -1){
                values = String(evt.data).split('|');
                donationdata = values[1];
                currency = values[2];
                url = window.location.href;
                //Single Project
                if ((donationdata != "") && (donationdata != 0)){
                //Multi projects
                //if ((donationdata != "") && (url.indexOf('/project/') >= 0)) {
                    amount = parseFloat(donationdata);
                    /**/
                    if ($('#donationvalue_side').html().length > 0)
                    {
                        amount += parseFloat($('#donationvalue_side').html())
                        $('#donationvalue_side').css("font-weight", "Bold");
                    }
                    $('#donationvalue_side').html(parseFloat(amount).toFixed(2));
                    //$('#currency_side').html(currency);
                    /**/
                    //Donation Pop Up
                    if (DonationPopUp) {
                        //alert('Show donation');
                        sum = parseFloat($('#totalamount').html());
                        $('#donation_pop_up').bPopup({
                            //autoClose: 10000,
                            position: (['auto','auto'])
                        });
                        $('#donationvalue_pop').html(parseFloat(amount).toFixed(2));
                    }
                    //$('#donationbutton').removeAttr("disabled");
                    if (GameActive) {
                      //$('#playbutton').removeAttr("disabled");
                    }

                    //$('#currency_pop').html(currency)
                }/* else {
                    $('#donationvalue_pop').html('You have inserted ' + parseFloat(donationdata).toFixed(2) + '<br> but you must first select a project to donate that amount to...');
                    $('#donationbutton').attr("disabled", "disabled");
                }*/
            } else {
                //Unknown Message received
            }
        };
        ws.onclose = function(evt) {
          $("#logger").text("Connection was closed...");
          //Disable Donation Button and make it red
          //$('#donationbutton').attr("disabled", "disabled");
          if (GameActive) {
            //$('#playbutton').attr("disabled", "disabled");
          }
          //$('#donationbutton').css("color", "#CC2E2E");
        };
        ws.onopen = function(evt) {
           $("#logger").text("Opening socket...");
            //Send Request project total
            url = window.location.href
            if (url.includes(str_href_project_id))
            {
                pid = url.split('-');
                ws.send('REQPROJECTTOTAL|'+pid[1]+'|')
            }

           //$('#donationbutton').css("color", "#2ECC71");
           //$('#donationbutton').css("color", "#FFF");
           if (GameActive) {
               //$('#playbutton').css("color", "#FFF");
           }
        };

        $("#msg").keypress(function(event) {
          if (event.which == 13) {
             sender();
           }
        });
        $("#donationbutton").click(function(){
          donate();
          if (ShowPopUps) {
            $('#thankyou_pop_up').bPopup({
                autoClose: 2000
            });
            //setTimeout(function(){ document.location.href="/"; }, 3000)
          }
        });
        if (GameActive) {
            playgame = true;
            $("#playbutton").click(function(){
            donate();
          });
        }

        $("#newsletterbutton").click(function(){
          registration();
        });
        /*$("#thebutton").click(function(){
          sender();
        });
        $('#formName').click(function(){
          keyboard();
        });
        $('#formEmail').click(function(){
          keyboard();
        }); */

      $(document).scroll( function(){
        var oldDateObj = new Date();
        var newDateObj = new Date(oldDateObj.getTime() + 3*60000);
        $('#defaultCountdown').countdown('option',{until: newDateObj, layout: '{mn} {snn}', expiryUrl: '/'});
      });

      if (ProBar) {
        //SetProgressBar();
      }
      /*
      $(document).on("pagecreate",".single-post",function(){
        $(document).on("scrollstart",function(){
           ResetTimer();
        });
      });
      /**/

         // DOM Ready
    $(document).keydown(function(e) {
    url = window.location.href;
    switch(e.which) {
        case 13: //enter
        if (parseFloat($('#donationvalue_side').html()) > 0) {
          e.preventDefault();
          $('#donation_pop_up').css("display","none");
          $('.b-modal').css("display","none");
          donate();
          //Printing receipt
          $('#printing_pop_up').bPopup({
            position: (['auto','auto'])
          });
          $('#loading').show();
          setTimeout(function(){ location.reload(); }, wait_for_print_timeout)
        }
            //Close donation pop up and sent donation
            //alert("button pressed");
            //If donation pop up is not hidden
            /*
            if ($('#donation_pop_up').css('display') == 'none') {
                alert("donation pop up none");

                if (url.includes(str_href_project_id))
                {
                    //return to home
                    window.location.href = home_url;
                }
            } else {
              /**/
            //}
        break;

        case 37: // left
        if ( ($('#donation_pop_up').css('display') == 'none') &&
             ($('#printing_pop_up').css('display') == 'none') ) {
          if (url.includes("Project-1"))
          {
              window.location.href = "/Project-2"
          } else {
              window.location.href = "/Project-1"
          }
        }
          /*
          sel_project--;
          if (sel_project < 0)
          {
              sel_project = projects-1;
              $( ".item:eq(0)" ).css("border","solid 8px white");
              $( ".item:eq(0)" ).css("opacity","0.4");
              $( ".item:eq(0)" ).find( "a" ).focus();
          }
          else
          {
              $( ".item:eq("+(sel_project+1)+")" ).css("border","solid 8px white");
              $( ".item:eq("+(sel_project+1)+")" ).css("opacity","0.4");
              $( ".item:eq("+(sel_project+1)+")" ).find( "a" ).focus();
          }
          $( ".item:eq("+sel_project+")" ).css("border-radius","10px");
          $( ".item:eq("+sel_project+")" ).css("border","solid 8px red");
          $( ".item:eq("+sel_project+")" ).css("opacity","1");
          $( ".item:eq("+sel_project+")" ).find( "a" ).focus();
          /**/
          /*$( "ul.nav" ).each(function( index ) {
            $( this ).find( "li:eq(1)" ).css( "fontStyle", "italic" );
            });*/
          //$(".grid-items div:eq("+sel_project+")").css("border","black 8px solid");
        break;

        case 38: // up
        e.preventDefault();
        break;

        case 39: // right
        if ( ($('#donation_pop_up').css('display') == 'none') &&
               ($('#printing_pop_up').css('display') == 'none') ) {
          if (url.includes("Project-1"))
          {
              window.location.href = "/Project-2"
          } else {
              window.location.href = "/Project-1"
          }
        }
        /*
        sel_project++;
          if (sel_project >= projects)
          {
              sel_project = 0;
              $( ".item:eq("+(projects-1)+")" ).css("border","solid 8px white");
              $( ".item:eq("+(projects-1)+")" ).css("opacity","0.4");
              $( ".item:eq("+(projects-1)+")" ).find( "a" ).focus();
          }
          else
          {
            $( ".item:eq("+(sel_project-1)+")" ).css("border","solid 8px white");
            $( ".item:eq("+(sel_project-1)+")" ).css("opacity","0.4");
            $( ".item:eq("+(sel_project-1)+")" ).find( "a" ).focus();
          }
          $( ".item:eq("+sel_project+")" ).css("border-radius","10px");
          $( ".item:eq("+sel_project+")" ).css("border","solid 8px red");
          $( ".item:eq("+sel_project+")" ).css("opacity","1");
          $( ".item:eq("+sel_project+")" ).find( "a" ).focus();
          /**/

        break;

        case 40: // down
        e.preventDefault();
        break;

        default: return; // exit this handler for other keys
    }
    //e.PreventDefault(); // prevent the default action (scroll / move caret)
});
        $(function() {
            // Binding a click event
            // From jQuery v.1.7.0 use .on() instead of .bind()
            $('#my-button').on('click', function(e) {
                // Prevents the default action to be triggered.
                e.preventDefault();
                // Triggering bPopup when click event is fired
                $('#element_to_pop_up').bPopup();
            });
        });
});

</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php
	$after_header = apply_filters('influence_after_header', '');

	if( empty($after_header) || !siteorigin_setting('home_menu_overlaps') ) {
		// We'll use a sentinel to take up space
		influence_site_header_sentinel();
		echo $after_header;
	}
	else {
		echo $after_header;
	}
	?>

	<div id="main" class="site-main">
        <div id="printing_pop_up">
          <div class="popup_content">
            <div style="font-size:20pt;">
              Παρακαλώ περιμένετε ενώ<br />εκτυπώνουμε την απόδειξή σας...<br />
            </div><br /><br /><br /><br /><br /><br /><br /><br /><br />
            <div style="background:#70b9aa;color:black;font-size:16px;">
              Σκανάρε το QR code με το κινητό σου<br />
              ή μπες στο <div style="color:white;display:inline;">thecoopbox.commonslab.gr</div><br />
              Με τον κωδικό που αναγράφεται<br />
              στις αποδείξεις από της δωρεές σου,<br />
              μπορείς να συγκεντρώσεις πόντους<br />
              για να πάρεις δώρο ένα T-shirt!
            </div>
            <div id="loading"><img src="/wp-content/themes/influence-child/loading.gif" /></div>
          </div>
        </div>
        <div id="donation_pop_up">
            <div class="popup_content">
                <div style="font-size:24pt">Η ΔΩΡΕΑ ΣΟΥ ΕΙΝΑΙ:</div>
                <div id="donationvalue_pop" style="display:inline;color:red;font-size:35pt;"></div>
                <div id="currency_side" style="display:inline;color:black;font-size:35pt;">€</div>
                <br>
                <div style="font-size:18pt;color:#333;text-align: left;">> Αν θέλεις αύξησε τη δωρεά σου.
                <br>
                > Πάτησε το κουμπί για να καταχωρηθεί.
                <br>
                > Πάρε την απόδειξη σου.
                </div>
                <div style="background:#70b9aa;color:black;font-size:16px;">
                  Μην ξεχάσεις να μπεις στο<br />
                  <div style="color:white;">thecoopbox.commonslab.gr</div>
                  Με τον κωδικό που αναγράφεται<br />
                  στις αποδείξεις από της δωρεές σου,<br />
                  μπορείς να συγκεντρώσεις πόντους<br />
                  για να πάρεις δώρο ένα T-shirt!
                </div>
                <div style="text-align:center">
                  ΕΥΧΑΡΙΣΤΟΥΜΕ <br />ΓΙΑ ΤΗ ΣΤΗΡΙΞΗ
                </div>
            </div>
        </div>
        <div id="thankyou_pop_up">
            <span class="button b-close">X</span>
            <div class="popup_content">
                <br>
                <b>THANK YOU</b>
                <br>
                for your donation!
            </div>
        </div>
        <div id="registration_success_pop_up">
            <span class="button b-close">X</span>
            <div class="popup_content">
                <br>
                <b>THANK YOU</b> for registering
                <br>
                to our newsletter.
            </div>
        </div>
        <div id="registration_error_pop_up">
            <span class="button b-close">X</span>
            <div class="popup_content">
                <br>
                Please enter a valid
                <br>
                e-mail address.
            </div>
        </div>
