<?php
/**
 * AdminWhale · 鲸落后台皮肤
 *
 * 给 Typecho 管理后台套上鲸落的视觉：深色渐变顶栏、新拟态柔光卡、
 * 胶囊按钮、深海风登录页，并且完整适配手机端。
 *
 * 纯 CSS 覆盖 + 一个 head 钩子，不改任何核心文件；停用插件立刻还原原版。
 *
 * @package AdminWhale
 * @author 零落山河
 * @version 0.1.2
 * @link https://gonghaiwen.icu
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class AdminWhale_Plugin implements \Typecho\Plugin\PluginInterface
{
    /** 皮肤版本，用于 CSS 缓存戳 */
    const VERSION = '0.1.2';

    /** 支持的配色 */
    const PALETTES = ['ocean', 'mint', 'forest', 'rose'];

    /**
     * 启用插件：往后台 <head> 末尾追加皮肤
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('admin/header.php')->header = [__CLASS__, 'injectHeader'];
    }

    /**
     * 停用插件：Typecho 会自动摘掉钩子，这里无需额外处理
     */
    public static function deactivate()
    {
    }

    /**
     * 插件设置面板
     *
     * @param \Typecho\Widget\Helper\Form $form
     */
    public static function config(\Typecho\Widget\Helper\Form $form)
    {
        $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
            'enabled',
            ['1' => _t('开启'), '0' => _t('关闭')],
            '1',
            _t('启用后台皮肤'),
            _t('关闭后立刻恢复 Typecho 原版外观，不用停用插件。')
        ));

        $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
            'palette',
            [
                'theme'  => _t('跟随前台主题'),
                'ocean'  => _t('海盐蓝（默认）'),
                'mint'   => _t('薄荷青'),
                'forest' => _t('暖米绿'),
                'rose'   => _t('雾粉'),
            ],
            'theme',
            _t('后台配色'),
            _t('选「跟随前台主题」时读取鲸落主题的默认配色；主题没设置就用海盐蓝。')
        ));

        $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text(
            'loginBrand',
            null,
            '鲸落',
            _t('登录页站名'),
            _t('替换登录页上那个灰色的 Typecho 方标。留空则保留 Typecho 原字样。')
        ));

        $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select(
            'dressLogin',
            ['1' => _t('美化'), '0' => _t('保持原样')],
            '1',
            _t('登录页深海背景'),
            _t('开启后登录页会换成深海渐变 + 毛玻璃卡片。')
        ));
    }

    /**
     * 用户个人设置（本插件不需要）
     *
     * @param \Typecho\Widget\Helper\Form $form
     */
    public static function personalConfig(\Typecho\Widget\Helper\Form $form)
    {
    }

    /**
     * 往后台 <head> 注入 CSS 与初始化脚本
     *
     * @param string $header 已有的 head 片段
     * @return string
     */
    public static function injectHeader($header)
    {
        $config = self::configValues();

        if ('1' !== $config->enabled) {
            return $header;
        }

        $options = \Typecho\Widget::widget('Widget_Options');
        $base    = rtrim((string)$options->pluginUrl, '/') . '/AdminWhale';
        $palette = self::resolvePalette($config, $options);
        $brand   = trim((string)$config->loginBrand);
        $dress   = '1' === $config->dressLogin ? '1' : '0';

        $out = '<link rel="stylesheet" href="' . htmlspecialchars($base . '/admin.css?v=' . self::VERSION, ENT_QUOTES, 'UTF-8') . '">';

        // 在 head 里同步打标记，避免刷新时先闪一下原版样式
        $out .= '<script>(function(){var d=document.documentElement;'
            . 'd.classList.add("aw");'
            . 'd.setAttribute("data-aw-palette","' . $palette . '");'
            . 'd.setAttribute("data-aw-login","' . $dress . '");'
            . '})();</script>';

        if ('' !== $brand) {
            $safe = str_replace(['\\', '"', "\r", "\n", '<', '>'], ['\\\\', '\\"', '', '', '', ''], $brand);
            $out .= '<style>html.aw body.body-100 .typecho-login h1 a.i-logo::after{content:"' . $safe . '"}</style>';
        }

        return $header . $out;
    }

    /**
     * 读取插件设置（带默认值兜底）
     *
     * @return object
     */
    private static function configValues()
    {
        $defaults = (object)[
            'enabled'    => '1',
            'palette'    => 'theme',
            'loginBrand' => '鲸落',
            'dressLogin' => '1',
        ];

        try {
            $options = \Typecho\Widget::widget('Widget_Options');
            $raw     = $options->plugin('AdminWhale');

            if (!is_object($raw)) {
                return $defaults;
            }

            foreach (['enabled', 'palette', 'loginBrand', 'dressLogin'] as $key) {
                $value = $raw->{$key} ?? null;
                if (null !== $value && '' !== trim((string)$value)) {
                    $defaults->{$key} = (string)$value;
                }
            }
        } catch (\Throwable $e) {
            // 插件刚装好还没保存过设置时，直接用默认值
        }

        return $defaults;
    }

    /**
     * 解析最终配色：follow theme 时读主题设置
     *
     * @param object $config
     * @param mixed  $options
     * @return string
     */
    private static function resolvePalette($config, $options)
    {
        $palette = (string)$config->palette;

        if ('theme' === $palette || '' === $palette) {
            $fromTheme = isset($options->defaultPalette) ? (string)$options->defaultPalette : '';
            return in_array($fromTheme, self::PALETTES, true) ? $fromTheme : 'ocean';
        }

        return in_array($palette, self::PALETTES, true) ? $palette : 'ocean';
    }
}