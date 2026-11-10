<?php
/**
 * 鲸落 · 404
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>

<section class="banner" style="min-height:min(76vh,640px)">
  <span class="banner__glow banner__glow--a"></span>
  <span class="banner__glow banner__glow--b"></span>
  <span class="banner__glow banner__glow--c"></span>

  <div class="banner__inner">
    <p class="error-code" aria-hidden="true">404</p>
    <h1 class="banner__title">这片海域没有内容喵</h1>
    <p class="banner__sub">你要找的那一页，可能被潮水卷走了，或者从来没有存在过。</p>

    <div class="banner__btns">
      <a class="btn btn--primary" href="<?php $this->options->siteUrl(); ?>">回到首页 <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
      <a class="btn btn--ghost" href="<?php echo htmlspecialchars(dswArchivesUrl($this), ENT_QUOTES, 'UTF-8'); ?>">看看全部文章</a>
    </div>

    <form class="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" style="max-width:400px;margin:26px auto 0">
      <span class="search__icon"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
      <input type="search" name="s" placeholder="搜点什么，也许还能捞回来…" aria-label="搜索文章">
    </form>
  </div>

  <div class="banner__waves" aria-hidden="true">
    <svg class="wave wave--back" viewBox="0 0 2880 200" preserveAspectRatio="none">
      <path d="M0,80 C240,25 480,135 720,80 C960,25 1200,135 1440,80 C1680,25 1920,135 2160,80 C2400,25 2640,135 2880,80 L2880,200 L0,200 Z"></path>
    </svg>
    <svg class="wave wave--mid" viewBox="0 0 2880 200" preserveAspectRatio="none">
      <path d="M0,115 C240,158 480,72 720,115 C960,158 1200,72 1440,115 C1680,158 1920,72 2160,115 C2400,158 2640,72 2880,115 L2880,200 L0,200 Z"></path>
    </svg>
    <svg class="wave wave--front" viewBox="0 0 2880 200" preserveAspectRatio="none">
      <path d="M0,155 C240,132 480,178 720,155 C960,132 1200,178 1440,155 C1680,132 1920,178 2160,155 C2400,132 2640,178 2880,155 L2880,200 L0,200 Z"></path>
    </svg>
  </div>
</section>

<div class="main-wrap" style="margin-top:-6px">
  <div class="wrap">
    <div class="card widget reveal" style="max-width:520px;margin:0 auto 40px;text-align:center">
      <h3 class="widget__title" style="justify-content:center">要不，去这些地方逛逛？</h3>
      <div class="tag-cloud" style="justify-content:center">
        <a class="chip chip--tag" href="<?php echo htmlspecialchars(dswArchivesUrl($this), ENT_QUOTES, 'UTF-8'); ?>">全部文章</a>
        <a class="chip chip--tag" href="<?php $this->options->siteUrl(); ?>">首页</a>
        <a class="chip chip--tag" href="<?php $this->options->feedUrl(); ?>">RSS 订阅</a>
      </div>
    </div>
  </div>
</div>

<?php $this->need('footer.php'); ?>