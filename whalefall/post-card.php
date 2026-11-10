<?php
/**
 * 鲸落 · 单篇文章卡（在 while ($this->next()) 循环里使用）
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$dswThumb = dswPostThumb($this);
$dswCover = coverColor($this);
$dswPin = '1' === (string)($this->fields->pin ?? '');
?>
<article class="card post-card reveal">
  <div class="post-card__body">
    <h2 class="post-card__title">
      <a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
    </h2>
    <div class="post-card__meta">
      <span><svg class="ico" aria-hidden="true"><use href="#i-calendar"/></svg><?php $this->date('Y-m-d'); ?></span>
      <?php if (dswFlag('showReadTime')): ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-book"/></svg><?php echo dswFormatCount(postWordCount($this)); ?> 字 · <?php echo postReadTime($this); ?> 分钟</span>
      <?php endif; ?>
      <?php $dswViews = getPostViews($this); if ('' !== $dswViews): ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-eye"/></svg><?php echo $dswViews; ?></span>
      <?php endif; ?>
    </div>
    <p class="post-card__desc"><?php $this->excerpt(96, '…'); ?></p>
    <div class="post-card__tags">
      <?php foreach ($this->categories as $dswCat): ?>
      <a class="chip chip--cat" href="<?php echo htmlspecialchars($dswCat['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dswCat['name'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endforeach; ?>
      <?php foreach ($this->tags as $dswTag): ?>
      <a class="chip chip--tag" href="<?php echo htmlspecialchars($dswTag['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dswTag['name'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <a class="post-card__cover<?php echo '' !== $dswThumb ? ' has-img' : ''; ?>" href="<?php $this->permalink(); ?>" style="--c1:<?php echo $dswCover['c1']; ?>;--c2:<?php echo $dswCover['c2']; ?>" tabindex="-1" aria-hidden="true">
    <?php if ('' !== $dswThumb): ?>
    <img class="cover-img" src="<?php echo htmlspecialchars($dswThumb, ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy" decoding="async">
    <?php else: ?>
    <svg class="cover-icon"><use href="#<?php echo $dswCover['icon']; ?>"/></svg>
    <?php endif; ?>
    <?php if ($dswPin): ?><span class="pin"><svg class="ico" aria-hidden="true"><use href="#i-pin"/></svg>置顶</span><?php endif; ?>
    <i class="cover-arrow"><svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></i>
  </a>
</article>