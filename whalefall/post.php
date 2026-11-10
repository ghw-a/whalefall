<?php
/**
 * 鲸落 · 文章页
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');

// 取出正文并生成目录（标题没有 id 的会自动补 sec-n）
ob_start();
$this->content();
$dswRawContent = ob_get_clean();
list($dswContent, $dswToc) = buildToc($dswRawContent);

$dswAvatar = dswAvatarUrl();
$dswNickname = (string)dswOpt('nickname');
$dswBio = (string)dswOpt('bio');

$dswPrimaryCat = null;
foreach ($this->categories as $dswCat) {
    $dswPrimaryCat = $dswCat;
    break;
}
?>

<header class="post-hero">
  <span class="post-hero__glow post-hero__glow--a"></span>
  <span class="post-hero__glow post-hero__glow--b"></span>

  <div class="post-hero__inner">
    <nav class="crumb" aria-label="面包屑">
      <a href="<?php $this->options->siteUrl(); ?>">首页</a>
      <?php if ($dswPrimaryCat): ?>
      <span class="sep" aria-hidden="true">/</span>
      <a href="<?php echo htmlspecialchars($dswPrimaryCat['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dswPrimaryCat['name'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endif; ?>
      <span class="sep" aria-hidden="true">/</span>
      <span>正文</span>
    </nav>

    <h1 class="post-hero__title"><?php $this->title(); ?></h1>

    <div class="post-hero__meta">
      <span><svg class="ico" aria-hidden="true"><use href="#i-calendar"/></svg><?php $this->date('Y-m-d'); ?></span>
      <?php if ($dswPrimaryCat): ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-folder"/></svg><?php echo htmlspecialchars($dswPrimaryCat['name'], ENT_QUOTES, 'UTF-8'); ?></span>
      <?php endif; ?>
      <?php if (dswFlag('showReadTime')): ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg>约 <?php echo postReadTime($this); ?> 分钟 · <?php echo dswFormatCount(postWordCount($this)); ?> 字</span>
      <?php endif; ?>
      <?php $dswViews = getPostViews($this); if ('' !== $dswViews): ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-eye"/></svg><?php echo $dswViews; ?> 次阅读</span>
      <?php endif; ?>
      <span><svg class="ico" aria-hidden="true"><use href="#i-comment"/></svg><a href="#comments"><?php $this->commentsNum('暂无评论', '1 条评论', '%d 条评论'); ?></a></span>
    </div>
  </div>

  <svg class="post-hero__waves" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
    <path class="wave-back" d="M0,40 C300,72 520,10 760,36 C980,60 1160,16 1440,44 L1440,80 L0,80 Z"></path>
    <path class="wave-front" d="M0,56 C260,38 480,70 720,58 C960,46 1180,72 1440,56 L1440,80 L0,80 Z"></path>
  </svg>
</header>

<div class="main-wrap">
  <div class="wrap layout layout--post">

    <main>
      <article class="card article-card">
        <div class="prose">
          <?php echo $dswContent; ?>
        </div>

        <div class="share-row">
          <div class="post-card__tags">
            <?php foreach ($this->tags as $dswTag): ?>
            <a class="chip chip--tag" href="<?php echo htmlspecialchars($dswTag['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dswTag['name'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
          </div>
          <div class="share-row__btns">
            <?php
            $dswLikes = dswLikes((int)$this->cid);
            /* 服务端直接按 cookie 渲染已赞态，刷新时不会先闪一下「未赞」 */
            $dswLiked = !empty($_COOKIE['dsw_liked_' . (int)$this->cid]);
            ?>
            <button class="share-btn like-btn<?php echo $dswLiked ? ' is-liked' : ''; ?>" type="button"
                    data-like-cid="<?php echo (int)$this->cid; ?>"
                    data-like-endpoint="<?php $this->permalink(); ?>"
                    aria-pressed="<?php echo $dswLiked ? 'true' : 'false'; ?>"
                    aria-label="喜欢这篇文章（当前 <?php echo $dswLikes; ?> 个喜欢）">
              <svg class="ico" aria-hidden="true"><use href="#i-heart"/></svg>
              <span class="like-btn__txt"><?php echo $dswLiked ? '已喜欢' : '喜欢'; ?></span>
              <span class="like-btn__num"><?php echo dswFormatCount($dswLikes); ?></span>
            </button>
            <button class="share-btn" type="button" data-copy-url="<?php $this->permalink(); ?>">
              <svg class="ico" aria-hidden="true"><use href="#i-link"/></svg><span class="share-btn__txt">复制链接</span>
            </button>
          </div>
        </div>
      </article>

      <div class="author-box card">
        <div class="avatar"><?php if ($dswAvatar): ?><img src="<?php echo htmlspecialchars($dswAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy"><?php else: ?>🐳<?php endif; ?></div>
        <div>
          <h4><?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?></h4>
          <p><?php echo htmlspecialchars($dswBio, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      </div>

      <nav class="postnav" aria-label="文章导航">
        <div class="postnav__col postnav__col--prev">
          <?php $this->thePrev('%s', '<span class="postnav__empty">已经是第一篇了</span>'); ?>
        </div>
        <div class="postnav__col postnav__col--next">
          <?php $this->theNext('%s', '<span class="postnav__empty">已经是最后一篇了</span>'); ?>
        </div>
      </nav>

      <?php if (dswCommentsEnabled() && $this->allow('comment')): ?>
      <section class="card comments" id="comments">
        <?php $this->need('comments.php'); ?>
      </section>
      <?php endif; ?>
    </main>

    <aside class="sidebar">
      <?php if (dswFlag('showToc') && count($dswToc) >= 2): ?>
      <div class="card widget toc reveal">
        <h4><svg class="ico" aria-hidden="true"><use href="#i-list"/></svg>目录</h4>
        <?php foreach ($dswToc as $dswSec): ?>
        <a href="#<?php echo htmlspecialchars($dswSec['id'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo 2 === (int)$dswSec['level'] ? '' : ' class="toc--sub"'; ?>><?php echo htmlspecialchars($dswSec['text'], ENT_QUOTES, 'UTF-8'); ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php $this->need('sidebar.php'); ?>
    </aside>
  </div>
</div>

<?php $this->need('footer.php'); ?>