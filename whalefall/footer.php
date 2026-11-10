<?php
/**
 * 鲸落 · 页脚
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$dswSocials = dswSocials();
$dswIcp = trim((string)dswOptOr('icpCode'));
$dswIcpLink = trim((string)dswOptOr('icpLink'));
?>
  <!-- ========== 页脚 ========== -->
  <footer class="footer">
    <span class="footer__line"></span>

    <?php if ($dswSocials): ?>
    <div class="footer__socials">
      <?php foreach ($dswSocials as $dswS): ?>
      <a class="social" href="<?php echo htmlspecialchars($dswS['url'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($dswS['label'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo strpos($dswS['url'], 'mailto:') === 0 ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>>
        <svg class="ico" aria-hidden="true"><use href="#<?php echo $dswS['icon']; ?>"/></svg>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <p class="footer__brand">🐋 <?php $this->options->title(); ?></p>
    <p class="footer__text"><?php echo htmlspecialchars((string)dswOpt('footerText'), ENT_QUOTES, 'UTF-8'); ?> · 由 <a href="https://typecho.org" target="_blank" rel="noopener noreferrer">Typecho</a> 强力驱动</p>
    <p class="footer__text">© <?php echo date('Y'); ?> <?php $this->options->title(); ?> · 保留所有鱼肉</p>

    <?php if ($dswIcp): ?>
    <p class="footer__icp">
      <?php if ($dswIcpLink): ?><a href="<?php echo htmlspecialchars($dswIcpLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
      <?php echo htmlspecialchars($dswIcp, ENT_QUOTES, 'UTF-8'); ?>
      <?php if ($dswIcpLink): ?></a><?php endif; ?>
    </p>
    <?php endif; ?>

    <div class="footer__links">
      <a href="<?php $this->options->feedUrl(); ?>">RSS</a>
      <a href="<?php $this->options->siteUrl(); ?>">首页</a>
      <a href="<?php echo htmlspecialchars(dswArchivesUrl($this), ENT_QUOTES, 'UTF-8'); ?>">归档</a>
    </div>
  </footer>

  <!-- 浮动工具栏 -->
  <div class="fab">
    <button class="fab__btn" type="button" id="fabPaletteBtn" title="切换配色" aria-label="切换配色"><svg class="ico" aria-hidden="true"><use href="#i-palette"/></svg></button>
    <button class="fab__btn" type="button" id="fabThemeBtn" title="切换主题" aria-label="切换主题"><svg class="ico" id="fabThemeIcon" aria-hidden="true"><use href="#i-moon"/></svg></button>
    <button class="fab__btn" type="button" id="topBtn" title="回到顶部" aria-label="回到顶部"><svg class="ico" aria-hidden="true"><use href="#i-arrow-up"/></svg></button>
  </div>

  <script>
    window.DSW = {
      typing: <?php echo json_encode(array_values(dswTypingLines()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>
    };
  </script>
  <script src="<?php $this->options->themeUrl('app.js'); ?>?v=<?php echo DSW_VERSION; ?>"></script>

  <?php
  $dswAnalytics = (string)dswOptOr('analytics');
  if ('' !== trim($dswAnalytics)) {
      echo $dswAnalytics;
  }
  ?>
</body>
</html>