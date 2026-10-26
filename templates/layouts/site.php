<!doctype html>
<html lang="<?= $locale ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta ?>
  <link rel="stylesheet" href="<?= $this->asset('assets/site.css') ?>">
</head>
<body>
<?php
// The language switcher remembers the choice, then shows this page in the other language.
$other = $locale === 'en' ? 'fr' : 'en';
$to = isset($page) ? $this->url($this->raw('page'), ['_locale' => $other]) : '/' . $other;
?>
  <nav>
    <a href="<?= $this->url('home') ?>"><?= $this->t('nav.home') ?></a>
    <a href="<?= $this->url('about') ?>"><?= $this->t('nav.about') ?></a>
    <a class="language" lang="<?= $other ?>" href="<?= $this->url('locale.switch', ['locale' => $other], ['to' => $to]) ?>"><?= $this->t('nav.other_language') ?></a>
  </nav>
  <main><?= $this->section('content') ?></main>
</body>
</html>
