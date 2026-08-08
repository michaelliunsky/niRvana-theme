/*!
 * niRvana theme source - jv-element.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
window.add_filter = function(e, t, n) {
  if (!e) return !1;
  if ("function" != typeof t) return !1;
  null != n && "number" == typeof n || (n = 10), window.Panda_Hook || (window.Panda_Hook = {}), window.Panda_Hook[e] || (window.Panda_Hook[e] = []);
  for (var i = window.Panda_Hook[e], o = 0, r = 0; r < i.length; r++)
    if (i[r].priority <= n && (o = r + 1), i[r].callback === t) return !1;
  i.splice(o, 0, {
    callback: t,
    priority: n
  })
}, window.add_action = function(e, t, n) {
  return add_filter(e, t, n)
}, window.do_action = function(e, t) {
  if (!window.Panda_Hook) return t;
  var n = window.Panda_Hook[e];
  if (!n) return !1;
  for (var i = 0; i < n.length; i++) n[i].callback(t)
}, window.apply_filters = function(e, t) {
  if (!window.Panda_Hook) return t;
  var n = window.Panda_Hook[e];
  if (!n) return t;
  for (var i = 0; i < n.length; i++) t = n[i].callback(t);
  return t
}, jQVue.prototype.$renderer = {}, jQVue.prototype.$writeBack = {}, jQVue.prototype.$beforeMounted = {}, jQVue.prototype.$beforeMounted.jv_element = function(e) {
  function t(e) {
    e = e || 32;
    var t = "qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ",
      n = t.length,
      o = "";
    for (i = 0; i < e; i++) o += t.charAt(Math.floor(Math.random() * n));
    return o
  }
  e = e || "", $(e + " .jv-switcher").after(function() {
    if ("done" != $(this).data("jv_element")) {
      var e = t();
      return $(this).attr("id", e), $(this).data("jv_element", "done"), '<label for="' + e + '"></label>'
    }
  }), $(e + " .jv-checkbox").after(function() {
    if ("done" != $(this).data("jv_element")) {
      var e = t();
      $(this).attr("id", e);
      var n = $(this).attr("label") || "";
      return $(this).data("jv_element", "done"), '<label for="' + e + '"></label><label for="' + e + '">' + n + "</label>"
    }
  })
}, jQVue.prototype.$isHuman = {
  temp: {
    time: 0,
    pageX: 0,
    pageY: 0
  },
  clickTimer: [],
  tapTimer: [],
  mouseMove: 0,
  check: function() {
    for (var e = jQVue.prototype.$isHuman.is_Mobile() || jQVue.prototype.$isHuman.is_mac() ? jQVue.prototype.$isHuman.tapTimer : jQVue.prototype.$isHuman.clickTimer, t = 0, n = 0, i = 0; i < e.length; i++) 1 < e[i] && e[i] < 2e3 ? t++ : n++;
    return jQVue.prototype.$isHuman.is_Mobile() ? n < t && 2 <= t + n : n < t && 2 <= t + n && 0 < jQVue.prototype.$isHuman.mouseMove
  },
  is_Mobile: function() {
    var e = navigator.userAgent;
    return navigator.appVersion, !!(!!e.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) | (-1 < e.indexOf("Android") || -1 < e.indexOf("Linux")) | -1 < e.indexOf("iPhone") | -1 < e.indexOf("iPad"))
  },
  is_mac: function() {
    return -1 < navigator.userAgent.indexOf("Macintosh")
  }
}, jQVue.prototype.$beforeMounted.isHuman = function() {
  window.setTimeout(function() {
    $("body").on("mousedown", function(e) {
      jQVue.prototype.$isHuman.temp.time = (new Date).getTime(), jQVue.prototype.$isHuman.temp.pageX = e.pageX, jQVue.prototype.$isHuman.temp.pageY = e.pageY
    }).on("mouseup", function(e) {
      if (jQVue.prototype.$isHuman.temp.pageX == e.pageX && jQVue.prototype.$isHuman.temp.pageY == e.pageY) {
        var t = (new Date).getTime();
        jQVue.prototype.$isHuman.clickTimer.push(t - jQVue.prototype.$isHuman.temp.time)
      }
    }), $("body").on("click", function() {
      jQVue.prototype.$isHuman.tapTimer.push(100)
    }), $("body").on("mousemove", "#cmt_form .submit-comment,#cmt_form .comment-input", function() {
      jQVue.prototype.$isHuman.mouseMove += 1
    })
  }, 0)
}
