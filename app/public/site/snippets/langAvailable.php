<?php 
$c = array('fr' => 'en', 'en' => 'fr');
$ownLang = (string)$page->content()->language();
if ($ownLang == '') {
  $ownLang = $site->defaultLanguage()->code();
}
$otherLang = (string)$page->content($c[$ownLang])->language();
if ($otherLang == '') {
  $otherLang = $site->defaultLanguage()->code();
}
if ($ownLang != $otherLang) {
  $p = $page->content($c[$ownLang]);
  ?>
  <div class="article-utils article-utils--footer">
    <p class="u-right"><?php echo l::get('article.langAvailable') . ' <a href="' . $page->url($c[$ownLang]) .'">[' . strtoupper($c[$ownLang]) . '] ' . $p->title() . '</a>'; ?></p>
  </div>
  <?php
}
?>