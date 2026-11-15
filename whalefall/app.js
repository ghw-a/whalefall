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

  /* ---------- 14. 音乐播放器（GD音乐台，服务端代理） ----------
   * 浏览器不直连第三方：歌单 / 音频 / 封面全部走主题自己的 PHP 接口
   * （functions.php 2.6 节），顺便吃到文件缓存，5 分钟 50 次的限流也扛得住。
   * 不自动播放：浏览器策略会拦，也尊重访客。
   * 记住上次听到哪首 / 哪一秒，下次打开还原位置但不自动出声。
   */
  (function () {
    var cfg = window.DSW_MUSIC;
    var player = document.getElementById('player');
    if (!cfg || !player || !window.JSON) return;

    var ENDPOINT = location.pathname || '/';
    var card = document.getElementById('playerCard');
    var trigger = document.getElementById('playerBtn');
    var minBtn = document.getElementById('pMin');
    var audio = document.getElementById('playerAudio');
    var cover = document.getElementById('pCover');
    var elTitle = document.getElementById('pTitle');
    var elArtist = document.getElementById('pArtist');
    var progress = document.getElementById('pProgress');
    var bar = document.getElementById('pBar');
    var elCur = document.getElementById('pCur');
    var elDur = document.getElementById('pDur');
    var playBtn = document.getElementById('pPlay');
    var playIco = document.getElementById('pPlayIco');
    var prevBtn = document.getElementById('pPrev');
    var nextBtn = document.getElementById('pNext');
    var repeatBtn = document.getElementById('pRepeat');
    var muteBtn = document.getElementById('pMute');
    var listBtn = document.getElementById('pListBtn');
    var listEl = document.getElementById('pList');

    var KEY = 'whale-music-';
    var songs = [];
    var cur = -1;
    var singleLoop = false;
    var loading = false;
    var pendingSeek = 0;
    var errStreak = 0;
    var lastSave = 0;

    function store(key, value) {
      try {
        if (typeof value === 'undefined') return localStorage.getItem(KEY + key);
        localStorage.setItem(KEY + key, value);
      } catch (e) { /* 隐私模式忽略 */ }
      return null;
    }

    function fmtTime(sec) {
      sec = Math.max(0, Math.floor(sec || 0));
      var m = Math.floor(sec / 60);
      var s = sec % 60;
      return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function esc(str) {
      return String(str || '').replace(/[&<>"']/g, function (ch) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
      });
    }

    /* ---------- 和主题接口说话 ---------- */
    function api(payload) {
      var parts = ['action=dsw-music'];
      for (var k in payload) {
        if (Object.prototype.hasOwnProperty.call(payload, k)) {
          parts.push(encodeURIComponent(k) + '=' + encodeURIComponent(payload[k]));
        }
      }
      return fetch(ENDPOINT, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: parts.join('&')
      }).then(function (res) {
        if (!res.ok && res.status !== 404) throw new Error('http ' + res.status);
        return res.json();
      });
    }

    function setOpen(open) {
      player.classList.toggle('is-open', open);
      trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
      card.setAttribute('aria-hidden', open ? 'false' : 'true');
      if (open) player.classList.remove('is-list');
    }

    function setPlaying(on) {
      player.classList.toggle('is-playing', on);
      playIco.setAttribute('href', on ? '#i-pause' : '#i-play-solid');
      playBtn.setAttribute('aria-label', on ? '暂停' : '播放');
    }

    function markList() {
      var rows = listEl.querySelectorAll('.plist__item');
      for (var i = 0; i < rows.length; i++) {
        var idx = parseInt(rows[i].getAttribute('data-i'), 10);
        rows[i].classList.toggle('is-cur', idx === cur);
      }
    }

    function renderList() {
      if (!songs.length) {
        listEl.innerHTML = '<p class="player__empty">还没捞到歌喵。点音符重试，或者去后台换个搜索关键词。</p>';
        return;
      }
      var html = '';
      for (var i = 0; i < songs.length; i++) {
        html += '<button type="button" class="plist__item" data-i="' + i + '">' +
          '<span class="plist__no">' + (i + 1) + '</span>' +
          '<span class="plist__eq"><i></i><i></i><i></i></span>' +
          '<span class="plist__name">' + esc(songs[i].name) + '</span>' +
          '<span class="plist__art">' + esc(songs[i].artist) + '</span>' +
          '</button>';
      }
      listEl.innerHTML = html;
      markList();
    }

    function applyMeta(song) {
      elTitle.textContent = song.name || '未命名曲目';
      elArtist.textContent = song.artist || '未知歌手';
    }

    function applyCover(song) {
      if (song && song.pic) {
        cover.style.backgroundImage = 'url("' + song.pic.replace(/"/g, '\\"') + '")';
        cover.classList.add('has-pic');
      } else {
        cover.style.backgroundImage = '';
        cover.classList.remove('has-pic');
      }
    }

    /* 音频地址按需取（服务端缓存 30 分钟） */
    function ensureUrl(song, done) {
      if (song.url) {
        done(true);
        return;
      }
      if (song.fetching) {
        done(false);
        return;
      }
      song.fetching = true;
      api({ do: 'url', id: song.id, source: song.source || cfg.source })
        .then(function (data) {
          song.fetching = false;
          if (data && data.ok && data.url) {
            song.url = data.url;
            done(true);
          } else {
            done(false);
          }
        })
        .catch(function () {
          song.fetching = false;
          done(false);
        });
    }

    /* 封面异步补，失败就留渐变底 */
    function loadPic(song) {
      if (!song || song.pic || !song.picId) {
        applyCover(song);
        return;
      }
      applyCover(null);
      api({ do: 'pic', id: song.picId, source: song.source || cfg.source })
        .then(function (data) {
          if (data && data.ok && data.url) song.pic = data.url;
          if (songs[cur] === song) applyCover(song);
        })
        .catch(function () { /* 没封面就渐变 */ });
    }

    function playAudio() {
      var task = audio.play();
      if (task && typeof task.catch === 'function') {
        task.catch(function () { setPlaying(false); });
      }
    }

    function setSong(index, play) {
      if (!songs.length) return;
      cur = (index % songs.length + songs.length) % songs.length;
      var song = songs[cur];
      errStreak = 0;

      applyMeta(song);
      loadPic(song);
      markList();
      store('index', String(cur));
      store('time', '0');

      bar.style.width = '0%';
      elCur.textContent = '00:00';
      elDur.textContent = '00:00';
      progress.setAttribute('aria-valuenow', '0');

      if (song.url) {
        audio.src = song.url;
        if (play) playAudio();
        return;
      }

      if (!play) return;

      elArtist.textContent = '正在取音频…';
      ensureUrl(song, function (ok) {
        if (!ok) {
          errStreak++;
          if (songs[cur] !== song) return;
          if (errStreak <= 3 && songs.length > 1) {
            setTimeout(function () { setSong(cur + 1, true); }, 600);
          } else {
            elArtist.textContent = '这片海域暂时没信号';
            setPlaying(false);
          }
          return;
        }
        audio.src = song.url;
        playAudio();
      });
    }

    function loadPlaylist() {
      if (loading || songs.length) return;

      /* 主题自带的固定歌单：不走网络，点开就能看到 30 首 */
      if (cfg.list && cfg.list.length) {
        songs = cfg.list;
        loading = false;
        renderList();
        restore();
        return;
      }

      loading = true;
      elArtist.textContent = '正在打捞歌单…';

      api({ do: 'search', q: cfg.keyword, source: cfg.source, count: cfg.count })
        .then(function (data) {
          if (!data || !data.ok || !data.items || !data.items.length) throw new Error('empty');
          songs = data.items;
          loading = false;
          renderList();
          restore();
        })
        .catch(function () {
          loading = false;
          elArtist.textContent = '海域暂时没信号';
          renderList();
        });
    }

    /* 还原上次听到的位置（不自动出声） */
    function restore() {
      var last = parseInt(store('index'), 10);
      if (isNaN(last) || last < 0 || last >= songs.length) last = 0;
      var at = parseFloat(store('time'));
      pendingSeek = isNaN(at) || at < 0 ? 0 : at;
      setSong(last, false);
      if (pendingSeek > 0) elCur.textContent = fmtTime(pendingSeek);
    }

    /* ---------- 交互 ---------- */
    trigger.addEventListener('click', function () {
      var open = !player.classList.contains('is-open');
      setOpen(open);
      if (open) loadPlaylist();
    });

    minBtn.addEventListener('click', function () { setOpen(false); });

    playBtn.addEventListener('click', function () {
      if (!songs.length) {
        loadPlaylist();
        return;
      }
      if (!audio.src) {
        setSong(cur >= 0 ? cur : 0, true);
        return;
      }
      if (audio.paused) playAudio();
      else audio.pause();
    });

    prevBtn.addEventListener('click', function () {
      if (audio.currentTime > 3) {
        audio.currentTime = 0;
        return;
      }
      setSong(cur - 1, true);
    });

    nextBtn.addEventListener('click', function () { setSong(cur + 1, true); });

    audio.addEventListener('loadedmetadata', function () {
      elDur.textContent = fmtTime(audio.duration);
      if (pendingSeek > 0 && isFinite(audio.duration)) {
        audio.currentTime = Math.min(pendingSeek, Math.max(0, audio.duration - 1));
        pendingSeek = 0;
      }
    });

    audio.addEventListener('play', function () { setPlaying(true); });
    audio.addEventListener('pause', function () { setPlaying(false); });

    audio.addEventListener('timeupdate', function () {
      if (!audio.duration || !isFinite(audio.duration)) return;
      var pct = audio.currentTime / audio.duration * 100;
      bar.style.width = pct.toFixed(2) + '%';
      progress.setAttribute('aria-valuenow', String(Math.round(pct)));
      elCur.textContent = fmtTime(audio.currentTime);
      if (Date.now() - lastSave > 3000) {
        lastSave = Date.now();
        store('time', String(Math.round(audio.currentTime)));
      }
    });

    audio.addEventListener('ended', function () {
      if (singleLoop) return;
      setSong(cur + 1, true);
    });

    /* 直链可能过期：先清空 url 重取一次，还失败就跳下一首 */
    audio.addEventListener('error', function () {
      if (!songs.length) return;
      var song = songs[cur];
      if (song && !song.retried) {
        song.retried = true;
        song.url = '';
        setSong(cur, true);
        return;
      }
      errStreak++;
      if (errStreak <= 3 && songs.length > 1) setTimeout(function () { setSong(cur + 1, true); }, 600);
    });

    function seekTo(clientX) {
      if (!audio.duration || !isFinite(audio.duration)) return;
      var rect = progress.getBoundingClientRect();
      var ratio = Math.min(1, Math.max(0, (clientX - rect.left) / rect.width));
      audio.currentTime = audio.duration * ratio;
    }

    progress.addEventListener('click', function (e) { seekTo(e.clientX); });
    progress.addEventListener('keydown', function (e) {
      if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
      e.preventDefault();
      if (!audio.duration) return;
      var delta = e.key === 'ArrowRight' ? 5 : -5;
      audio.currentTime = Math.min(audio.duration, Math.max(0, audio.currentTime + delta));
    });

    listBtn.addEventListener('click', function () {
      var open = player.classList.toggle('is-list');
      listBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) loadPlaylist();
    });

    listEl.addEventListener('click', function (e) {
      var row = e.target;
      while (row && row !== listEl && !row.classList.contains('plist__item')) row = row.parentNode;
      if (!row || row === listEl) return;
      setSong(parseInt(row.getAttribute('data-i'), 10) || 0, true);
    });

    repeatBtn.addEventListener('click', function () {
      singleLoop = !singleLoop;
      audio.loop = singleLoop;
      repeatBtn.classList.toggle('is-on', singleLoop);
      repeatBtn.setAttribute('aria-pressed', singleLoop ? 'true' : 'false');
      repeatBtn.title = singleLoop ? '单曲循环' : '列表循环';
      repeatBtn.setAttribute('aria-label', repeatBtn.title);
      store('loop', singleLoop ? '1' : '0');
    });

    muteBtn.addEventListener('click', function () {
      audio.muted = !audio.muted;
      muteBtn.classList.toggle('is-muted', audio.muted);
      muteBtn.setAttribute('aria-pressed', audio.muted ? 'true' : 'false');
    });

    var baseVol = isNaN(cfg.volume) ? 0.7 : cfg.volume;
    var savedVol = parseFloat(store('volume'));
    audio.volume = isNaN(savedVol) ? Math.min(1, Math.max(0, baseVol)) : Math.min(1, Math.max(0, savedVol));

    if (store('loop') === '1') {
      singleLoop = true;
      audio.loop = true;
      repeatBtn.classList.add('is-on');
      repeatBtn.setAttribute('aria-pressed', 'true');
      repeatBtn.title = '单曲循环';
      repeatBtn.setAttribute('aria-label', '单曲循环');
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && player.classList.contains('is-open')) setOpen(false);
    });

    /* 真正交互过再取歌单，别白占带宽 */
    var prefetched = false;
    function prefetch() {
      if (prefetched) return;
      prefetched = true;
      loadPlaylist();
    }
    trigger.addEventListener('mouseenter', prefetch, { passive: true });
    trigger.addEventListener('focus', prefetch);
  })();
})();