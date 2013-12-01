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

       <?php
         // UGLY HACK: allow chat and translor in FrontPage.
         $result = db_query("SELECT s.status FROM {system} s WHERE s.name = '%s' AND s.type = '%s'", 'drupalchat', 'module');
         while ($row = db_fetch_object($result)) {
           if ($row->status == 1 ) {
            ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_path().drupal_get_path('module', 'drupalchat'); ?>/themes/light/light.css" />
            <?php
            }
          }
       ?>
       <?php $result = db_query("SELECT s.status FROM {system} s WHERE s.name = '%s' AND s.type = '%s'", 'l10n_client', 'module');
         while ($row = db_fetch_object($result)) {
           if ($row->status == 1 ) {
            ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_path().drupal_get_path('module', 'l10n_client'); ?>/l10n_client.css" />
            <?php
            }
          }
        // end HACK
       ?>

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
        <script type="text/javascript" src="<?php echo base_path().$directory; ?>/js/portada-guifi.js"></script>
    </head>
    <body>
        <div id="guifi-sites">
            <ul id="topnav" class="topnav">
                <li class="leftnav"><a href="<?php print base_path() ?>" class="home" title="Inici"><img src="<?php echo base_path().$directory; ?>/img/guifi_favicon.jpg" height="16" width="16" border="0" alt="Guifi.net" align="middle"/></a></li>
                <li class="leftnav"><a href="/portada"><?php echo t('Old Homepage'); ?></a></li>
                <?php
					global $user;
					if ($user->uid) {
				?>
          <li class="leftnav expanded"><a href="#"><?php echo t('My account'); ?></a>
                	<ul>
                    	<li><a href="/user/<?php print $user->uid; ?>"><?php echo t('My profile'); ?></a></li>
                        <li><a href="/meus-nodes/<?php print $user->uid; ?>"><?php echo t('My nodes'); ?></a></li>
                        <li><a href="/blog/<?php print $user->uid; ?>"><?php echo t('My blog'); ?></a></li>
                        <li><a href="/user/<?php print $user->uid; ?>/track"><?php echo t('My content'); ?></a></li>
                    </ul>
                </li>
			</ul>
			<ul class="topnav">
                <?php print i18nmenu_translated_tree('menu-crear-continguts'); ?>
                <?php
					}
				?>
			</ul>
			<?php print i18nmenu_translated_tree('menu-barra-guifi'); ?>
        	<?php
				global $user;
				if (!$user->uid) {
			?>
			<ul>
				<li><a href="#" class="signin" title="Entra"><span><?php echo t('Login'); ?> / <?php echo t('Register'); ?></span></a></li>
			</ul>
                <fieldset id="signin_menu">
				<?php print drupal_get_form('user_login');?>
                	<p class="forgot"><a href="/user/register" class="vincle"><?php echo t('Register'); ?></a></p>
                    <p class="forgot"><a href="/user/password" id="resend_password_link" class="vincle"><?php echo t('Request new password'); ?></a></p>
				</fieldset>
				<?php
				}
				?>
        </div>
        <div id="pagina">
            <div id="principal">
				<div id="header">
                    <h1><a href="<?php print base_path() ?>" title="Guifi.net" id="logo"><img src="<?php echo base_path().$directory; ?>/img/guifi-logo.png" alt="Guifi.net" title="Guifi.net" /></a></h1>
                    <?php
                    	if ($mission) {
							print '<h2><a href="'.base_path().'" title="Guifi.net" id="slogan">';
            				print $mission;
							print '</a></h2>';
						}
					?>
                    <div id="eines">
                    	<?php if ($search_box): ?><?php print $search_box ?><?php endif; ?>
                        <form name="llengua" id="llengua" action="">
                            <label for="language-select-list"><?php echo t('Languages'); ?></label>
                        	<?php draw_language_selection(); ?>
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
                	<?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
          			<?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
                    <?php
						$destacat = module_invoke('views', 'block', 'view', 'destacataHome-block_1');
						$destacatok = 0;
						if ($destacat) {
							$destacatok = 1;
							$tabcontent1 = "";
						} else {
							$tabcontent1 = " active";
						}
					?>
                    <div class="capsa" id="central">
                        <div class="tabbed_area capsa">
                            <ul class="tabs">
                            	<?php
                                	if ($destacatok == 1){
										print '<li><a href="#" title="content_0" class="tab active" >'.t('Highlight').'</a></li>';
									}
								?>
                                <li><a href="#" title="content_1" class="tab<?php print $tabcontent1; ?>" >Guifi.net <?php echo t('Today'); ?></a></li>
                                <li><a href="#" title="content_2" class="tab"><?php echo t('Services'); ?></a></li>
                                <li><a href="#" title="content_3" class="tab"><?php echo t('Recent posts'); ?></a></li>
                                <li><a href="#" title="content_4" class="tab"><?php echo t('New forum topics'); ?></a></li>
                                <!--<li><a href="#" title="content_4" class="tab">Agenda</a></li>-->
                            </ul>
                            <?php
                            	if ($destacatok == 1){
									print '<div id="content_0" class="content">'.$destacat['content'].'</div>';
									print '<div id="content_1" class="content" style="display:none;">';
								} else {
									print '<div id="content_1" class="content">';
								}
							?>
                                <img src="/guifi/cnml/1/plot" height="150" width="200" style="float:right" alt="creixement de la xarxa"/>
                                <!-- //http://guifi.net/guifi/stats/chart?id=0&width=200&height=190&title=Nodes operatius -->
                                <h3 style="width:50%"><span id='total_actius' class="gran"></span> <?php echo t('working nodes'); ?></h3>
                                <ul style="width:50%">
                                	<li><strong id='nombre_total_links'></strong> <?php echo t('links'); ?></li>
                                	<li><strong id='km_conexions'></strong> <?php echo t('total kilometers of links'); ?></li>
                                	<li><strong id='nodes_creats_ultima_setmana'></strong> <?php echo t('last week new nodes'); ?></li>
                                	<li><strong id='nodes_operatius_ultima_setmana'></strong> <?php echo t('working nodes last week'); ?></li>
                                </ul>
                                <p style="clear:both;"><a class="vincle" href="/guifi/menu/stats/nodes"><?php echo t('more info'); ?></a></p>
                            </div>
                            <div id="content_2" class="content">
                                <ul>
                                	<li><?php echo t('Total internet Gateways'); ?>: <?php echo t('<strong id="Adsl"></strong> Direct Gateways and <strong id="num_proxys"></strong> Proxys'); ?></li>
                                    <li><?php echo t('VoiceIP Servers'); ?>: <strong id='centrals_VoIP'></strong></li>
                                    <li><?php echo t('FTP or shared disk server'); ?>: <strong id='fileservers'></strong></li>
                                    <li><?php echo t('Instant Message Servers'); ?>: <?php echo t('<strong id="IM"></strong> jabber and <strong id="irc"></strong> irc'); ?></li>
                                    <li><?php echo t('Videoconference Servers'); ?>: <strong id='videoconferencia'></strong></li>
                                    <li><?php echo t('Web server'); ?>: <strong id='web_servers'></strong></li>
                                    <li><?php echo t('Radio broadcast'); ?> (<?php echo t('music'); ?>): <strong id='radio'></strong></li>
                                    <li><?php echo t('Mail server'); ?>: <strong id='mail_servers'></strong></li>
                                </ul>
                                <p><a class="vincle" href="/node/3671/view/services"><?php echo t('All services'); ?></a></p>
                            </div>
                            <?php print $actualitat_guifi; ?>
                            <?php print $actualitat_foros; ?>
                        </div>
                        <div class="quadre gris capsa" id="fundacio">
                            <h3><?php echo t('Guifi.net Foundation'); ?></h3>
                            <a class="vincle" href="http://fundacio.guifi.net/"><?php echo t('What is the Foundation?'); ?></a>
                            <!-- //<a class="vincle" href="http://fundacio.guifi.net/fundacio/02_ami/ami.html"><?php echo t('Become a friend!'); ?></a> -->
                            <a class="vincle" href="http://blogs.guifi.net/fundacio/guifi-net-voluntariat/"><?php echo t('Become a volunteer!'); ?></a>
                        </div>
                        <div class="quadre blanc" id="apadrinaments">
                            <h3><?php echo t('Sponsorships'); ?></h3>
                            <p><?php echo t('The netwoork grows and is maintained thanks to sponshorships'); ?></p>
                            <div class="graph">
                                <strong id='percent_apadrinament' class="bar" style="width: 74%;">74%</strong>
                            </div>
                            <a class="vincle" href="/budgets/3671/list/Open"><?php echo t('Sponsor a node!'); ?></a>
                        </div>
                    </div>
                    <div class="capsa" id="patrocinadors">
                        <!--<h3>Hi donen suport</h3>-->
                        <ul class="slider" id="slider1">
                            <li><a class="left slider-arrow" id="left1" href="#"></a></li>
                            <li class="view">
                                <?php
									//$block = module_invoke('views', 'block', 'view', 'logoshome-block_1');
									//print $block['content'];
								?>
                                <ul class="images">
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/RIPE.jpg" width="135" height="43" alt="Membres de RIPE NCC member" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/EULivingLabs.png" width="50" height="50" alt="European Network of Living Labs" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/openspectrum.png" width="141" height="45" alt="Openspectrum.eu" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/CATNIX_mini.gif" width="75" height="45" alt="Membres del Catnix" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/premis_nacionals.gif" width="135" height="39" alt="Premi Nacional de Telecomunicacions 2007 concedit a Guifi.net" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/citilab.jpg" width="62" height="47" alt="Citilab Cornella" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/fundacio_punt_cat.gif" width="90" height="44" alt="Fundacio Punt.cat" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/hangar_org.gif" width="102" height="43" alt="Fundacio Punt.cat" /></li>
                                    <li><img src="<?php echo base_path().$directory; ?>/img/patrocinadors/sant_bartomeu_del_grau.gif" width="31" height="45" alt="Ajuntament de Sant Bartomeu del Grau" /></li>
                                </ul>
                            </li>
                            <li><a class="right slider-arrow" id="right1" href="#"></a></li>
                        </ul>
                        <!--<p><a href="#" class="vincle">Suports i padrins</a></p>-->
                    </div>
                </div>
                <div id="peu" class="quatre columnes" style="margin-top: -40px; z-index: 10">
                    <?php print $sitemap; ?>
                </div>
                <div id="sotapeu">
                	<?php print $footer_message . $footer ?>
                </div>
            </div>
        </div>
        <?php print $closure ?>
    </body>
</html>
