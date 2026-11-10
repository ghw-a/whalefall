<?php
/**
 * 鲸落 · 页头
 *
 * @package whalefall
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$dswPalette = (string)dswOpt('defaultPalette');
if (!in_array($dswPalette, ['ocean', 'mint', 'forest', 'rose'], true)) {
    $dswPalette = 'ocean';
}

$dswMode = (string)dswOpt('defaultMode');
$dswInitTheme = 'dark' === $dswMode ? 'dark' : 'light';
$dswTyping = dswTypingLines();
$dswIcon = dswAvatarUrl();
?><!DOCTYPE html>
<html lang="zh-CN" class="no-js" data-theme="<?php echo $dswInitTheme; ?>" data-palette="<?php echo $dswPalette; ?>">
<head>
  <meta charset="<?php $this->options->charset(); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php $this->archiveTitle([
      'category' => _t('分类 %s 中的文章'),
      'search'   => _t('搜索 %s 的结果'),
      'tag'      => _t('标签 %s 中的文章'),
      'author'   => _t('作者 %s 的文章'),
  ], '', ' - '); ?><?php $this->options->title(); ?></title>

  <?php $this->header(); ?>

  <link rel="icon" href="<?php $this->options->themeUrl('assets/icon.svg'); ?>" type="image/svg+xml">

  <!-- 圆体字体（加载失败自动回退系统字体，不影响布局） -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;500;700;800;900&family=ZCOOL+KuaiLe&family=Noto+Sans+SC:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- 提前应用主题与配色，避免刷新闪白（必须在 CSS 之前） -->
  <script>
    (function () {
      var def = '<?php echo $dswMode; ?>';
      try {
        var saved = localStorage.getItem('whale-theme');
        var dark;
        if (saved === 'dark' || saved === 'light') {
          dark = saved === 'dark';
        } else if (def === 'dark') {
          dark = true;
        } else if (def === 'light') {
          dark = false;
        } else {
          dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        document.documentElement.dataset.theme = dark ? 'dark' : 'light';

        var palette = localStorage.getItem('whale-palette');
        if (palette) document.documentElement.dataset.palette = palette;
      } catch (e) { /* 隐私模式下忽略 */ }
    })();
  </script>

  <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>?v=<?php echo DSW_VERSION; ?>">
