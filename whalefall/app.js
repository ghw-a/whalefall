/* ============================================================
   鲸落 · 交互脚本
   ------------------------------------------------------------
   - 深浅色 / 四套配色切换（localStorage 记忆）
   - Banner 打字机、上浮气泡
   - 滚动渐入、导航状态、回到顶部、移动端菜单
   - 文章目录平滑滚动、分享复制
   ============================================================ */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 告诉 CSS：脚本已就绪，可以放心启用渐入动画
  root.classList.remove('no-js');

  /* ---------- 1. 主题切换 ---------- */
  var themeBtns = [
    document.getElementById('navThemeBtn'),
    document.getElementById('fabThemeBtn')
  ].filter(Boolean);

  function syncThemeIcons() {
    var isDark = root.dataset.theme === 'dark';
    ['navThemeIcon', 'fabThemeIcon'].forEach(function (id) {
      var use = document.querySelector('#' + id + ' use');
      if (use) use.setAttribute('href', isDark ? '#i-sun' : '#i-moon');
    });
    themeBtns.forEach(function (btn) {
      btn.setAttribute('aria-label', isDark ? '切换到浅色' : '切换到深色');
      btn.title = isDark ? '切换到浅色' : '切换到深色';
    });
  }

  function toggleTheme() {
    var next = root.dataset.theme === 'dark' ? 'light' : 'dark';
    root.dataset.theme = next;
    try { localStorage.setItem('whale-theme', next); } catch (e) { /* 忽略 */ }
    syncThemeIcons();
  }

  themeBtns.forEach(function (btn) { btn.addEventListener('click', toggleTheme); });
  syncThemeIcons();

  /* ---------- 2. 配色切换（四套） ---------- */
  var palettes = ['ocean', 'mint', 'forest', 'rose'];
  var paletteNames = { ocean: '海盐蓝', mint: '薄荷青', forest: '暖米绿', rose: '雾粉' };
  var paletteBtns = [
    document.getElementById('paletteBtn'),
    document.getElementById('fabPaletteBtn')
  ].filter(Boolean);

  function syncPaletteTitle() {
    var name = paletteNames[root.dataset.palette || 'ocean'] || '海盐蓝';
    paletteBtns.forEach(function (btn) {
      btn.title = '配色：' + name + '（点击切换）';
      btn.setAttribute('aria-label', '配色：' + name + '（点击切换）');
    });
  }

  paletteBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var idx = palettes.indexOf(root.dataset.palette || 'ocean');
      var next = palettes[(idx + 1) % palettes.length];
      root.dataset.palette = next;
      try { localStorage.setItem('whale-palette', next); } catch (e) { /* 忽略 */ }
      syncPaletteTitle();
    });
  });
  syncPaletteTitle();

  /* ---------- 3. Banner 打字机 ---------- */
  var lines = (window.DSW && Array.isArray(window.DSW.typing) && window.DSW.typing.length)
    ? window.DSW.typing
    : ['欢迎来到鲸落，慢慢漂、慢慢写'];
  var typingEl = document.getElementById('typing');

  if (typingEl) {
    if (reduceMotion || lines.length < 2) {
      typingEl.textContent = lines[0];
    } else {
      var li = 0, ci = 0, deleting = false;

      var tick = function () {
        var line = lines[li];

        if (!deleting) {
          ci++;
          typingEl.textContent = line.slice(0, ci);
          if (ci >= line.length) {
            deleting = true;
            return setTimeout(tick, 1900);
          }
          return setTimeout(tick, 95);
        }

        ci--;
        typingEl.textContent = line.slice(0, ci);
        if (ci <= 0) {
          deleting = false;
          li = (li + 1) % lines.length;
          return setTimeout(tick, 380);
        }
        return setTimeout(tick, 42);
      };

      setTimeout(tick, 600);
    }
  }

  /* ---------- 4. Banner 上浮气泡 ---------- */
  var bubbleBox = document.getElementById('bubbles');
  if (bubbleBox && !reduceMotion) {
    for (var i = 0; i < 16; i++) {
      var b = document.createElement('span');
      var size = 6 + Math.random() * 26;
      b.className = 'bubble';
      b.style.width = size + 'px';
      b.style.height = size + 'px';
      b.style.left = (Math.random() * 100) + '%';
      b.style.animationDuration = (11 + Math.random() * 12) + 's';
      b.style.animationDelay = (-Math.random() * 14) + 's';
      bubbleBox.appendChild(b);
    }
  }

  /* ---------- 5. 滚动渐入 ---------- */
  var reveals = document.querySelectorAll('.reveal');

  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = Array.prototype.indexOf.call(el.parentNode.children, el) % 6;
        el.style.transitionDelay = (delay * 70) + 'ms';
        el.classList.add('is-in');
        io.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---------- 6. 导航状态 & 回到顶部 ---------- */
  var nav = document.getElementById('nav');
  var topBtn = document.getElementById('topBtn');

  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    if (nav) nav.classList.toggle('is-scrolled', y > 12);
    if (topBtn) topBtn.classList.toggle('is-show', y > 420);
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (topBtn) {
    topBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* ---------- 7. 移动端菜单 ---------- */
  var menuToggle = document.getElementById('menuToggle');
  if (menuToggle && nav) {
    menuToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      nav.classList.toggle('is-open');
      menuToggle.setAttribute('aria-expanded', nav.classList.contains('is-open') ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target)) {
        nav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });

    document.querySelectorAll('#menu a').forEach(function (a) {
      a.addEventListener('click', function () { nav.classList.remove('is-open'); });
    });
  }

  /* ---------- 8. 文章页目录 ---------- */
  var tocLinks = document.querySelectorAll('.toc a');
  tocLinks.forEach(function (a) {
    a.addEventListener('click', function (e) {
      var href = a.getAttribute('href') || '';
      var target = href.charAt(0) === '#' ? document.getElementById(href.slice(1)) : null;
      if (!target) return;

      e.preventDefault();
      tocLinks.forEach(function (x) { x.classList.remove('is-on'); });
      a.classList.add('is-on');
      target.scrollIntoView({
        behavior: reduceMotion ? 'auto' : 'smooth',
        block: 'start'
      });
      if (history.replaceState) history.replaceState(null, '', href);
    });
  });

  /* ---------- 9. 分享：复制链接 ---------- */
  document.querySelectorAll('[data-copy-url]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = btn.getAttribute('data-copy-url') || location.href;
      var tip = btn.querySelector('.share-btn__txt');
      var original = tip ? tip.textContent : '';

      function done(ok) {
        if (!tip) return;
        tip.textContent = ok ? '已复制' : '复制失败';
        setTimeout(function () { tip.textContent = original; }, 1600);
      }

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(function () { done(true); }, function () { done(false); });
        return;
      }

      var ta = document.createElement('textarea');
      ta.value = url;
      ta.setAttribute('readonly', '');
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      var ok = false;
      try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
      document.body.removeChild(ta);
      done(ok);
    });
  });

  /* ---------- 10. 评论回复：滚动到表单 ---------- */
  var respondBox = document.querySelector('.respond');
  if (respondBox) {
    document.addEventListener('click', function (e) {
      var link = e.target.closest ? e.target.closest('.comment__actions a') : null;
      if (!link) return;
      setTimeout(function () {
        respondBox.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
      }, 80);
    });
  }

  /* ---------- 11. 搜索：顶部按钮 / 四格入口 → 聚焦搜索框 ---------- */
  function focusSearch(e) {
    var input = document.getElementById('sidebarSearchInput') || document.querySelector('.search input');
    if (!input) return;
    e.preventDefault();
    input.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
    input.focus({ preventScroll: true });
  }

  var navSearchBtn = document.getElementById('navSearchBtn');
  if (navSearchBtn) navSearchBtn.addEventListener('click', focusSearch);

  document.querySelectorAll('[data-focus-search]').forEach(function (el) {
    el.addEventListener('click', focusSearch);
  });

  /* ---------- 12. Banner 随机人物背景 ---------- */
  (function () {
    var box = document.getElementById('bannerPhoto');
    if (!box) return;

    var banner = box.parentNode;
    var apis = (box.getAttribute('data-apis') || '').split(/\r?\n/).map(function (s) {
      return s.trim();
    }).filter(Boolean);
    if (!apis.length) return;

    function pickUrl(json) {
      if (!json) return '';
      if (typeof json === 'string') return json;
      if (json.url) return json.url;
      if (json.results && json.results[0] && json.results[0].url) return json.results[0].url;
      if (json.images && json.images[0]) return json.images[0].url || json.images[0];
      if (json.data && json.data.url) return json.data.url;
      if (json.link) return json.link;
      return '';
    }

    /** 预加载一张图，成功才挂到 Banner 上；返回 Promise<boolean> */
    function preload(url, timeout) {
      return new Promise(function (resolve) {
        var img = new Image();
        var done = false;
        var timer = setTimeout(function () { finish(false); }, timeout || 10000);

        function finish(ok) {
          if (done) return;
          done = true;
          clearTimeout(timer);
          if (ok) {
            box.style.backgroundImage = 'url("' + url.replace(/"/g, '\\"') + '")';
            box.classList.add('is-loaded');
            if (banner && banner.classList) banner.classList.add('has-photo');
          }
          resolve(ok);
        }

        img.decoding = 'async';
        img.onload = function () { finish(true); };
        img.onerror = function () { finish(false); };
        img.src = url;
      });
    }

    /**
     * 尝试一个接口，返回 Promise<boolean>
     * - JSON 接口（如 nekos.best）：解析出 url 再预加载
     * - 图片直链（如 t.alcy.cc）：<img> 直接加载
     * 图片直链常常不给 CORS 头，fetch 会被浏览器拦，所以 catch 里再交给 <img> 兜一次
     */
    function tryOne(api) {
      var controller = typeof AbortController === 'function' ? new AbortController() : null;
      var timer = setTimeout(function () {
        if (controller) controller.abort();
      }, 8000);

      var opts = { mode: 'cors', credentials: 'omit' };
      if (controller) opts.signal = controller.signal;

      return fetch(api, opts)
        .then(function (res) {
          clearTimeout(timer);
          if (!res.ok) return false;
          var ct = (res.headers.get('content-type') || '').toLowerCase();
          if (ct.indexOf('json') !== -1) {
            return res.json().then(function (json) {
              var url = pickUrl(json);
              return url ? preload(url, 10000) : false;
            });
          }
          // 直接吐图片的接口
          return preload(api, 10000);
        })
        .catch(function () {
          clearTimeout(timer);
          // CORS / 网络失败兜底：如果它本身就是图片，<img> 仍能加载出来
          return preload(api, 10000);
        });
    }

    // 按配置顺序依次尝试，第一个能用的就用它（接口本身每次随机出图）
    var queue = apis.slice();
    (function next() {
      var api = queue.shift();
      if (!api) return;
      tryOne(api).then(function (ok) {
        if (!ok) next();
      });
    })();
  })();

  /* ---------- 13. 文章点赞 ---------- */
  (function () {
    var btns = document.querySelectorAll('[data-like-cid]');
    if (!btns.length) return;

    function fmt(n) {
      n = parseInt(n, 10) || 0;
      if (n >= 10000) return (n / 10000).toFixed(1).replace(/\.0$/, '') + 'w';
      if (n >= 1000) return (n / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
      return String(n);
    }

    // 点过就记住：localStorage 正常场景，cookie 兜住清缓存前的生活
    function likedBefore(cid) {
      try {
        if (localStorage.getItem('whale-liked-' + cid)) return true;
      } catch (e) { /* 隐私模式忽略 */ }
      return document.cookie.indexOf('dsw_liked_' + cid + '=') !== -1;
    }

    btns.forEach(function (btn) {
      var cid = btn.getAttribute('data-like-cid');
      var endpoint = btn.getAttribute('data-like-endpoint') || location.pathname;
      var numEl = btn.querySelector('.like-btn__num');
      var txtEl = btn.querySelector('.like-btn__txt');

      function setLiked(on) {
        btn.classList.toggle('is-liked', on);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
        if (txtEl) txtEl.textContent = on ? '已喜欢' : '喜欢';
      }

      if (likedBefore(cid)) setLiked(true);

      btn.addEventListener('click', function () {
        if (btn.classList.contains('is-liked') || btn.classList.contains('is-busy')) return;
        btn.classList.add('is-busy');

        fetch(endpoint, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: 'action=dsw-like&cid=' + encodeURIComponent(cid)
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (!data || !data.ok) throw new Error('like failed');
            if (numEl) numEl.textContent = data.formatted || fmt(data.likes);
            setLiked(true);
            try { localStorage.setItem('whale-liked-' + cid, '1'); } catch (e) { /* 忽略 */ }
            btn.classList.remove('is-busy');
            if (!reduceMotion) {
              btn.classList.add('just-liked');
              setTimeout(function () { btn.classList.remove('just-liked'); }, 1000);
            }
          })
          .catch(function () {
            btn.classList.remove('is-busy');
            if (txtEl) {
              var old = txtEl.textContent;
              txtEl.textContent = '再试一次';
              setTimeout(function () { txtEl.textContent = old; }, 1600);
            }
          });
      });
    });
  })();
})();