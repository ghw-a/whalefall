<?php
/**
 * 鲸落 · 搜索结果
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');

$dswRawKeyword = (string)($this->archiveKeywords ?? '');
if ('' === $dswRawKeyword) {
    $dswRawKeyword = (string)$this->request->get('keywords', '');
}
if ('' === $dswRawKeyword) {
    $dswRawKeyword = (string)$this->request->get('s', '');
}
$dswRawKeyword = rawurldecode($dswRawKeyword);
$dswKeyword = htmlspecialchars($dswRawKeyword, ENT_QUOTES, 'UTF-8');
$dswTotal = (int)$this->getTotal();
?>

<header class="post-hero archive-hero">
  <span class="post-hero__glow post-hero__glow--a"></span>
  <span class="post-hero__glow post-hero__glow--b"></span>

  <div class="post-hero__inner">
    <nav class="crumb" aria-label="面包屑">
      <a href="<?php $this->options->siteUrl(); ?>">首页</a>
      <span class="sep" aria-hidden="true">/</span>
      <span>搜索</span>
    </nav>

    <h1 class="post-hero__title"><?php if ('' !== $dswKeyword): ?>"<?php echo $dswKeyword; ?>" 的搜索结果<?php else: ?>搜索这片海域<?php endif; ?></h1>

    <div class="archive-hero__meta">共找到 <?php echo $dswTotal; ?> 篇文章</div>

    <form class="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" style="max-width:420px;margin:22px auto 0">
      <span class="search__icon"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
      <input type="search" name="s" value="<?php echo $dswKeyword; ?>" placeholder="换个词，再捞一捞…" aria-label="搜索文章">
    </form>
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
          <div class="empty-state__icon">🫧</div>
          <p class="empty-state__title">这片海域没有内容喵</p>
          <p class="empty-state__text"><?php if ('' !== $dswKeyword): ?>没有找到和「<?php echo $dswKeyword; ?>」相关的文章，换个关键词试试？<?php else: ?>试着搜一个关键词，本鲸鱼娘帮你捞上来。<?php endif; ?></p>
          <div class="empty-state__actions">
            <a class="btn btn--primary" href="<?php $this->options->siteUrl(); ?>">回到首页 <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
            <a class="btn btn--ghost" href="<?php echo htmlspecialchars(dswArchivesUrl($this), ENT_QUOTES, 'UTF-8'); ?>">浏览全部文章</a>
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