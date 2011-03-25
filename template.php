<?php
// $Id: template.php,v 1.16 2007/10/11 09:51:29 goba Exp $

/**
 * Sets the body-tag class attribute.
 *
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */
function phptemplate_body_class($left, $right) {
  if ($left != '' && $right != '') {
    $class = 'sidebars';
  }
  else {
    if ($left != '') {
      $class = 'sidebar-left';
    }
    if ($right != '') {
      $class = 'sidebar-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function phptemplate_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    return '<div class="breadcrumb">'. implode(' › ', $breadcrumb) .'</div>';
  }
}

/**
 * Allow themable wrapping of all comments.
 */
function phptemplate_comment_wrapper($content, $node) {
  if (!$content || $node->type == 'forum') {
    return '<div id="comments">'. $content .'</div>';
  }
  else {
    return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'</div>';
  }
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
//  print_r($vars);
  $vars['primary_links']   = _opensourcery_primary_links($vars['primary_links']);
  $vars['secondary_links'] = _opensourcery_secondary_links($vars['secondary_links']);

  $vars['tabs2'] = menu_secondary_local_tasks();

  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
  // Render the slideshow view for homepage
  $vars['embedded_slideshow'] = views_embed_view('homeslideshow', 'block_1');
  //Reload the javascript into the scripts due to a problem with slideshow.
  $vars['scripts'] = drupal_get_js();
}

function _opensourcery_primary_links($primary) {
  global $language;

  foreach ($primary as $lid => $link) {
    $link = os_translate_translate_path($link);
    $primary[$lid] = $link;
  }
  return $primary;
}

function _opensourcery_secondary_links($secondary) {
  global $language;

  // This function call will rebuild the secondary menu as if the page were in
  // English, thus solving the second issue.
  if ($language->language == 'ca') {
    $secondary = _opensourcery_rebuild_secondary_links();
  }

  foreach ($secondary as $lid => $link) {
    $link = os_translate_translate_path($link);
    $secondary[$lid] = $link;
  }
  return $secondary;
}

/**
 * Translate a link array.
 */
function os_translate_translate_path($link) {
  global $language;
  // get a list of all available paths
  $new_paths = translation_path_get_translations($link['href']);
  if ($new_paths[$language->language]) {
    // if a translated path exists, set it here
    $link['href'] = $new_paths[$language->language];
  }

  // translate the title (this adds every menu title to the locale_source
  // table, for later translation
  $link['title'] = t($link['title']);
  if ($link['attributes']['title']) {
    $link['attributes']['title'] = t($link['attributes']['title']);
  }

  return $link;
}

function _opensourcery_rebuild_secondary_links() {
  // menus are built in English, so set active trail there
  $new_paths = translation_path_get_translations($_GET['q']);

  // save current path
  $current = $_GET['q'];

  if ($new_paths['en']) {
    menu_set_active_item($new_paths['en']);
  }

  $secondary_links = menu_secondary_links();

  // reset active item
  menu_set_active_item($current);

  return $secondary_links;
}
/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
  if (defined('LANGUAGE_RTL') && $language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}

/**
 * el nou disseny de la web utilitza les seguents funcions
 * creades per Fernando Graells basades en posts i forums de drupal
 */



/*
 * Theme function to return dropdown html (based on lang_dropdown.module)
 */
function draw_language_selection() {
  /* This code is almost from locale.module with a bit of modifications
   * Instead of themeing it using theme('links') we'll theme our own dropdown
   */ 
  global $language;
  
  //$path = drupal_is_front_page() ? '<front>' : $_GET['q'];
  $path = $_GET['q'];
  $languages = language_list('enabled');
  $options = array();
  foreach ($languages[1] as $lang_item) {
    $options[$lang_item->language] = array(
      'href'       => $path,
      'title'      => $lang_item->native,
      'language'   => $lang_item,
    );
  }
  drupal_alter('translation_link', $options, $path);
  
  /* Here we theme our own dropdown */
  $output = "<select id=\"language-select-list\" name=\"language-select-list\" onchange=\"document.location.href=this.options[this.selectedIndex].value;\">\n";
  foreach ($options as $lang_option) {
    if ($language->language == $lang_option['language']->language) {
      $selected = ' selected="selected"';
    } else { $selected = ''; }
    $path = check_url(url($lang_option['href'], array('language' => $lang_option['language'])));
    $output .= '<option value="' . $path . '"' . $selected . '>' . $lang_option['title'] . '</option>' . "\n";
  }
  
  $output .= "</select>\n" . $languageicon;
  print $output;
}


/**
* Override or insert PHPTemplate variables into the search_theme_form template.
*
* @param $vars
*   A sequential array of variables to pass to the theme template.
* @param $hook
*   The name of the theme function being called (not used in this case.)
*/
function phptemplate_preprocess_search_theme_form(&$vars, $hook) {
  // Remove the "Search this site" label from the form.
  //print_r($vars);
  unset($vars['form']['search_theme_form']['#title']);
  //$vars['form']['search_theme_form']['#title'] = t('');
 
  // Set a default value for text inside the search box field.
  //$vars['form']['search_theme_form']['#value'] = t('Search this Site');
  $vars['form']['search_theme_form']['#value'] = t('Search');
 
  // Add a custom class and placeholder text to the search box.
  $vars['form']['search_theme_form']['#attributes'] = array('class' => 'NormalTextBox txtSearch', 'onblur' => "if (this.value == '') {this.value = '".$vars['form']['search_block_form']['#value']."';} ;", 'onfocus' => "if (this.value == '".$vars['form']['search_theme_form']['#value']."') {this.value = '';} ;" );

 
  // Change the text on the submit button
  //$vars['form']['submit']['#value'] = t('Go');

  // Rebuild the rendered version (search form only, rest remains unchanged)
  unset($vars['form']['search_theme_form']['#printed']);
  $vars['search']['search_theme_form'] = drupal_render($vars['form']['search_theme_form']);

  $vars['form']['submit']['#type'] = 'button';
  $vars['form']['submit']['#id'] = 'searchguifi';
   
  // Rebuild the rendered version (submit button, rest remains unchanged)
  unset($vars['form']['submit']['#printed']);
  $vars['search']['submit'] = drupal_render($vars['form']['submit']);

  // Collect all form elements to make it easier to print the whole form.
  $vars['search_form'] = implode($vars['search']);
}

function phptemplate_aggregator_block_item($item, $feed = 0) {
// Display the feed teaser link to the item.
	$output .= '<a href="' . check_url($item->link) . '" class="rsswidget" title="'.check_plain($item->description).'">' . check_plain($item->title) . "</a>\n";

return $output;
}

function phptemplate_more_link($url, $title) {
  return '<p>' . t('<a href="@link" title="@title" class="vincle">més</a>', array('@link' => check_url($url), '@title' => $title)) . '</p>';
}

/**
* Implementation of hook_theme.
*
* Register custom theme functions.
*/
function guifi_theme_theme() {
  return array(
    // The form ID.
    'user_login' => array(
      // Forms always take the form argument.
      'arguments' => array('form' => NULL),
    ),
  );
}

/*
FORM OVERRIDES
*/

/**
* Theme override for user login block.
*
* The function is named themename_formid.
*/
function guifi_theme_user_login($form) {
  // Add your overrides here.
  $form['name']['#title'] = 'Nom d\'usuari'; //wrap any text in a t function
  $form['pass']['#title'] = 'Contrasenya';
  unset($form['name']['#description']); //remove links under fields
  unset($form['pass']['#description']); //remove links under fields
  print_r($form);
  return (drupal_render($form));
}