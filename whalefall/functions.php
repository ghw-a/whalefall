<?php
/**
 * 鲸落 · Typecho 主题函数
 *
 * 安静的海岛，柔光、圆角、三层波浪。
 *
 * @package whalefall
 * @author 零落山河
 * @version 2.0.0
 * @link https://gonghaiwen.icu
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

define('DSW_VERSION', '2.0.0');

/* ============================================================
 * 0. 主题选项：默认值 / 读取
 * ============================================================ */

/**
 * 主题所有设置项及其默认值
 */
function dswDefaults(): array
{
    return [
        'defaultPalette'  => 'ocean',
        'defaultMode'     => 'auto',
        'bannerBadge'     => '✿ 深海的第 1024 天',
        'bannerTitle'     => '鲸落之处，**皆是星海**',
        'bannerBtnA'      => '开始阅读',
        'bannerBtnAUrl'   => '',
        'bannerBtnB'      => '关于本鲸鱼娘',
        'bannerBtnBUrl'   => '',
        'bannerPhotoOn'   => '1',
        'bannerPhotoApis' => "https://t.alcy.cc/ycy\nhttps://t.alcy.cc/pc\nhttps://t.alcy.cc/moe",
        'bannerPhotoOpacity' => '0.5',
        'typingLines'     => "欢迎来到鲸落，慢慢漂、慢慢写\n这里有米饭、代码和一点点星光\n把日子写成文章，把文章泡进海水",
        'avatarUrl'       => '',
        'nickname'        => '鲸鱼娘 · 大肥鱼',
        'bio'             => '住在数据海洋里的一条小鲸鱼，主食米饭，副业写代码。',
        'github'          => '',
        'mail'            => '',
        'bilibili'        => '',
        'rss'             => '',
        'footerText'      => '用 ❤ 和 🍚 搭建',
        'icpCode'         => '',
        'icpLink'         => '',
        'analytics'       => '',
        'showReadTime'    => '1',
        'showToc'         => '1',
        'commentSystem'   => 'native',
        'viewsField'      => '',
        'siteStartDate'   => '',
        'showScrollHint'  => '1',
        'showQuickNav'    => '1',
        'hotWidgetTitle'  => '最近文章',
    ];
}

/**
 * 读取设置原值（不做默认值兜底）
 */
function dswRawOpt(string $key): ?string
{
    static $options = null;

    if (null === $options) {
        $options = \Typecho\Widget::widget('Widget_Options');
    }

    $value = $options->{$key} ?? null;

    if (null === $value) {
        return null;
    }

    return is_string($value) ? $value : (string)$value;
}

/**
 * 读取一个有默认值的设置：空则回退到主题默认值
 */
function dswOpt(string $key): string
{
    static $defaults = null;

    if (null === $defaults) {
        $defaults = dswDefaults();
    }

    $value = dswRawOpt($key);

    if (null === $value || '' === trim($value)) {
        return (string)($defaults[$key] ?? '');
    }

    return $value;
}

/**
 * 读取一个可为空的设置：空就是空，不兜底
 */
function dswOptOr(string $key, string $fallback = ''): string
{
    $value = dswRawOpt($key);

    if (null === $value || '' === trim($value)) {
        return $fallback;
    }

    return $value;
}

/**
 * 开关类设置：返回 bool
 */
function dswFlag(string $key): bool
{
    return '1' === dswOpt($key);
}

/* ============================================================
 * 1. 主题后台设置面板
 * ============================================================ */

