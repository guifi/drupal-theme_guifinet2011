<?php
// $Id: page.tpl.php,v 1.18 2008/01/24 09:42:53 goba Exp $
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
    <head>
        <title><?php print $head_title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_path().$directory; ?>/css/portada.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_path().$directory; ?>/css/guifi.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_path().$directory; ?>/css/barra_guifi.css" />
        <?php print $scripts ?>
        <script type="text/javascript" src="<?php echo base_path().$directory; ?>/js/jquery.scrollShow.js"></script>
        <script type="text/javascript" src="<?php echo base_path().$directory; ?>/js/jquery.scrollTo-min.js"></script>
        <script type="text/javascript">
            jQuery(function( $ ){
            //borrowed from jQuery easing plugin
            //http://gsgd.co.uk/sandbox/jquery.easing.php
                $.easing.backout = function(x, t, b, c, d){
                    var s=1.70158;
                    return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
                };
            });
        </script>
        <script type="text/javascript">
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
                $.get('http://test.guifi.net/guifi/cnml/1/home', function(data){
                  $('#total_actius').html($(data).find('total_working_nodes').attr('nodes'));
                  $('#nombre_total_links').html($(data).find('total_links').attr('num'));
                  $('#km_conexions').html($(data).find('total_links').attr('kms'));
                  $('#nodes_creats_ultima_setmana').html($(data).find('nodes_last_week').attr('total_nodes'));
                  $('#nodes_operatius_ultima_setmana').html($(data).find('nodes_last_week').attr('working_nodes'));
                });
				//serveis agafats de http://test.guifi.net/guifi/cnml/2/home
                //$.get('<?php echo base_path().$directory; ?>/serveis.xml', function(data){
                $.get('http://test.guifi.net/guifi/cnml/2/home', function(data){
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
                $.get('http://test.guifi.net/budgets/3671/cnml/short/Open', function(data){
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
            function MM_jumpIdioma(targ,selObj,restore){ //v3.0
                eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
                if (restore) selObj.selectedIndex=0;
            }
        </script>
    </head>
    <body>
        <div id="guifi-sites">
            <ul id="topnav" class="topnav">
                <li class="leftnav"><a href="<?php print base_path() ?>" class="home" title="Inicio">Inicio</a></li>
                <li class="leftnav"><a href="/portada">Antigua portada</a></li>
                <?php
					global $user;
					if ($user->uid) {
				?>
                <li class="leftnav expanded"><a href="#">Mi cuenta</a>
                	<ul>
                    	<li><a href="/user/<?php print $user->uid; ?>">Mi perfil</a></li>
                        <li><a href="/meus-nodes/<?php print $user->uid; ?>">Mis nodos</a></li>
                        <li><a href="/blog/<?php print $user->uid; ?>">Mi blog</a></li>
                        <li><a href="/user/<?php print $user->uid; ?>/track">Mis publicaciones</a></li>
                    </ul>                
                </li>
			</ul>
			<ul class="topnav">
                <?php
					print menu_tree_output(menu_tree_all_data('menu-crear-continguts'));
				?>
                <?php
					}
				?>
			</ul>
			<?php //print theme('links', menu_navigation_links('menu-barra-guifi'), array('class' => 'links primary-links')); ?>
			<?php print menu_tree_output(menu_tree_all_data('menu-barra-guifi')); ?>
			<?php //print menu_tree_output(menu_tree_all_data('navigation')); ?>
			<?php //print theme('links', $primary_links, array('class' => 'topnav')); ?>
        		<?php
					global $user;
					if (!$user->uid) {
				?>
			<ul>
				<li><a href="#" class="signin" title="Entra"><span>Entra / Regístrate</span></a></li>
			</ul>
                <fieldset id="signin_menu">
				<?php print drupal_get_form('user_login');?>
                	<p class="forgot"><a href="/user/register" class="vincle">Regístrate</a></p>
                    <p class="forgot"><a href="/user/password" id="resend_password_link" class="vincle">Pide una contraseña nueva</a></p>
				</fieldset>
				<?php
				}
				?>
        </div>
        <div id="pagina">
            <div id="principal">
				<div id="header">
                    <h1><a href="<?php print base_path() ?>" title="Guifi.net" id="logo"><img src="<?php echo base_path().$directory; ?>/img/guifi-logo.png" alt="Guifi.net" title="Guifi.net" /></a></h1>
                    <h2><a href="<?php print base_path() ?>" title="Guifi.net" id="slogan">Red Abierta, Libre i Neutral<br />Internet para todos</a></h2>
                    <div id="eines">
                    	<?php if ($search_box): ?><?php print $search_box ?><?php endif; ?>
                        <form name="llengua" id="llengua" action="">
                            <label for="jumpIdioma"><?php echo t('Languages'); ?></label>
                            <select name="jumpIdioma" id="jumpIdioma" onchange="MM_jumpIdioma('parent',this,0)">
                        	<?php draw_language_selection(); ?>
                            </select>
                        </form>
                    </div>
                </div>
                <?php print $embedded_slideshow; //viene del template.php ?>
				<div id="cos">
                    <?php
                    	if (arg(2) == 'edit' || arg(2) == 'translate'){
							print $content;
						} else {
							print $node->content['body']['#value'];
						}
					?>
                    <div class="capsa" id="central">
                        <div class="tabbed_area capsa">
                            <ul class="tabs">
                                <li><a href="#" title="content_1" class="tab active" >Guifi.net Hoy</a></li>
                                <li><a href="#" title="content_2" class="tab">Servicios</a></li>
                                <li><a href="#" title="content_3" class="tab">Actualidad en guifi.net</a></li>
                                <li><a href="#" title="content_4" class="tab">Nuevo en los forums</a></li>
                                <!--<li><a href="#" title="content_4" class="tab">Agenda</a></li>-->
                            </ul>
                            <div id="content_1" class="content">
                                <img src="/guifi/cnml/1/plot" height="150" width="200" style="float:right" alt="crecimiento de la red"/>
                                <!-- //http://guifi.net/guifi/stats/chart?id=0&width=200&height=190&title=Nodes operatius -->
                                <h3 style="width:50%"><span id='total_actius' class="gran">11.177</span> nodos operativos.</h3>
                                <ul style="width:50%">
                                	<li><strong id='nombre_total_links'></strong> enlaces</li>
                                	<li><strong id='km_conexions'></strong> kilómetros de enlaces totales</li>
                                	<li><strong id='nodes_creats_ultima_setmana'></strong> nodos creados la última semana</li>
                                	<li><strong id='nodes_operatius_ultima_setmana'></strong> nodos pasados a operativos la última semana</li>
                                </ul>
                                <p style="clear:both;"><a class="vincle" href="/guifi/menu/stats/nodes">Más datos</a></p>
                            </div>
                            <div id="content_2" class="content">
                                <ul>
                                	<li>Número total de accesos a Internet: <strong id='Adsl'></strong> Accesos directos y <strong id='num_proxys'></strong> Proxys</li>
                                    <li>Centralitas de VozIP: <strong id='centrals_VoIP'></strong></li>
                                    <li>Servidores de archivos: <strong id='fileservers'></strong></li>
                                    <li>Servidores de Mensajería instantania: <strong id='IM'></strong> de jabber y <strong id='irc'></strong> de irc</li>
                                    <li>Servidores de Videoconferencia: <strong id='videoconferencia'></strong></li>
                                    <li>Servidores de webs: <strong id='web_servers'></strong></li>
                                    <li>Emisoras de Radio (música): <strong id='radio'></strong></li>
                                    <li>Servidores de correo: <strong id='mail_servers'></strong></li>
                                </ul>
                                <p><a class="vincle" href="/node/3671/view/services">Todos los servicios</a></p>
                            </div>
                            <?php print $actualitat_guifi; ?>
                            <?php print $actualitat_foros; ?>
                        </div>
                        <div class="quadre gris capsa" id="fundacio">
                            <h3>Fundación Guifi.net</h3>
                            <a class="vincle" href="http://fundacio.guifi.net/">Qué es la Fundación?</a>
                            <a class="vincle" href="http://fundacio.guifi.net/fundacio/02_ami/ami.html">Hazte amigo!</a>
                            <a class="vincle" href="http://fundacio.guifi.net/fundacio/03_vol/vol.html">Hazte voluntario!</a>
                        </div>
                        <div class="quadre blanc" id="apadrinaments">
                            <h3>Apadrinamientos</h3>
                            <p>El crecimiento y mantenimiento de la red se realiza gracias a los apadrinamientos</p>
                            <div class="graph">
                                <strong id='percent_apadrinament' class="bar" style="width: 74%;">74%</strong>
                            </div>
                            <a class="vincle" href="/budgets/3671/list/Open">Apadrina un nodo!</a>
                        </div>
                    </div>
                    <div class="capsa" id="patrocinadors">
                        <!--<h3>Dan su soporte</h3>-->
                        <ul class="slider" id="slider1">
                            <li><a class="left slider-arrow" id="left1" href="#"></a></li>
                            <li class="view">
                                <ul class="images">
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/RIPE.jpg" width="135" height="43" alt="Miembros de RIPE NCC member" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/EULivingLabs.png" width="50" height="50" alt="European Network of Living Labs" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/openspectrum.png" width="141" height="45" alt="Openspectrum.eu" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/CATNIX_mini.jpg" width="75" height="45" alt="Miembros del Catnix" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/premis_nacionals.gif" width="135" height="39" alt="Premio nacional de Telecomunicaciones 2007 concedido a Guifi.net" /></li>
                                </ul>
                            </li>
                            <li><a class="right slider-arrow" id="right1" href="#"></a></li>
                        </ul>
                        <p><a href="#" class="vincle">Soportes y padrinos</a></p>
                    </div>
                </div>
                <div id="peu" class="quatre columnes" style="margin-top: -40px; z-index: 10">
                    <?php print $sitemap; ?>
                </div>
                <div id="sotapeu">
                	&copy; Guifi.net - <a href="/es/avisolegal" title="Aviso Legal">Aviso Legal</a> - <a href="/node/feed"><img src="/misc/xml.png" alt="xml_logo" width="36" height="14" border="0"/></a>
                </div>
            </div>
        </div>
        <?php print $closure ?>
    </body>
</html>