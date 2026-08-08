/*!
 * niRvana theme source - jquery.custom-scrollbars.js
 * 反解自 assets/minify/app.min.js (2026-08-06)
 * 自定义源码, 从生产压缩包反解并补齐 IIFE 括号. 压缩丢失注释与局部变量名.
 */
(function(e) {
  e.fn.custom_scrollbar = function(t) {
    var n = e.fn.custom_scrollbar.config;
    void 0 !== t && null != t && (n = jQuery.extend({}, n, t));
    var i = n.wrapperClassName,
      o = n.contentWrapperClassName,
      r = n.contentClassName,
      s = n.scrollbarTrackClassName,
      a = n.scrollbarClassName,
      l = n.scrollbarWidth;
    return n.scrollbarTrackSpacing, e(this).each(function() {
      var t, c, u, d, h, p = e(this).find("div." + i),
        f = e(this).outerWidth();
      if (0 == p.length) {
        p = e("<div />").addClass(i), t = e("<div />").addClass(o), c = e("<div />").addClass(r), e(this).children().appendTo(c), c.appendTo(t), t.appendTo(p), e(this).append(p), (u = e("<div />")).addClass(s), u.css("width", l), u.css("background-color", n.scrollbarTrackColor), u.css("position", "absolute"), u.css("top", 0), u.css("height", "100%"), u.appendTo(p), u.on("click", function(n) {
          e(this).offset().left;
          var i = e(this).offset().top,
            o = (n.pageX, n.pageY - i),
            r = parseFloat(d.css("top")),
            s = parseFloat(d.css("height")),
            a = t.height() - 40;
          o < r ? t.scrollTop(t.scrollTop() - a) : r + s < o && t.scrollTop(t.scrollTop() + a), n.preventDefault(), n.stopPropagation()
        }), (d = e("<div />")).addClass(a), d.css("width", l), d.css("background-color", n.scrollbarColor), d.css("position", "absolute"), d.css("right", 0), d.appendTo(u);
        var m = !1;
        // 滚动条拖拽 (Pointer Events, 替代 jQuery UI draggable)
        (function() {
          var dragging = !1,
            startY = 0,
            startTop = 0;
          d.on("pointerdown", function(e) {
            var n = e.originalEvent;
            if (n.button > 0) return;
            dragging = !0, m = !0, startY = n.clientY, startTop = parseFloat(d.css("top")) || 0;
            var el = d.get(0);
            el.setPointerCapture && el.setPointerCapture(n.pointerId), n.preventDefault()
          });
          d.on("pointermove", function(e) {
            if (!dragging) return;
            var n = e.originalEvent,
              max = u.height() - d.height(),
              top = startTop + (n.clientY - startY);
            d.css("top", Math.max(0, Math.min(max, top))), t.scrollTop(d.position().top / t.height() * c.height())
          });
          d.on("pointerup", function() {
            dragging = !1, m = !1
          });
          d.on("pointercancel", function() {
            dragging = !1, m = !1
          })
        })(), e(this).css("overflow", "hidden"), p.css("position", "relative"), t.css("overflow", "auto"), t.css("width", "150%"), t.css("position", "relative"), t.on("scroll", function() {
          if (!m) {
            var n = t.scrollTop() / c.height() * e(this).height();
            d.css("top", n)
          }
        })
      } else t = e(this).find("div." + o), c = t.find("div." + r), u = e(this).find("div." + s), d = u.find("div." + a);
      u.css("right", 0), c.css("width", f), t.css("height", e(this).height()), c.height() > e(this).height() ? (h = e(this).height() / c.height() * e(this).height(), u.show()) : (h = 0, u.hide()), d.css("height", h), t.trigger("scroll")
    })
  }, e.fn.custom_scrollbar.config = {
    wrapperClassName: "custom-scrollbar-wrapper",
    contentWrapperClassName: "custom-scrollbar-content-wrapper",
    contentClassName: "custom-scrollbar-content",
    scrollbarTrackClassName: "custom-scrollbar-track",
    scrollbarClassName: "custom-scrollbar",
    scrollbarWidth: 6,
    scrollbarTrackSpacing: 4,
    scrollbarColor: "#000000",
    scrollbarTrackColor: "#CCCCCC"
  }
}(jQuery))
