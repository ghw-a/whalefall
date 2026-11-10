<?php
/**
 * 鲸落 · 首页
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');

$dswAvatar = dswAvatarUrl();
$dswNickname = (string)dswOpt('nickname');
$dswBio = (string)dswOpt('bio');

// 有「归档」独立页面就链过去，没有则退回首页
$dswArchiveUrl = dswArchivesUrl($this);
?>

<!-- ========== Banner ========== -->
<section class="banner">
  <?php
  $dswPhotoApis = trim((string)dswOpt('bannerPhotoApis'));
  if ('' !== $dswPhotoApis && dswFlag('bannerPhotoOn')):
      $dswPhotoOpacity = max(0.05, min(0.9, (float)dswOpt('bannerPhotoOpacity')));
  ?>
  <div class="banner__photo" id="bannerPhoto"
       data-apis="<?php echo htmlspecialchars($dswPhotoApis, ENT_QUOTES, 'UTF-8'); ?>"
       style="--photo-opacity:<?php echo number_format($dswPhotoOpacity, 2, '.', ''); ?>"
       aria-hidden="true"></div>
  <?php endif; ?>
  <span class="banner__glow banner__glow--a"></span>
  <span class="banner__glow banner__glow--b"></span>
  <span class="banner__glow banner__glow--c"></span>
  <div class="banner__bubbles" id="bubbles"></div>

  <div class="banner__inner">
    <?php $dswBadge = trim((string)dswOpt('bannerBadge')); ?>
    <?php if ('' !== $dswBadge): ?>
    <span class="banner__badge"><?php echo htmlspecialchars($dswBadge, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>

    <h1 class="banner__title"><?php echo dswBannerTitle((string)dswOpt('bannerTitle')); ?></h1>

    <p class="banner__sub">
      <span id="typing"></span><span class="caret" aria-hidden="true"></span>
    </p>

    <div class="banner__btns">
      <?php $dswBtnA = trim((string)dswOpt('bannerBtnA')); ?>
      <?php if ('' !== $dswBtnA): ?>
      <a class="btn btn--primary" href="<?php echo htmlspecialchars((string)dswOptOr('bannerBtnAUrl', '#posts'), ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($dswBtnA, ENT_QUOTES, 'UTF-8'); ?>
        <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg>
      </a>
      <?php endif; ?>

      <?php $dswBtnB = trim((string)dswOpt('bannerBtnB')); ?>
      <?php if ('' !== $dswBtnB): ?>
      <a class="btn btn--ghost" href="<?php echo htmlspecialchars(dswOptOr('bannerBtnBUrl', $dswArchiveUrl), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dswBtnB, ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endif; ?>
    </div>
  </div>

  <?php if (dswFlag('showScrollHint')): ?>
  <div class="banner__scroll" aria-hidden="true">
    <span>SCROLL</span>
    <svg class="ico"><use href="#i-chev-d"/></svg>
  </div>
  <?php endif; ?>

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

<!-- ========== 主体 ========== -->
<div class="main-wrap">
  <div class="wrap layout">

    <main class="content" id="posts">

      <!-- 横版资料卡 -->
      <div class="card profile profile--row reveal">
        <div class="avatar-ring">
          <div class="avatar"><?php if ($dswAvatar): ?><img src="<?php echo htmlspecialchars($dswAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy"><?php else: ?>🐳<?php endif; ?></div>
        </div>
        <div class="profile__body">
          <h3 class="profile__name"><?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?></h3>
          <p class="profile__bio"><?php echo htmlspecialchars($dswBio, ENT_QUOTES, 'UTF-8'); ?></p>
          <div class="profile__stats">
            <div class="stat"><strong><?php echo dswSiteStat('posts'); ?></strong><span>文章</span></div>
            <div class="stat"><strong><?php echo dswSiteStat('categories'); ?></strong><span>分类</span></div>
            <div class="stat"><strong><?php echo dswSiteStat('tags'); ?></strong><span>标签</span></div>
          </div>
        </div>
        <?php $dswSocials = dswSocials(); if ($dswSocials): ?>
        <div class="socials">
          <?php foreach ($dswSocials as $dswS): ?>
          <a class="social" href="<?php echo htmlspecialchars($dswS['url'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($dswS['label'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo strpos($dswS['url'], 'mailto:') === 0 ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>>
            <svg class="ico" aria-hidden="true"><use href="#<?php echo $dswS['icon']; ?>"/></svg>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <?php if (dswFlag('showQuickNav')): ?>
      <!-- 四格快捷导航 -->
      <div class="quick reveal">
        <a class="quick__item" href="<?php echo htmlspecialchars($dswArchiveUrl, ENT_QUOTES, 'UTF-8'); ?>">
          <span class="quick__ico"><svg class="ico ico--lg" aria-hidden="true"><use href="#i-archive"/></svg></span>
          <span class="quick__label">归档</span>
          <span class="quick__sub">按时间浏览</span>
        </a>
        <a class="quick__item" href="<?php echo htmlspecialchars(dswFirstCategoryUrl($this), ENT_QUOTES, 'UTF-8'); ?>">
          <span class="quick__ico"><svg class="ico ico--lg" aria-hidden="true"><use href="#i-folder"/></svg></span>
          <span class="quick__label">分类</span>
          <span class="quick__sub">按主题浏览</span>
        </a>
        <a class="quick__item" href="<?php echo htmlspecialchars(dswFirstTagUrl($this), ENT_QUOTES, 'UTF-8'); ?>">
          <span class="quick__ico"><svg class="ico ico--lg" aria-hidden="true"><use href="#i-tag"/></svg></span>
          <span class="quick__label">标签</span>
          <span class="quick__sub">按关键词找</span>
        </a>
        <a class="quick__item" href="#sidebarSearchInput" data-focus-search>
          <span class="quick__ico"><svg class="ico ico--lg" aria-hidden="true"><use href="#i-search"/></svg></span>
          <span class="quick__label">搜索</span>
          <span class="quick__sub">全文检索</span>
        </a>
      </div>
      <?php endif; ?>

      <div class="section-head">
        <div>
          <h2>最新文章</h2>
          <p>把生活写成文章，把文章泡进海水</p>
        </div>
        <a class="more" href="<?php echo htmlspecialchars($dswArchiveUrl, ENT_QUOTES, 'UTF-8'); ?>">全部文章 <svg class="ico" aria-hidden="true"><use href="#i-chev-r"/></svg></a>
      </div>

      <div class="posts">
        <?php if ($this->have()): ?>
        <?php while ($this->next()): ?>
        <?php $this->need('post-card.php'); ?>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="empty-state">
          <div class="empty-state__icon">🐋</div>
          <p class="empty-state__title">这片海域还很安静</p>
          <p class="empty-state__text">还没有发布文章，本鲸鱼娘正在海底慢慢写。</p>
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