/*!
 * niRvana theme source - jquery.imgcomplete.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解并补齐 IIFE 括号. 压缩丢失注释与局部变量名.
 */
(function(e) {
  e.fn.imgcomplete = function(t) {
    return this.each(function() {
      var n = this,
        i = e(this);
      if (!i.is("img")) return !0;
      var o = new Image;
      return o.src = i.attr("src"), o.complete ? t.call(n, o.width, o.height) : o.onload = function() {
        o.complete && t.call(n, o.width, o.height)
      }, !0
    })
  }
}(jQuery))
