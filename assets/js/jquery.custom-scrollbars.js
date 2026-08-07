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
        p = e("<div />").addClass(i), t = e("<div />").addClass(o), c = e("<div />").addClass(r), e(this).children().appendTo(c), c.appendTo(t), t.appendTo(p), e(this).append(p), (u = e("<div />")).addClass(s), u.css("width", l), u.css("background-color", n.scrollbarTrackColor), u.css("position", "absolute"), u.css("top", 0), u.css("height", "100%"), u.appendTo(p), u.click(function(n) {
          e(this).offset().left;
          var i = e(this).offset().top,
            o = (n.pageX, n.pageY - i),
            r = parseFloat(d.css("top")),
            s = parseFloat(d.css("height")),
            a = t.height() - 40;
          o < r ? t.scrollTop(t.scrollTop() - a) : r + s < o && t.scrollTop(t.scrollTop() + a), n.preventDefault(), n.stopPropagation()
        }), (d = e("<div />")).addClass(a), d.css("width", l), d.css("background-color", n.scrollbarColor), d.css("position", "absolute"), d.css("right", 0), d.appendTo(u);
        var m = !1;
        void 0 !== jQuery.ui && d.draggable({
          containment: "parent",
          start: function() {
            m = !0
          },
          stop: function() {
            m = !1
          },
          drag: function(e, n) {
            t.scrollTop(d.position().top / t.height() * c.height())
          }
        }), e(this).css("overflow", "hidden"), p.css("position", "relative"), t.css("overflow", "auto"), t.css("width", "150%"), t.css("position", "relative"), t.scroll(function() {
          if (!m) {
            var n = t.scrollTop() / c.height() * e(this).height();
            d.css("top", n)
          }
        })
      } else t = e(this).find("div." + o), c = t.find("div." + r), u = e(this).find("div." + s), d = u.find("div." + a);
      u.css("right", 0), c.css("width", f), t.css("height", e(this).height()), c.height() > e(this).height() ? (h = e(this).height() / c.height() * e(this).height(), u.show()) : (h = 0, u.hide()), d.css("height", h), t.scroll()
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
