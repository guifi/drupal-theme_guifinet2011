/** JavaScript Document portada-guifi.js
 * aquesta funció s'encarrega de:
 * 1. gestionar les pestanyes d'actualitat de la home
 * 2. gestionar com mostrar les estadístiques de la home agafant les dades dels arxius cnml
 * 3. Fer que funcioni l'slider dels logos dels patrocinadors
 **/
 
 // When the document loads do everything inside here ...
            $(document).ready(function(){
                $('#slider1').scrollShow({
                    view:'.view',
                    content:'.images',
                    easing:'backout',
                    wrappers:'link,crop',
                    navigators:'a[id]',
                    navigationMode:'sr',
                    circular:true,
                    start:0
                });
                // When a link is clicked
                $("a.tab").click(function () {
                    // switch all tabs off
                    $(".active").removeClass("active");
                    // switch this tab on
                    $(this).addClass("active");
                    // slide all elements with the class 'content' up
                    //$(".content").slideUp();
                    $(".content").hide();
                    // Now figure out what the 'title' attribute value is and find the element with that id.  Then slide that down.
                    var content_show = $(this).attr("title");
                    //$("#"+content_show).slideDown();
                    $("#"+content_show).fadeIn();
                    return false;
                });
                // Desplegable 'Entra / Registra't'
                $(".signin").click(function(e) {
                    e.preventDefault();
                    $("fieldset#signin_menu").toggle();
                    $(".signin").toggleClass("menu-open");
                });
                $("fieldset#signin_menu").mouseup(function() {
                    return false
                });
                $(document).mouseup(function(e) {
                    if($(e.target).parent("a.signin").length==0) {
                        $(".signin").removeClass("menu-open");
                        $("fieldset#signin_menu").hide();
                    }
                });
				//dades agafat de http://test.guifi.net/guifi/cnml/1/home
                //$.get('<?php echo base_path().$directory; ?>/home.xml', function(data){
                $.get('/guifi/cnml/1/home', function(data){
                //$.get('http://guifi.net/guifi/cnml/1/home', function(data){
                  $('#total_actius').html($(data).find('total_working_nodes').attr('nodes'));
                  $('#nombre_total_links').html($(data).find('total_links').attr('num'));
                  $('#km_conexions').html($(data).find('total_links').attr('kms'));
                  $('#nodes_creats_ultima_setmana').html($(data).find('nodes_last_week').attr('total_nodes'));
                  $('#nodes_operatius_ultima_setmana').html($(data).find('nodes_last_week').attr('working_nodes'));
                });
				//serveis agafats de http://test.guifi.net/guifi/cnml/2/home
                //$.get(Drupal.settings.basePath+'guifi/cnml/2/home', function(data){
                $.get('/guifi/cnml/2/home', function(data){
                //$.get('http://guifi.net/guifi/cnml/2/home', function(data){
                  $(data).find('service').each(function(){
					  var servei = $(this);
					  if (servei.attr('type') == 'Proxy') $('#num_proxys').html(servei.attr('total'));
					  if (servei.attr('type') == 'ADSL') $('#Adsl').html(servei.attr('total'));
					  if (servei.attr('type') == 'asterisk') $('#centrals_VoIP').html(servei.attr('total'));
					  if (servei.attr('type') == 'ftp') $('#fileservers').html(servei.attr('total'));
					  if (servei.attr('type') == 'IM') $('#IM').html(servei.attr('total'));
					  if (servei.attr('type') == 'irc') $('#irc').html(servei.attr('total'));
					  if (servei.attr('type') == 'Streaming') $('#videoconferencia').html(servei.attr('total'));
					  if (servei.attr('type') == 'radio') $('#radio').html(servei.attr('total'));
					  if (servei.attr('type') == 'web') $('#web_servers').html(servei.attr('total'));
					  if (servei.attr('type') == 'mail') $('#mail_servers').html(servei.attr('total'));
				  });
                });
				// apadrinaments agafats de http://test.guifi.net/budgets/3671/cnml/short/Open
                //$.get('<?php echo base_path().$directory; ?>/apadrinaments.xml', function(data){
                $.get('/budgets/3671/cnml/short/Open', function(data){
                  var funded = 0;
                  var amount = 0;
				  $(data).find('budget').each(function(){
					  funded = funded + eval($(this).attr('funded'));
					  amount = amount + eval($(this).attr('amount'));				  
				  });
				  var total = parseInt((funded*100)/amount)+'%';
				  $('#percent_apadrinament').html(total);
				  $('#percent_apadrinament').css('width',total);
                });
            });