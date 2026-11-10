<?php
/**
 * 鲸落 · 「关于」独立页面
 *
 * 命中规则：Typecho 1.3 的 Archive::getThemeFile() 会先找 `{archiveType}/{slug}.php`，
 * 所以本文件只对 slug = start-page 的独立页面生效，其它页面依旧走 page.php。
 * 改 slug 就失效，要改名的话记得同步改目录名。
 *
 * @package whalefall
 * @template 关于（深海名片）
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');

$dswNickname = trim((string)dswOpt('nickname'));
if ('' === $dswNickname) {
    $dswNickname = (string)$this->options->title;
}
$dswBio     = trim((string)dswOpt('bio'));
$dswAvatar  = dswAvatarUrl();

$dswMail   = '';
$dswGithub = '';
foreach (dswSocials() as $dswS) {
    if ('i-mail' === $dswS['icon'] && '' === $dswMail) {
        $dswMail = $dswS['url'];
    }
    if ('i-github' === $dswS['icon'] && '' === $dswGithub) {
        $dswGithub = $dswS['url'];
    }
}

/* 正文没写就显示模板自带的介绍，免得主人看到空白 */
$dswHasContent = '' !== trim(strip_tags((string)$this->text));
$dswTimeline   = dswAboutTimeline();
$dswLinks      = dswAboutLinks();
?>

