<?php 

if ($page->translation()->value() != '') {
  $text = $page->translation()->value();
  $tr = explode(' ', $text);
  ?>
  <div class="article-utils">
    <p class="u-right"><?php echo l::get('article.langAvailable') . ' <a href="' . $tr[1] .'">[' . $tr[0] . ']</a>'; ?></p>
  </div>
  <?php
} else {

$c = array('fr' => 'en', 'en' => 'fr');
$ownLang = (string)$page->content()->language();
if ($ownLang == '') {
  $ownLang = $site->defaultLanguage()->code();
}
$otherLang = (string)$page->content($c[$ownLang])->language();
if ($otherLang == '') {
  $otherLang = $site->defaultLanguage()->code();
}
if ($ownLang != $otherLang ) {
  $p = $page->content($c[$ownLang]);
  if ((time() - strtotime($p->date())) > 0) {
  ?>
  <div class="article-utils">
    <p class="u-right"><?php echo l::get('article.langAvailable') . ' <a href="' . $page->url($c[$ownLang]) .'">[' . strtoupper($c[$ownLang]) . '] ' . $p->title() . '</a>'; ?></p>
  </div>
  <?php
  }
}

}
?>