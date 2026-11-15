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

  <?php
  /* ---------- 音乐播放器 ----------
   * 歌单走 GD音乐台，由主题的 PHP 接口代取（functions.php 2.6 节）：
   * 浏览器不直连第三方，文件缓存顺便守住「5 分钟 50 次」的限流。
   */
  $dswMusicCfg = null;

  if ('1' === dswOpt('musicOn')) {
      $dswMusicMode = 'search' === (string)dswOpt('musicMode') ? 'search' : 'fixed';
      $dswMusicList = 'fixed' === $dswMusicMode ? dswMusicPlaylist() : [];

      /* 固定歌单读不到文件时，别让播放器空着——退回关键词搜索 */
      if ('fixed' === $dswMusicMode && !$dswMusicList) {
          $dswMusicMode = 'search';
      }

      $dswMusicCfg = [
          'endpoint' => (string)$this->permalink(),
          'mode'     => $dswMusicMode,
          'list'     => $dswMusicList,
          'keyword'  => trim((string)dswOpt('gdKeyword')),
          'source'   => trim((string)dswOpt('gdSource')),
          'count'    => max(1, min(50, (int)dswOpt('gdCount'))),
          'volume'   => max(0, min(1, (float)dswOpt('musicVolume'))),
      ];
  }

  if ($dswMusicCfg):
  ?>
  <!-- 音乐播放器：收起是一颗音符，点开向左展开小卡片 -->
  <div class="player" id="player">
    <div class="player__card" id="playerCard" aria-hidden="true">
      <div class="player__head">
        <div class="player__cover" id="pCover"><svg class="ico" aria-hidden="true"><use href="#i-music"/></svg></div>
        <div class="player__meta">
          <p class="player__title" id="pTitle">还没开始漂</p>
          <p class="player__artist" id="pArtist">鲸落 · 海域电台</p>
        </div>
        <button class="player__min" type="button" id="pMin" aria-label="收起播放器">✕</button>
      </div>

      <div class="player__progress" id="pProgress" role="slider" tabindex="0" aria-label="播放进度" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span class="player__bar" id="pBar"></span></div>
      <div class="player__time"><span id="pCur">00:00</span><span id="pDur">00:00</span></div>

      <div class="player__ctrl">
        <button class="pbtn" type="button" id="pPrev" aria-label="上一首"><svg class="ico" aria-hidden="true"><use href="#i-prev"/></svg></button>
        <button class="pbtn pbtn--play" type="button" id="pPlay" aria-label="播放"><svg class="ico" aria-hidden="true"><use id="pPlayIco" href="#i-play-solid"/></svg></button>
        <button class="pbtn" type="button" id="pNext" aria-label="下一首"><svg class="ico" aria-hidden="true"><use href="#i-next"/></svg></button>
        <button class="pbtn" type="button" id="pRepeat" aria-label="列表循环" title="列表循环" aria-pressed="false"><svg class="ico" aria-hidden="true"><use href="#i-repeat"/></svg></button>
        <button class="pbtn" type="button" id="pMute" aria-label="静音" aria-pressed="false"><svg class="ico" aria-hidden="true"><use href="#i-volume"/></svg></button>
        <button class="pbtn" type="button" id="pListBtn" aria-label="展开歌单" title="歌单" aria-expanded="false"><svg class="ico" aria-hidden="true"><use href="#i-list"/></svg></button>
      </div>

      <div class="player__list" id="pList" aria-label="歌单"></div>
      <p class="player__powered">音乐来自 <a href="https://music.gdstudio.xyz" target="_blank" rel="noopener nofollow">GD音乐台</a></p>
    </div>

    <button class="player__btn" type="button" id="playerBtn" aria-label="打开音乐播放器" aria-expanded="false"><svg class="ico" aria-hidden="true"><use href="#i-music"/></svg></button>
  </div>
  <audio id="playerAudio" preload="none"></audio>

  <script>
    window.DSW_MUSIC = <?php echo json_encode($dswMusicCfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?>;
  </script>
  <?php endif; ?>

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