function themeConfig($form)
{
    /** 外观 */
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'defaultPalette',
        [
            'ocean'  => _t('海盐蓝（冷白 + 蓝紫）'),
            'mint'   => _t('薄荷青（青绿 + 暖橙）'),
            'forest' => _t('暖米绿（米白 + 草绿）'),
            'rose'   => _t('雾粉（低饱和粉）'),
        ],
        'ocean',
        _t('默认配色'),
        _t('访客第一次访问时的配色，右上角可随时切换。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'defaultMode',
        [
            'auto'  => _t('跟随系统'),
            'light' => _t('始终浅色'),
            'dark'  => _t('始终深色（深海蓝）'),
        ],
        'auto',
        _t('默认深浅色模式'),
        _t('访客第一次访问时使用，右上角可随时切换。')
    ));

    /** 首页 Banner */
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerBadge',
        null,
        '✿ 深海的第 1024 天',
        _t('Banner 徽章文案'),
        _t('Banner 顶部的小胶囊文字，留空则不显示。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerTitle',
        null,
        '鲸落之处，**皆是星海**',
        _t('Banner 标题'),
        _t('用 **双星号** 包住的部分会渲染成渐变色，例如：鲸落之处，**皆是星海**')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'typingLines',
        null,
        "欢迎来到鲸落，慢慢漂、慢慢写\n这里有米饭、代码和一点点星光\n把日子写成文章，把文章泡进海水",
        _t('打字机文案'),
        _t('一行一句，循环打字显示。只写一行则不做动画。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerBtnA',
        null,
        '开始阅读',
        _t('Banner 主按钮文案'),
        _t('留空则不显示主按钮。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerBtnAUrl',
        null,
        '',
        _t('Banner 主按钮链接'),
        _t('留空则滚动到文章列表。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerBtnB',
        null,
        '关于本鲸鱼娘',
        _t('Banner 次按钮文案'),
        _t('留空则不显示次按钮。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerBtnBUrl',
        null,
        '',
        _t('Banner 次按钮链接'),
        _t('可以填独立页面地址，例如 /index.php/about.html')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'showScrollHint',
        ['1' => _t('显示'), '0' => _t('隐藏')],
        '1',
        _t('Banner 底部 SCROLL 提示')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'bannerPhotoOn',
        ['1' => _t('显示'), '0' => _t('关闭')],
        '1',
        _t('首页随机人物背景'),
        _t('访问首页时从下面的接口随机取一张二次元插画，淡入铺在 Banner 上。加载失败会静默退回纯渐变底，不影响首屏。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'bannerPhotoApis',
        null,
        "https://t.alcy.cc/ycy\nhttps://t.alcy.cc/pc\nhttps://t.alcy.cc/moe",
        _t('背景图接口（一行一个）'),
        _t('随机选一行逐个尝试：返回 JSON 的接口取 url 字段，直接返回图片的接口直接当背景，失败自动换下一个。留空则关闭背景图。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'bannerPhotoOpacity',
        null,
        '0.5',
        _t('背景图不透明度'),
        _t('建议 0.2 ~ 0.6。太高会压住标题和打字机文字。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'showQuickNav',
        ['1' => _t('显示'), '0' => _t('隐藏')],
        '1',
        _t('首页四格快捷入口')
    ));

    /** 个人信息 */
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Url(
        'avatarUrl',
        null,
        '',
        _t('头像地址'),
        _t('填写图片 URL，留空则显示 🐳 图标。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'nickname',
        null,
        '鲸鱼娘 · 大肥鱼',
        _t('昵称'),
        _t('显示在首页资料卡、作者卡。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'bio',
        null,
        '住在数据海洋里的一条小鲸鱼，主食米饭，副业写代码。',
        _t('一句话简介'),
        _t('显示在首页资料卡、作者卡。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Url(
        'github',
        null,
        '',
        _t('GitHub 地址'),
        _t('留空则不渲染该图标。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'mail',
        null,
        '',
        _t('邮箱'),
        _t('只填邮箱地址，主题会自动加上 mailto:。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Url(
        'bilibili',
        null,
        '',
        _t('B 站地址'),
        _t('留空则不渲染该图标。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Url(
        'rss',
        null,
        '',
        _t('RSS 地址'),
        _t('留空则使用站点默认 RSS。')
    ));

    /** 页脚 */
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'footerText',
        null,
        '用 ❤ 和 🍚 搭建',
        _t('页脚文案'),
        _t('显示在版权行上方。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'icpCode',
        null,
        '',
        _t('备案号'),
        _t('例如：京ICP备 12345678 号，留空则不显示。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Url(
        'icpLink',
        null,
        '',
        _t('备案号链接'),
        _t('一般填 https://beian.miit.gov.cn/')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea(
        'analytics',
        null,
        '',
        _t('统计代码'),
        _t('原样输出到 </body> 之前，可放百度统计 / 51LA 等。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'siteStartDate',
        null,
        '',
        _t('建站日期'),
        _t('格式 2026-01-01，用于侧栏「已运行 N 天」，留空则取第一篇文章的日期。')
    ));

    /** 功能开关 */
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'showReadTime',
        ['1' => _t('显示'), '0' => _t('隐藏')],
        '1',
        _t('显示阅读时长 / 字数')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'showToc',
        ['1' => _t('显示'), '0' => _t('隐藏')],
        '1',
        _t('文章页自动目录'),
        _t('正文中有两级以上标题时才生成。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
        'commentSystem',
        [
            'native' => _t('Typecho 原生评论'),
            'off'    => _t('关闭评论'),
        ],
        'native',
        _t('评论方案'),
        _t('已为 Giscus / Waline 预留扩展位，后续版本接入。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'viewsField',
        null,
        '',
        _t('浏览量字段名'),
        _t('装了统计浏览量插件（字段名通常为 views）才需要填，留空则全站不显示浏览量。')
    ));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
        'hotWidgetTitle',
        null,
        '最近文章',
        _t('侧栏文章小工具标题'),
        _t('默认「最近文章」；若装了浏览量插件可改成「热门文章」。')
    ));
}

/* ============================================================
 * 2. 文章自定义字段
 * ============================================================ */

function themeFields($layout)
{
    $layout->addItem(new \Typecho\Widget\Helper\Form\Element\Text(
        'thumb',
        null,
        null,
        _t('缩略图'),
        _t('填写图片 URL。留空时自动取正文第一张图；再没有则用分类色块 + 图标。')
    ));

    $layout->addItem(new \Typecho\Widget\Helper\Form\Element\Select(
        'pin',
        ['0' => _t('否'), '1' => _t('是，置顶')],
        '0',
        _t('置顶'),
        _t('置顶文章会在列表里显示「置顶」角标。')
    ));
}

/* ============================================================
 * 2.5 文章点赞（主题内置，不需要插件）
 *
 * 存储：typecho_fields 里 name=dsw_likes 的 int 字段
 * 接口：POST 到当前文章地址，body: action=dsw-like&cid=<cid>
 * 防重：浏览器写 cookie dsw_liked_<cid>，前端再用 localStorage 兜一层
 * ============================================================ */

/**
 * 点赞用的字段名（主题私有，避免和第三方点赞插件打架）
 */
function dswLikeField(): string
{
    return 'dsw_likes';
}

/**
 * Typecho 只在前台归档页初始化时调一次，正好拿来接轻量接口
 */
function themeInit($archive): void
{
    if ('dsw-like' === (string)($_POST['action'] ?? '')) {
        dswHandleLike();
    }
}

/**
 * 读某篇文章的点赞数
 */
function dswLikes(int $cid): int
{
    if ($cid < 1) {
        return 0;
    }

    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow(
            $db->select('int_value')
                ->from('table.fields')
                ->where('cid = ? AND name = ?', $cid, dswLikeField())
                ->limit(1)
        );
    } catch (\Throwable $e) {
        return 0;
    }

    return $row ? max(0, (int)$row['int_value']) : 0;
}

/**
 * 点赞接口：校验同源 + 内容状态，字段自增一次，回 JSON
 */
function dswHandleLike(): void
{
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('X-Robots-Tag: noindex');

    $reply = static function (int $code, array $payload): void {
        http_response_code($code);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    };

    if ('POST' !== strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? ''))) {
        $reply(405, ['ok' => false, 'msg' => 'Method Not Allowed']);
    }

    /* 同源校验：正常 AJAX 会带 Referer，跨站脚本刷接口会被挡 */
    $host = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
    $refHost = strtolower((string)parse_url((string)($_SERVER['HTTP_REFERER'] ?? ''), PHP_URL_HOST));
    if ('' !== $refHost && '' !== $host && $refHost !== $host) {
        $reply(403, ['ok' => false, 'msg' => 'Forbidden']);
    }

    $cid = (int)($_POST['cid'] ?? 0);
    if ($cid < 1) {
        $reply(400, ['ok' => false, 'msg' => 'Bad Request']);
    }

    $name = dswLikeField();
    $cookie = 'dsw_liked_' . $cid;
    $already = !empty($_COOKIE[$cookie]);

    try {
        $db = \Typecho\Db::get();

        $post = $db->fetchRow(
            $db->select('type', 'status')
                ->from('table.contents')
                ->where('cid = ?', $cid)
                ->limit(1)
        );

        if (!$post || 'post' !== $post['type'] || 'publish' !== $post['status']) {
            $reply(404, ['ok' => false, 'msg' => 'Not Found']);
        }

        if (!$already) {
            /* 先自增；字段不存在时 affectedRows 为 0，再补插入 */
            $updated = (int)$db->query(
                $db->update('table.fields')
                    ->expression('int_value', 'int_value + 1', false)
                    ->where('cid = ? AND name = ?', $cid, $name)
            );

            if ($updated < 1) {
                $exists = $db->fetchRow(
                    $db->select('cid')
                        ->from('table.fields')
                        ->where('cid = ? AND name = ?', $cid, $name)
                        ->limit(1)
                );

                if (!$exists) {
                    try {
                        $db->query($db->insert('table.fields')->rows([
                            'cid'         => $cid,
                            'name'        => $name,
                            'type'        => 'int',
                            'str_value'   => null,
                            'int_value'   => 1,
                            'float_value' => 0,
                        ]));
                    } catch (\Throwable $e) {
                        /* 并发下别人刚好插进去了，补一次自增 */
                        $db->query(
                            $db->update('table.fields')
                                ->expression('int_value', 'int_value + 1', false)
                                ->where('cid = ? AND name = ?', $cid, $name)
                        );
                    }
                }
            }

            setcookie($cookie, '1', [
                'expires'  => time() + 31536000,
                'path'     => '/',
                'samesite' => 'Lax',
            ]);
        }

        $likes = dswLikes($cid);
    } catch (\Throwable $e) {
        $reply(500, ['ok' => false, 'msg' => 'Server Error']);
    }

    $reply(200, [
        'ok'        => true,
        'likes'     => $likes,
        'formatted' => dswFormatCount($likes),
        'already'   => $already,
    ]);
}

/* ============================================================
 * 3. 内容辅助函数
 * ============================================================ */

/**
 * 字数统计：中文按字、英文按词
 */
function postWordCount($post): int
{
    $text = is_object($post) ? (string)$post->content : (string)$post;
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $zh = preg_match_all('/[\x{4e00}-\x{9fff}\x{3400}-\x{4dbf}]/u', $text);
    $en = preg_match_all('/[a-zA-Z0-9]+/', $text);

    return max(0, (int)$zh + (int)$en);
}

/**
 * 阅读时长（按 300 字/分钟）
 */
function postReadTime($post): int
{
    return max(1, (int)ceil(postWordCount($post) / 300));
}

/**
 * 数字格式化：1204 -> 1.2k
 */
function dswFormatCount($num): string
{
    $num = (int)$num;
    if ($num >= 10000) {
        return rtrim(rtrim(number_format($num / 10000, 1), '0'), '.') . 'w';
    }
    if ($num >= 1000) {
        return rtrim(rtrim(number_format($num / 1000, 1), '0'), '.') . 'k';
    }
    return (string)$num;
}

/**
 * 缩略图配色 + 图标：分类色板，按 cid 稳定取用
 */
function coverColor($post): array
{
    $palette = [
        ['#f6ddc9', '#f0c6b0', 'i-coffee'],
        ['#d8e2f6', '#c3d4f2', 'i-code'],
        ['#e0daf5', '#cdc4ee', 'i-headphones'],
        ['#d6ebe2', '#c6e5d5', 'i-camera'],
        ['#eae2f4', '#d9cdea', 'i-book'],
        ['#f3e7d8', '#e9d5be', 'i-image'],
    ];

    $cid = 0;
    if (is_object($post) && isset($post->cid)) {
        $cid = (int)$post->cid;
    }

    $pick = $palette[$cid % count($palette)];

    return ['c1' => $pick[0], 'c2' => $pick[1], 'icon' => $pick[2]];
}

/**
 * 取文章缩略图：字段 thumb -> 正文首图 -> 空
 */
function dswPostThumb($post): string
{
    $thumb = $post->fields->thumb ?? '';
    if (is_string($thumb) && '' !== trim($thumb)) {
        return trim($thumb);
    }

    $content = (string)$post->content;
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
        return $m[1];
    }

    return '';
}

/**
 * 浏览量：没有统计插件时返回空
 */
function getPostViews($post): string
{
    $field = (string)dswOptOr('viewsField');
    if ('' === $field || !is_object($post)) {
        return '';
    }

    $views = (int)($post->fields->{$field} ?? 0);
    return $views > 0 ? dswFormatCount($views) : '';
}

/**
 * 生成文章目录，并给标题补 id
 *
 * @return array{0: string, 1: array}
 */
function buildToc(string $content): array
{
    $toc = [];
    $index = 0;

    $content = preg_replace_callback(
        '/<h([2-3])([^>]*)>(.*?)<\/h\1>/is',
        function ($m) use (&$toc, &$index) {
            $text = trim(strip_tags($m[3]));
            if ('' === $text) {
                return $m[0];
            }

            $attrs = $m[2];
            if (preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $idMatch)) {
                $id = $idMatch[1];
            } else {
                $index++;
                $id = 'sec-' . $index;
                $attrs .= ' id="' . $id . '"';
            }

            $toc[] = [
                'id'    => $id,
                'text'  => $text,
                'level' => (int)$m[1],
            ];

            return '<h' . $m[1] . $attrs . '>' . $m[3] . '</h' . $m[1] . '>';
        },
        $content
    );

    return [$content, $toc];
}

/**
 * Banner 标题：把 **文字** 包成渐变 span
 */
function dswBannerTitle(string $title): string
{
    $safe = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return preg_replace('/\*\*(.+?)\*\*/u', '<span class="grad">$1</span>', $safe) ?: $safe;
}

/**
 * 打字机句子
 */
function dswTypingLines(): array
{
    $raw = (string)dswOpt('typingLines');
    $lines = preg_split('/\r\n|\r|\n/', $raw);
    $lines = array_values(array_filter(array_map('trim', $lines), function ($v) {
        return '' !== $v;
    }));
    return $lines ?: ['欢迎来到鲸落，慢慢漂、慢慢写'];
}

/**
 * 头像地址
 */
function dswAvatarUrl(): string
{
    return trim((string)dswOptOr('avatarUrl'));
}

/* ============================================================
 * 4. 站点统计
 * ============================================================ */

function dswSiteStat(string $key)
{
    static $stats = null;

    if (null === $stats) {
        $db = \Typecho\Db::get();

        $postCount = 0;
        $commentCount = 0;
        $lastUpdate = 0;
        $firstDate = 0;
        $catCount = 0;
        $tagCount = 0;

        try {
            $row = $db->fetchObject(
                $db->select('COUNT(*) AS num')->from('table.contents')
                    ->where('type = ?', 'post')
                    ->where('status = ?', 'publish')
            );
            $postCount = (int)($row->num ?? 0);

            $row = $db->fetchObject(
                $db->select('COUNT(*) AS num')->from('table.comments')
                    ->where('status = ?', 'approved')
            );
            $commentCount = (int)($row->num ?? 0);

            $row = $db->fetchObject(
                $db->select('COUNT(*) AS num')->from('table.metas')
                    ->where('type = ?', 'category')
            );
            $catCount = (int)($row->num ?? 0);

            $row = $db->fetchObject(
                $db->select('COUNT(*) AS num')->from('table.metas')
                    ->where('type = ?', 'tag')
            );
            $tagCount = (int)($row->num ?? 0);

            $row = $db->fetchObject(
                $db->select('created')->from('table.contents')
                    ->where('type = ?', 'post')
                    ->where('status = ?', 'publish')
                    ->order('created', \Typecho\Db::SORT_DESC)
                    ->limit(1)
            );
            $lastUpdate = (int)($row->created ?? 0);

            $row = $db->fetchObject(
                $db->select('created')->from('table.contents')
                    ->where('type = ?', 'post')
                    ->where('status = ?', 'publish')
                    ->order('created', \Typecho\Db::SORT_ASC)
                    ->limit(1)
            );
            $firstDate = (int)($row->created ?? 0);
        } catch (\Exception $e) {
            // 统计失败不影响页面
        }

        $start = trim((string)dswOptOr('siteStartDate'));
        $startTs = $start ? strtotime($start) : 0;
        if (!$startTs) {
            $startTs = $firstDate ?: time();
        }

        $stats = [
            'posts'      => $postCount,
            'comments'   => $commentCount,
            'categories' => $catCount,
            'tags'       => $tagCount,
            'days'       => max(1, (int)floor((time() - $startTs) / 86400)),
            'lastUpdate' => $lastUpdate,
        ];
    }

    return $stats[$key] ?? 0;
}

/**
 * 「2 天前」这种相对时间
 */
function dswTimeAgo(int $timestamp): string
{
    if ($timestamp <= 0) {
        return '—';
    }

    $diff = time() - $timestamp;

    if ($diff < 60) {
        return '刚刚';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . ' 分钟前';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . ' 小时前';
    }
    if ($diff < 86400 * 30) {
        return floor($diff / 86400) . ' 天前';
    }
    if ($diff < 86400 * 365) {
        return floor($diff / (86400 * 30)) . ' 个月前';
    }

    return floor($diff / (86400 * 365)) . ' 年前';
}

/* ============================================================
 * 5. 导航与社交链接
 * ============================================================ */

/**
 * 顶部导航项
 */
function dswMenuItems(\Widget\Archive $archive): array
{
    $options = \Typecho\Widget::widget('Widget_Options');
    $items = [];

    $items[] = [
        'url'    => $options->siteUrl,
        'label'  => _t('首页'),
        'icon'   => 'i-home',
        'active' => $archive->is('index'),
    ];

    // 独立页面
    try {
        /* 第 4 参 false = 独立实例；共享 widget 池的游标会互相干扰 */
        $pages = \Typecho\Widget::widget('Widget_Contents_Page_List', null, null, false);
        while ($pages->next()) {
            $slug = (string)$pages->slug;
            $icon = 'i-book';
            if (false !== strpos($slug, 'archive')) {
                $icon = 'i-archive';
            } elseif (false !== strpos($slug, 'about') || false !== strpos($slug, 'about-me')) {
                $icon = 'i-user';
            }

            $items[] = [
                'url'    => $pages->permalink,
                'label'  => $pages->title,
                'icon'   => $icon,
                'active' => $archive->is('page', $slug),
            ];
        }
    } catch (\Exception $e) {
        // 忽略
    }

    // 前四个分类
    try {
        /* Typecho 按类名缓存 widget（self::$widgetPool），导航和侧栏拿的是同一个实例。
           这里传第 4 参 false 走独立实例，否则下面的 break 会把游标停在半路，
           侧栏再遍历就只剩后面的分类了（曾经只显示出一个分类就是这个原因）。 */
        $cats = \Typecho\Widget::widget('Widget_Metas_Category_List', null, null, false);
        $i = 0;
        while ($cats->next()) {
            if ($i >= 4) {
                break;
            }
            $i++;
            $items[] = [
                'url'    => $cats->permalink,
                'label'  => $cats->name,
                'icon'   => 'i-folder',
                'active' => $archive->is('category', (string)$cats->slug),
            ];
        }
    } catch (\Exception $e) {
        // 忽略
    }

    return $items;
}

/**
 * 社交链接（只返回填了的）
 */
function dswSocials(): array
{
    $items = [];

    $github = trim((string)dswOptOr('github'));
    if ($github) {
        $items[] = ['url' => $github, 'icon' => 'i-github', 'label' => 'GitHub'];
    }

    $mail = trim((string)dswOptOr('mail'));
    if ($mail) {
        $items[] = ['url' => strpos($mail, 'mailto:') === 0 ? $mail : 'mailto:' . $mail, 'icon' => 'i-mail', 'label' => '邮箱'];
    }

    $bilibili = trim((string)dswOptOr('bilibili'));
    if ($bilibili) {
        $items[] = ['url' => $bilibili, 'icon' => 'i-play', 'label' => '哔哩哔哩'];
    }

    $rss = trim((string)dswOptOr('rss'));
    if ($rss) {
        $items[] = ['url' => $rss, 'icon' => 'i-rss', 'label' => 'RSS'];
    }

    return $items;
}

/**
 * 取一篇真实文章的标题和时间（供「关于」页时间线用）
 *
 * @param string $order \Typecho\Db::SORT_ASC / SORT_DESC
 */
function dswAboutPostRow(string $order): ?array
{
    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow(
            $db->select('title', 'created')
                ->from('table.contents')
                ->where('table.contents.type = ?', 'post')
                ->where('table.contents.status = ?', 'publish')
                ->where('table.contents.created < ?', time())
                ->order('table.contents.created', $order)
                ->limit(1)
        );

        return $row ?: null;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * 「关于」页时间线：最早一篇 + 最新一篇 + 「现在」，日期全部来自真实数据
 */
function dswAboutTimeline(): array
{
    $items = [];
    $options = \Typecho\Widget::widget('Widget_Options');
    $timezone = (int)$options->timezone;
    $fmt = 'Y / m';

    $first = dswAboutPostRow(\Typecho\Db::SORT_ASC);
    $last  = dswAboutPostRow(\Typecho\Db::SORT_DESC);

    if ($first) {
        $items[] = [
            'date'  => date($fmt, (int)$first['created'] + $timezone),
            'title' => '第一篇落水',
            'desc'  => '写下《' . $first['title'] . '》，这片海域有了第一句话。',
        ];
    }

    if ($last && (!$first || (int)$last['created'] !== (int)$first['created'])) {
        $items[] = [
            'date'  => date($fmt, (int)$last['created'] + $timezone),
            'title' => '最近写下的',
            'desc'  => '《' . $last['title'] . '》先放这儿，下一篇还在路上。',
        ];
    }

    $items[] = [
        'date'  => '现在',
        'title' => '还在慢慢写',
        'desc'  => '不追更新频率，只保证每篇都是真的想写。',
    ];

    return $items;
}

/**
 * GitHub 链接里抠用户名：github.com/xxx?tab=... → xxx
 */
function dswGithubHandle(string $url): string
{
    $path = (string)parse_url($url, PHP_URL_PATH);
    $seg = trim((string)strtok(trim($path, '/'), '/'));

    return '' !== $seg ? $seg : 'GitHub';
}

/**
 * 「关于」页联系方式：把主题设置里的社交链接翻译成卡片
 */
function dswAboutLinks(): array
{
    $links = [];
    $seen = [];

    foreach (dswSocials() as $s) {
        if (isset($seen[$s['icon']])) {
            continue;
        }
        $seen[$s['icon']] = true;

        $desc = '';
        if ('i-mail' === $s['icon']) {
            $desc = (string)preg_replace('#^mailto:#i', '', $s['url']);
        } elseif ('i-github' === $s['icon']) {
            $desc = dswGithubHandle($s['url']);
        } elseif ('i-play' === $s['icon']) {
            $desc = '偶尔冒泡';
        } elseif ('i-rss' === $s['icon']) {
            $desc = '订阅这片海域';
        }

        $links[] = [
            'url'   => $s['url'],
            'icon'  => $s['icon'],
            'label' => $s['label'],
            'desc'  => '' !== $desc ? $desc : $s['label'],
        ];
    }

    /* 没配 RSS 就用站点自带的订阅地址兜底 */
    if (!isset($seen['i-rss'])) {
        $options = \Typecho\Widget::widget('Widget_Options');
        $links[] = [
            'url'   => (string)$options->feedUrl,
            'icon'  => 'i-rss',
            'label' => 'RSS',
            'desc'  => '订阅这片海域',
        ];
    }

    return $links;
}

/**
 * 评论是否开启
 */
function dswCommentsEnabled(): bool
{
    return 'native' === (string)dswOpt('commentSystem');
}

/**
 * 归档页地址：优先用 slug 含 archive 的独立页面，否则退回首页
 */
function dswArchivesUrl(\Widget\Archive $archive): string
{
    try {
        /* 这里会提前 return，必须用独立实例，否则游标会卡在中间 */
        $pages = \Typecho\Widget::widget('Widget_Contents_Page_List', null, null, false);
        while ($pages->next()) {
            if (false !== strpos((string)$pages->slug, 'archive')) {
                return (string)$pages->permalink;
            }
        }
    } catch (\Exception $e) {
        // 忽略
    }

    return (string)\Typecho\Widget::widget('Widget_Options')->siteUrl;
}

/**
 * 第一个分类的链接（四格导航用）
 */
function dswFirstCategoryUrl(\Widget\Archive $archive): string
{
    try {
        /* 只取第一个分类就必须用独立实例；共享池的游标会被这次 next() 带偏，
           侧栏「文章分类」就会少显示一项（首页曾经只剩一个分类的元凶之一） */
        $cats = \Typecho\Widget::widget('Widget_Metas_Category_List', null, null, false);
        if ($cats->next()) {
            return (string)$cats->permalink;
        }
    } catch (\Exception $e) {
        // 忽略
    }

    return (string)\Typecho\Widget::widget('Widget_Options')->siteUrl;
}

/**
 * 第一个标签的链接（四格导航用）
 */
function dswFirstTagUrl(\Widget\Archive $archive): string
{
    try {
        $tags = \Typecho\Widget::widget('Widget_Metas_Tag_Cloud', 'sort=count&desc=1&limit=1', null, false);
        if ($tags->next()) {
            return (string)$tags->permalink;
        }
    } catch (\Exception $e) {
        // 忽略
    }

    return (string)\Typecho\Widget::widget('Widget_Options')->siteUrl;
}