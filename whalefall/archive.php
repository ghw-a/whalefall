<?php
/**
 * 鲸落 · 分类 / 标签 / 日期归档
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>

<header class="post-hero archive-hero">
  <span class="post-hero__glow post-hero__glow--a"></span>
  <span class="post-hero__glow post-hero__glow--b"></span>

  <div class="post-hero__inner">
    <nav class="crumb" aria-label="面包屑">
      <a href="<?php $this->options->siteUrl(); ?>">首页</a>
      <span class="sep" aria-hidden="true">/</span>
      <span>归档</span>
    </nav>

    <h1 class="post-hero__title"><?php $this->archiveTitle([
        'category' => _t('分类：%s'),
        'search'   => _t('搜索：%s'),
        'tag'      => _t('标签：%s'),
        'author'   => _t('作者：%s'),
    ], '', ''); ?></h1>

    <div class="archive-hero__meta">
      <?php if ($this->is('date')): ?>
      按时间归档
      <?php endif; ?>
      共 <?php echo (int)$this->getTotal(); ?> 篇文章
    </div>
  </div>

  <svg class="post-hero__waves" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
    <path class="wave-back" d="M0,40 C300,72 520,10 760,36 C980,60 1160,16 1440,44 L1440,80 L0,80 Z"></path>
    <path class="wave-front" d="M0,56 C260,38 480,70 720,58 C960,46 1180,72 1440,56 L1440,80 L0,80 Z"></path>
  </svg>
</header>

<div class="main-wrap">
  <div class="wrap layout">

    <main class="content">
      <div class="posts">
        <?php if ($this->have()): ?>
        <?php while ($this->next()): ?>
        <?php $this->need('post-card.php'); ?>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="empty-state">
          <div class="empty-state__icon">🐚</div>
          <p class="empty-state__title">这个角落还没有文章</p>
          <p class="empty-state__text">换个分类逛逛，或者回首页看看新的浪花。</p>
          <div class="empty-state__actions">
            <a class="btn btn--primary" href="<?php $this->options->siteUrl(); ?>">回到首页 <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <?php $this->pageNav('‹', '›', 2, '…'); ?>
    </main>

    <aside class="sidebar">
      <?php $this->need('sidebar.php'); ?>
    </aside>
  </div>
</div>

<?php $this->need('footer.php'); ?>