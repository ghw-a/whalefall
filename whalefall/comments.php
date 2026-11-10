<?php
/**
 * 鲸落 · 评论
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->comments()->to($comments);
$dswCommentCount = 0;
$dswHasComments = $comments->have();
?>
<h3 class="widget__title">
  <svg class="ico" aria-hidden="true"><use href="#i-comment"/></svg><?php $this->commentsNum('还没有评论', '1 条评论', '%d 条评论'); ?>
</h3>

<?php if ($dswHasComments): ?>
  <?php while ($comments->next()): ?>
  <?php
    $dswLevel = (int)($comments->levels ?? 0);
    $dswAvatarClass = (int)($comments->coid % 5) + 1;
    $dswInitial = mb_substr((string)$comments->author, 0, 1, 'UTF-8');
  ?>
  <div class="comment<?php echo $dswLevel > 0 ? ' comment--reply' : ''; ?>" id="<?php $comments->theId(); ?>">
    <div class="c-avatar c-avatar--<?php echo $dswAvatarClass; ?>"><?php echo htmlspecialchars($dswInitial, ENT_QUOTES, 'UTF-8'); ?></div>
    <div>
      <div class="c-head">
        <span class="c-name"><?php $comments->author(); ?></span>
        <span class="c-time"><?php $comments->date('Y-m-d H:i'); ?></span>
      </div>
      <div class="c-text"><?php $comments->content(); ?></div>
      <?php if ($this->allow('comment')): ?>
      <div class="comment__actions"><?php $comments->reply('回复'); ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php endwhile; ?>

  <?php $comments->pageNav('‹', '›', 2, '…'); ?>
<?php else: ?>
  <p class="search-hint">还没有评论，来坐第一排喵～</p>
<?php endif; ?>

<?php if ($this->allow('comment')): ?>
<div class="respond" id="<?php $this->respondId(); ?>">
  <h3 class="respond__title">
    <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg>写点什么
    <?php $comments->cancelReply('· 取消回复'); ?>
  </h3>

  <?php if ($this->user->hasLogin()): ?>
  <p class="c-form__user">
    登录身份：<a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
    · <a href="<?php $this->options->logoutUrl(); ?>">退出</a>
  </p>
  <?php endif; ?>

  <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form" class="c-form">
    <?php if (!$this->user->hasLogin()): ?>
    <div class="c-form__row">
      <label class="c-field">
        <span>称呼<em class="req">*</em></span>
        <input class="c-input" type="text" name="author" id="author" value="<?php $this->remember('author'); ?>" autocomplete="nickname" required>
      </label>
      <label class="c-field">
        <span>邮箱<?php if ($this->options->commentsRequireMail): ?><em class="req">*</em><?php endif; ?></span>
        <input class="c-input" type="email" name="mail" id="mail" value="<?php $this->remember('mail'); ?>" autocomplete="email"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
      </label>
      <label class="c-field">
        <span>网站<?php if ($this->options->commentsRequireUrl): ?><em class="req">*</em><?php endif; ?></span>
        <input class="c-input" type="url" name="url" id="url" value="<?php $this->remember('url'); ?>" placeholder="https://" autocomplete="url"<?php if ($this->options->commentsRequireUrl): ?> required<?php endif; ?>>
      </label>
    </div>
    <?php endif; ?>

    <label class="c-field">
      <span>内容<em class="req">*</em></span>
      <textarea class="c-textarea" name="text" id="textarea" rows="6" placeholder="说点什么都好，别把海水弄浑了喵～" required><?php $this->remember('text'); ?></textarea>
    </label>

    <div class="c-form__foot">
      <span class="c-form__hint">评论前请先想清楚，浪花会记住每一句话。</span>
      <button type="submit" class="btn btn--primary">发射评论 <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></button>
    </div>
  </form>
</div>
<?php endif; ?>