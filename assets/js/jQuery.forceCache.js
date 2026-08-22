/*!
 * niRvana theme source - jQuery.forceCache.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解. 压缩丢失注释与局部变量名.
 */
$.ajaxPrefilter(function(e, t, n) {
  if (t.forceCache) {
    var i = {
        url: t.url,
        data: t.data,
        type: t.type
      },
      o = JSON.stringify(i);
    "object" != typeof window._ajaxForceCache && (window._ajaxForceCache = {}), window._ajaxForceCache[o] || (e.success = function(e) {
      window._ajaxForceCache[o] = JSON.stringify(e)
    })
  }
}), $.ajaxTransport("+*", function(e, t, n) {
  var i = t.forceCache,
    o = {
      url: t.url,
      data: t.data,
      type: t.type
    },
    r = JSON.stringify(o);
  "object" != typeof window._ajaxForceCache && (window._ajaxForceCache = {});
  var s = window._ajaxForceCache[r];
  if (s && 1 == i) return {
    send: function(e, t) {
      var n = {};
      n.json = JSON.parse(s), t(200, "success", n, "")
    },
    abort: function() {}
  }
})