<main class="about">

  <!-- ========== 深海名片 ========== -->
  <section class="about-hero">
    <div class="about-avatar">
      <?php if ($dswAvatar): ?>
      <img src="<?php echo htmlspecialchars($dswAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?>">
      <?php else: ?>
      <span class="about-avatar__fallback" aria-hidden="true">🐋</span>
      <?php endif; ?>
      <span class="about-avatar__badge" aria-hidden="true">🐋</span>
    </div>

    <div class="about-hero__body">
      <span class="about-kicker">About Me</span>
      <h1 class="about-name"><span class="grad"><?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?></span></h1>

      <?php if ('' !== $dswBio): ?>
      <p class="about-bio"><?php echo htmlspecialchars($dswBio, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>

      <div class="about-pills">
        <span class="about-pill"><svg class="ico" aria-hidden="true"><use href="#i-pin"/></svg>常年漂在深海区</span>
        <span class="about-pill"><svg class="ico" aria-hidden="true"><use href="#i-coffee"/></svg>靠米饭续命</span>
        <span class="about-pill"><svg class="ico" aria-hidden="true"><use href="#i-code"/></svg>前端 / 折腾</span>
      </div>

      <?php if ('' !== $dswMail || '' !== $dswGithub): ?>
      <div class="about-cta">
        <?php if ('' !== $dswMail): ?>
        <a class="btn btn--primary" href="<?php echo htmlspecialchars($dswMail, ENT_QUOTES, 'UTF-8'); ?>">给我写封信<svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <?php endif; ?>
        <?php if ('' !== $dswGithub): ?>
        <a class="btn btn--ghost" href="<?php echo htmlspecialchars($dswGithub, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">看看代码</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ========== 数据条 ========== -->
  <section class="about-stats">
    <div class="about-stat"><b><?php echo dswSiteStat('posts'); ?></b><span>篇文章</span></div>
    <div class="about-stat"><b><?php echo dswSiteStat('categories'); ?></b><span>个分类</span></div>
    <div class="about-stat"><b><?php echo dswSiteStat('tags'); ?></b><span>枚标签</span></div>
    <div class="about-stat"><b><?php echo number_format((float)dswSiteStat('days')); ?></b><span>天已运行</span></div>
  </section>

  <!-- ========== 自我介绍（走页面正文） ========== -->
  <section class="about-section">
    <div class="section-head">
      <div>
        <h2>这片海域是谁的</h2>
        <p>一句话版本：一个爱写点东西的普通人</p>
      </div>
    </div>
    <div class="card about-card about-card--prose reveal">
      <div class="prose">
        <?php if ($dswHasContent): ?>
        <?php $this->content(); ?>
        <?php else: ?>
        <p>你好呀，我是<?php echo htmlspecialchars($dswNickname, ENT_QUOTES, 'UTF-8'); ?>。一个人在这片数字海里漂着，偶尔浮上来换口气，顺手把看到的、想到的记在这里。</p>
        <p>白天写前端，把设计稿翻译成能跑的东西；晚上写点小工具，再把踩过的坑摊开记成文章——算是给未来的自己留一盏灯，也给恰好路过的你省点时间。</p>
        <p>这里的内容很杂：技术笔记、读书碎片、还有半夜灵光一现的胡话。没有固定的更新节奏，也不打算追热点，想到什么写什么。</p>
        <blockquote><p>「慢一点也没关系，潮水总会把人推到该去的地方。」</p></blockquote>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 在折腾 + 工具箱 ========== -->
  <section class="about-grid">

    <div class="card about-card reveal">
      <div class="section-head about-section-head">
        <div><h2>最近在折腾</h2></div>
      </div>
      <div class="doing">
        <div class="doing__item">
          <span class="doing__ico"><svg class="ico" aria-hidden="true"><use href="#i-code"/></svg></span>
          <div>
            <p class="doing__t">前端工程化</p>
            <p class="doing__d">把重复劳动交给脚本，把省下来的时间还给自己发呆。</p>
          </div>
        </div>
        <div class="doing__item">
          <span class="doing__ico"><svg class="ico" aria-hidden="true"><use href="#i-heart"/></svg></span>
          <div>
            <p class="doing__t">这个博客本身</p>
            <p class="doing__d">Typecho + 鲸落主题，从配色到波浪，一行一行调出来的作品。</p>
          </div>
        </div>
        <div class="doing__item">
          <span class="doing__ico"><svg class="ico" aria-hidden="true"><use href="#i-fire"/></svg></span>
          <div>
            <p class="doing__t">AI 工具</p>
            <p class="doing__d">让它替我搬砖，我负责验收；省下的时间用来把问题想明白。</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card about-card reveal">
      <div class="section-head about-section-head">
        <div><h2>常用工具箱</h2></div>
      </div>
      <div class="tools">
        <span class="chip">Typecho</span>
        <span class="chip">PHP</span>
        <span class="chip">JavaScript</span>
        <span class="chip">CSS</span>
        <span class="chip">Node.js</span>
        <span class="chip">Git</span>
        <span class="chip">VS Code</span>
        <span class="chip">Figma</span>
        <span class="chip">AI 助手</span>
      </div>
      <p class="tools__note">工具会换，习惯不太换：先跑起来，再跑好看。</p>
    </div>

  </section>

  <!-- ========== 时间线 ========== -->
  <?php if ($dswTimeline): ?>
  <section class="about-section">
    <div class="section-head">
      <div>
        <h2>这条鱼怎么游过来的</h2>
        <p>从一片空白到现在的样子</p>
      </div>
    </div>
    <div class="card about-card reveal">
      <div class="timeline">
        <?php foreach ($dswTimeline as $dswT): ?>
        <div class="timeline__item">
          <div class="timeline__date"><?php echo htmlspecialchars($dswT['date'], ENT_QUOTES, 'UTF-8'); ?></div>
          <p class="timeline__t"><?php echo htmlspecialchars($dswT['title'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="timeline__d"><?php echo htmlspecialchars($dswT['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ========== 联系方式 ========== -->
  <?php if ($dswLinks): ?>
  <section class="about-section">
    <div class="section-head">
      <div>
        <h2>想找我？</h2>
        <p>海面下也能收到消息</p>
      </div>
    </div>
    <div class="about-links">
      <?php foreach ($dswLinks as $dswL): ?>
      <a class="about-link" href="<?php echo htmlspecialchars($dswL['url'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo 0 === strpos($dswL['url'], 'mailto:') ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>>
        <span class="about-link__ico"><svg class="ico" aria-hidden="true"><use href="#<?php echo $dswL['icon']; ?>"/></svg></span>
        <span class="about-link__body">
          <span class="about-link__t"><?php echo htmlspecialchars($dswL['label'], ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="about-link__d"><?php echo htmlspecialchars($dswL['desc'], ENT_QUOTES, 'UTF-8'); ?></span>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
    <p class="about-tail">🐋 本站由 Typecho 驱动 · 鲸落主题 · 保留所有鱼鳞</p>
  </section>
  <?php endif; ?>

</main>

<?php $this->need('footer.php'); ?>