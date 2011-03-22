<?php
// $Id: block-sitemap.tpl.php,v 1.0 2011/01/15 08:39:36 Fernando Graells Exp $
?>
<div  class="columna">
<?php if (!empty($block->subject)): ?>
<span class="titolpeu"><?php print $block->subject ?></span>
<?php endif;?>
<?php print $block->content ?>
</div>
