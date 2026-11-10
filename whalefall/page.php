<?php
/**
 * 鲸落 · 独立页面
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>

<header class="post-hero">
  <span class="post-hero__glow post-hero__glow--a"></span>
  <span class="post-hero__glow post-hero__glow--b"></span>

  <div class="post-hero__inner">
    <nav class="crumb" aria-label="面包屑">
      <a href="<?php $this->options->siteUrl(); ?>">首页</a>
      <span class="sep" aria-hidden="true">/</span>
      <span><?php $this->title(); ?></span>
    </nav>

    <h1 class="post-hero__title"><?php $this->title(); ?></h1>
  </div>

  <svg class="post-hero__waves" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
    <path class="wave-back" d="M0,40 C300,72 520,10 760,36 C980,60 1160,16 1440,44 L1440,80 L0,80 Z"></path>
    <path class="wave-front" d="M0,56 C260,38 480,70 720,58 C960,46 1180,72 1440,56 L1440,80 L0,80 Z"></path>
  </svg>
</header>

<div class="main-wrap">
  <div class="wrap layout layout--page">

    <main>
      <article class="card article-card">
        <div class="prose">
          <?php $this->content(); ?>
        </div>
      </article>

      <?php if (dswCommentsEnabled() && $this->allow('comment')): ?>
      <section class="card comments" id="comments">
        <?php $this->need('comments.php'); ?>
      </section>
      <?php endif; ?>
    </main>

    <aside class="sidebar">
      <?php $this->need('sidebar.php'); ?>
    </aside>
  </div>
</div>

<?php $this->need('footer.php'); ?>