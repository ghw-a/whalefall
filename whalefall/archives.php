<?php
/**
 * 文章归档
 *
 * 在后台「撰写 → 独立页面」里选择本模板，即可得到按年份分组的全部文章列表。
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');

/* 独立实例：别和侧栏的「最近文章」共用同一个 widget 池（侧栏要 pageSize=5） */
$dswList = \Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=999', null, false);
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
    <div class="archive-hero__meta">共 <?php echo (int)dswSiteStat('posts'); ?> 篇文章</div>
  </div>

  <svg class="post-hero__waves" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
    <path class="wave-back" d="M0,40 C300,72 520,10 760,36 C980,60 1160,16 1440,44 L1440,80 L0,80 Z"></path>
    <path class="wave-front" d="M0,56 C260,38 480,70 720,58 C960,46 1180,72 1440,56 L1440,80 L0,80 Z"></path>
  </svg>
</header>

<div class="main-wrap">
  <div class="wrap layout">

    <main>
      <article class="card article-card">
        <?php if ($this->content): ?>
        <div class="prose" style="margin-bottom:26px"><?php $this->content(); ?></div>
        <?php endif; ?>

        <div class="archive-list-wrap">
          <?php
          $dswYear = null;
          $dswHas = false;
          while ($dswList->next()):
              $dswHas = true;
              $dswThisYear = date('Y', (int)$dswList->created);
              if ($dswThisYear !== $dswYear) {
                  if (null !== $dswYear) {
                      echo '</ul>';
                  }
                  $dswYear = $dswThisYear;
                  echo '<h3 class="archive-list__year">' . $dswYear . ' 年</h3><ul class="archive-list">';
              }
          ?>
          <li class="archive-list__item">
            <span class="archive-list__date"><?php $dswList->date('m-d'); ?></span>
            <a class="archive-list__title" href="<?php $dswList->permalink(); ?>"><?php $dswList->title(); ?></a>
          </li>
          <?php endwhile; ?>
          <?php if (null !== $dswYear) { echo '</ul>'; } ?>
          <?php if (!$dswHas): ?>
          <div class="empty-state">
            <div class="empty-state__icon">🐋</div>
            <p class="empty-state__title">还没有文章</p>
          </div>
          <?php endif; ?>
        </div>
      </article>
    </main>

    <aside class="sidebar">
      <?php $this->need('sidebar.php'); ?>
    </aside>
  </div>
</div>

<?php $this->need('footer.php'); ?>