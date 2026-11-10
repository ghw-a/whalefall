<?php
/**
 * 鲸落 · 侧栏
 *
 * 本文件只输出侧栏内部的小工具，容器 <aside class="sidebar"> 由各模板提供。
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
?>
  <div class="card widget reveal">
    <h3 class="widget__title"><span class="dot"></span>搜索这片海域</h3>
    <form class="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
      <span class="search__icon"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
      <input type="search" id="sidebarSearchInput" name="s" placeholder="输入关键字，捞一捞…" aria-label="搜索文章" required>
    </form>
  </div>

  <div class="card widget reveal">
    <h3 class="widget__title"><svg class="ico" aria-hidden="true"><use href="#i-fire"/></svg><?php echo htmlspecialchars(dswOpt('hotWidgetTitle'), ENT_QUOTES, 'UTF-8'); ?></h3>
    <div class="hot-list">
      <?php
      $dswRecent = \Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=5');
      $dswNum = 0;
      while ($dswRecent->next()):
          $dswNum++;
      ?>
      <a class="hot" href="<?php $dswRecent->permalink(); ?>">
        <span class="hot__num"><?php echo $dswNum; ?></span>
        <span><?php $dswRecent->title(); ?></span>
      </a>
      <?php endwhile; ?>
      <?php if (0 === $dswNum): ?>
      <p class="search-hint">这里还没有文章喵。</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="card widget reveal">
    <h3 class="widget__title"><span class="dot"></span>文章分类</h3>
    <ul class="cat-list">
      <?php
      $dswCats = \Typecho\Widget::widget('Widget_Metas_Category_List');
      while ($dswCats->next()):
      ?>
      <li>
        <a href="<?php $dswCats->permalink(); ?>">
          <i class="cat-dot" aria-hidden="true"></i><?php $dswCats->name(); ?>
          <span class="cat-count"><?php $dswCats->count(); ?></span>
        </a>
      </li>
      <?php endwhile; ?>
    </ul>
  </div>

  <?php
  $dswTags = \Typecho\Widget::widget('Widget_Metas_Tag_Cloud', 'sort=count&desc=1&limit=20');
  $dswHasTags = false;
  ob_start();
  while ($dswTags->next()) {
      $dswHasTags = true;
      ?>
      <a class="chip chip--tag" href="<?php $dswTags->permalink(); ?>"><?php $dswTags->name(); ?></a>
      <?php
  }
  $dswTagHtml = ob_get_clean();
  ?>
  <?php if ($dswHasTags): ?>
  <div class="card widget reveal">
    <h3 class="widget__title"><span class="dot"></span>标签云</h3>
    <div class="tag-cloud"><?php echo $dswTagHtml; ?></div>
  </div>
  <?php endif; ?>

  <div class="card widget reveal">
    <h3 class="widget__title"><span class="dot"></span>站点信息</h3>
    <div class="site-info">
      <div><span>文章</span><b><?php echo dswSiteStat('posts'); ?> 篇</b></div>
      <div><span>评论</span><b><?php echo dswSiteStat('comments'); ?> 条</b></div>
      <div><span>已运行</span><b><?php echo number_format(dswSiteStat('days')); ?> 天</b></div>
      <div><span>最后更新</span><b><?php echo dswTimeAgo((int)dswSiteStat('lastUpdate')); ?></b></div>
    </div>
  </div>