</head>
<body>

  <!-- ========== 图标库（lucide 风格线描，全站复用） ========== -->
  <svg style="display:none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
    <symbol id="i-folder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></symbol>
    <symbol id="i-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0l-7.2-7.2A2 2 0 0 1 3 12V5a2 2 0 0 1 2-2h7a2 2 0 0 1 1.4.6l7.2 7.2a2 2 0 0 1 0 2.6z"/><circle cx="7.5" cy="7.5" r="1.2"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></symbol>
    <symbol id="i-archive" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="5" rx="1"/><path d="M4 9v10a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9M10 13h4"/></symbol>
    <symbol id="i-github" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.5 2 2 6.6 2 12.2c0 4.5 2.9 8.3 6.8 9.6.5.1.7-.2.7-.5v-1.7c-2.8.6-3.4-1.4-3.4-1.4-.5-1.2-1.1-1.5-1.1-1.5-.9-.6.1-.6.1-.6 1 .1 1.5 1 1.5 1 .9 1.6 2.4 1.1 3 .9.1-.7.4-1.1.6-1.4-2.2-.3-4.6-1.1-4.6-5 0-1.1.4-2 1-2.7-.1-.3-.4-1.3.1-2.7 0 0 .8-.3 2.7 1a9.3 9.3 0 0 1 5 0c1.9-1.3 2.7-1 2.7-1 .5 1.4.2 2.4.1 2.7.6.7 1 1.6 1 2.7 0 3.9-2.3 4.7-4.6 5 .4.3.7.9.7 1.9v2.8c0 .3.2.6.7.5 3.9-1.3 6.8-5.1 6.8-9.6C22 6.6 17.5 2 12 2z"/></symbol>
    <symbol id="i-rss" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1.6" fill="currentColor" stroke="none"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></symbol>
    <symbol id="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></symbol>
    <symbol id="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A8.5 8.5 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-chev-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></symbol>
    <symbol id="i-chev-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></symbol>
    <symbol id="i-chev-d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-comment" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 8.9 8.9 0 0 1-4-.9L3 21l1.9-4.9A8.4 8.4 0 0 1 4 11.5 8.4 8.4 0 0 1 12.5 3 8.4 8.4 0 0 1 21 11.5z"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l8.8 8.6 8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-fire" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4 0 7-2.7 7-6.5 0-4.5-4.5-6-4.5-9.5 0-1.2.4-2.3 1-3-4 .5-8.5 4-8.5 9 0 1.3.4 2.4 1 3.3-1.5.4-2.5 1.7-2.5 3.2C5.5 20 8 22 12 22z"/></symbol>
    <symbol id="i-home" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></symbol>
    <symbol id="i-image" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2 8 8l-4 1 3 3-1 4 4-1 3 3 1-4 6-6z"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-list" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></symbol>
    <symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5"/></symbol>
    <symbol id="i-palette" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".6" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".6" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".6" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".6" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.9 0 1.6-.7 1.6-1.6 0-.4-.2-.8-.4-1.1-.2-.3-.4-.7-.4-1.1 0-.9.7-1.6 1.6-1.6H16c3.3 0 6-2.7 6-6 0-5.5-4.5-9.6-10-9.6z"/></symbol>
    <symbol id="i-coffee" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4z"/><path d="M6 2v2M10 2v2M14 2v2"/></symbol>
    <symbol id="i-code" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/></symbol>
    <symbol id="i-headphones" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></symbol>
    <symbol id="i-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="4"/><path d="m10 9 5 3-5 3z" fill="currentColor"/></symbol>
  </svg>

  <!-- ========== 顶部导航 ========== -->
  <header class="nav" id="nav">
    <div class="nav__inner">
      <a class="brand" href="<?php $this->options->siteUrl(); ?>">
        <span class="brand__logo" aria-hidden="true"><?php if ($dswIcon): ?><img src="<?php echo htmlspecialchars($dswIcon, ENT_QUOTES, 'UTF-8'); ?>" alt=""><?php else: ?>🐋<?php endif; ?></span>
        <span class="brand__name"><?php $this->options->title(); ?></span>
      </a>

      <nav class="menu" id="menu" aria-label="主导航">
        <?php foreach (dswMenuItems($this) as $dswItem): ?>
        <a href="<?php echo htmlspecialchars($dswItem['url'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $dswItem['active'] ? ' class="is-active" aria-current="page"' : ''; ?>>
          <svg class="ico" aria-hidden="true"><use href="#<?php echo $dswItem['icon']; ?>"/></svg><?php echo htmlspecialchars($dswItem['label'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
        <?php endforeach; ?>
      </nav>

      <div class="nav__actions">
        <button class="icon-btn" type="button" id="navSearchBtn" title="搜索" aria-label="搜索"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></button>
        <button class="icon-btn" type="button" id="paletteBtn" title="切换配色" aria-label="切换配色"><svg class="ico" aria-hidden="true"><use href="#i-palette"/></svg></button>
        <button class="icon-btn" type="button" id="navThemeBtn" title="切换主题" aria-label="切换主题" aria-pressed="false"><svg class="ico" id="navThemeIcon" aria-hidden="true"><use href="#i-moon"/></svg></button>
        <?php $dswSocials = dswSocials(); foreach ($dswSocials as $dswS): if ('i-github' !== $dswS['icon']) { continue; } ?>
        <a class="icon-btn" href="<?php echo htmlspecialchars($dswS['url'], ENT_QUOTES, 'UTF-8'); ?>" title="GitHub" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><svg class="ico" aria-hidden="true"><use href="#i-github"/></svg></a>
        <?php endforeach; ?>
        <button class="icon-btn menu-toggle" type="button" id="menuToggle" aria-label="菜单" aria-expanded="false" aria-controls="menu">☰</button>
      </div>
    </div>
  </header>