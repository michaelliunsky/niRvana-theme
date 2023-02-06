!(function (e) {
  function t(r) {
    if (n[r]) return n[r].exports;
    var o = (n[r] = { i: r, l: !1, exports: {} });
    return e[r].call(o.exports, o, o.exports, t), (o.l = !0), o.exports;
  }
  var n = {};
  (t.m = e),
    (t.c = n),
    (t.d = function (e, n, r) {
      t.o(e, n) ||
        Object.defineProperty(e, n, {
          configurable: !1,
          enumerable: !0,
          get: r,
        });
    }),
    (t.n = function (e) {
      var n =
        e && e.__esModule
          ? function () {
              return e.default;
            }
          : function () {
              return e;
            };
      return t.d(n, "a", n), n;
    }),
    (t.o = function (e, t) {
      return Object.prototype.hasOwnProperty.call(e, t);
    }),
    (t.p = ""),
    t((t.s = 1));
})([
  function (e, t, n) {
    var r = n(13),
      o = r.Symbol;
    e.exports = o;
  },
  function (e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", { value: !0 });
    var r = n(2),
      o = n.n(r),
      u = n(3),
      c = n.n(u),
      i = wp.i18n,
      l = (i.__, i.setLocaleData, wp.blocks.registerBlockType),
      a = (wp.element.Fragment, wp.editor),
      f =
        (a.RichText,
        a.MediaUpload,
        a.BlockControls,
        a.AlignmentToolbar,
        a.InspectorControls,
        a.InnerBlocks),
      p = wp.components,
      s = (p.Panel, p.PanelBody, ["core/column"]);
    l("pandastudio/needreply", {
      title: "回复可见",
      icon: "format-status",
      category: "pandastudio-block-category",
      description: "此模块内容需用户评论文章后才可显示，此模块内可嵌套其它模块",
      attributes: {},
      edit: function (e) {
        var t = o()(function (e) {
          return c()(e, function () {
            return ["core/column"];
          });
        });
        return wp.element.createElement(
          "div",
          { className: "needreply_wrap" },
          wp.element.createElement(
            "div",
            { className: "needreply_tip" },
            "此模块内容回复可见"
          ),
          wp.element.createElement(f, {
            template: t(1),
            templateLock: "all",
            allowedBlocks: s,
          })
        );
      },
      save: function (e) {
        return wp.element.createElement(
          "div",
          null,
          "[need_reply]",
          wp.element.createElement(f.Content, null),
          "[/need_reply]"
        );
      },
    });
  },
  function (e, t, n) {
    e.exports = function (e, t) {
      function n() {
        var t,
          n,
          i = o,
          l = arguments.length;
        e: for (; i; ) {
          if (i.args.length === arguments.length) {
            for (n = 0; n < l; n++)
              if (i.args[n] !== arguments[n]) {
                i = i.next;
                continue e;
              }
            return (
              i !== o &&
                (i === u && (u = i.prev),
                (i.prev.next = i.next),
                i.next && (i.next.prev = i.prev),
                (i.next = o),
                (i.prev = null),
                (o.prev = i),
                (o = i)),
              i.val
            );
          }
          i = i.next;
        }
        for (t = new Array(l), n = 0; n < l; n++) t[n] = arguments[n];
        return (
          (i = { args: t, val: e.apply(null, t) }),
          o ? ((o.prev = i), (i.next = o)) : (u = i),
          c === r ? ((u = u.prev), (u.next = null)) : c++,
          (o = i),
          i.val
        );
      }
      var r,
        o,
        u,
        c = 0;
      return (
        t && t.maxSize && (r = t.maxSize),
        (n.clear = function () {
          (o = null), (u = null), (c = 0);
        }),
        n
      );
    };
  },
  function (e, t, n) {
    function r(e, t) {
      if ((e = c(e)) < 1 || e > i) return [];
      var n = l,
        r = a(e, l);
      (t = u(t)), (e -= l);
      for (var f = o(r, t); ++n < e; ) t(n);
      return f;
    }
    var o = n(4),
      u = n(5),
      c = n(7),
      i = 9007199254740991,
      l = 4294967295,
      a = Math.min;
    e.exports = r;
  },
  function (e, t) {
    function n(e, t) {
      for (var n = -1, r = Array(e); ++n < e; ) r[n] = t(n);
      return r;
    }
    e.exports = n;
  },
  function (e, t, n) {
    function r(e) {
      return "function" == typeof e ? e : o;
    }
    var o = n(6);
    e.exports = r;
  },
  function (e, t) {
    function n(e) {
      return e;
    }
    e.exports = n;
  },
  function (e, t, n) {
    function r(e) {
      var t = o(e),
        n = t % 1;
      return t === t ? (n ? t - n : t) : 0;
    }
    var o = n(8);
    e.exports = r;
  },
  function (e, t, n) {
    function r(e) {
      if (!e) return 0 === e ? e : 0;
      if ((e = o(e)) === u || e === -u) {
        return (e < 0 ? -1 : 1) * c;
      }
      return e === e ? e : 0;
    }
    var o = n(9),
      u = 1 / 0,
      c = 1.7976931348623157e308;
    e.exports = r;
  },
  function (e, t, n) {
    function r(e) {
      if ("number" == typeof e) return e;
      if (u(e)) return c;
      if (o(e)) {
        var t = "function" == typeof e.valueOf ? e.valueOf() : e;
        e = o(t) ? t + "" : t;
      }
      if ("string" != typeof e) return 0 === e ? e : +e;
      e = e.replace(i, "");
      var n = a.test(e);
      return n || f.test(e) ? p(e.slice(2), n ? 2 : 8) : l.test(e) ? c : +e;
    }
    var o = n(10),
      u = n(11),
      c = NaN,
      i = /^\s+|\s+$/g,
      l = /^[-+]0x[0-9a-f]+$/i,
      a = /^0b[01]+$/i,
      f = /^0o[0-7]+$/i,
      p = parseInt;
    e.exports = r;
  },
  function (e, t) {
    function n(e) {
      var t = typeof e;
      return null != e && ("object" == t || "function" == t);
    }
    e.exports = n;
  },
  function (e, t, n) {
    function r(e) {
      return "symbol" == typeof e || (u(e) && o(e) == c);
    }
    var o = n(12),
      u = n(18),
      c = "[object Symbol]";
    e.exports = r;
  },
  function (e, t, n) {
    function r(e) {
      return null == e
        ? void 0 === e
          ? l
          : i
        : a && a in Object(e)
        ? u(e)
        : c(e);
    }
    var o = n(0),
      u = n(16),
      c = n(17),
      i = "[object Null]",
      l = "[object Undefined]",
      a = o ? o.toStringTag : void 0;
    e.exports = r;
  },
  function (e, t, n) {
    var r = n(14),
      o = "object" == typeof self && self && self.Object === Object && self,
      u = r || o || Function("return this")();
    e.exports = u;
  },
  function (e, t, n) {
    (function (t) {
      var n = "object" == typeof t && t && t.Object === Object && t;
      e.exports = n;
    }.call(t, n(15)));
  },
  function (e, t) {
    var n;
    n = (function () {
      return this;
    })();
    try {
      n = n || Function("return this")() || (0, eval)("this");
    } catch (e) {
      "object" == typeof window && (n = window);
    }
    e.exports = n;
  },
  function (e, t, n) {
    function r(e) {
      var t = c.call(e, l),
        n = e[l];
      try {
        e[l] = void 0;
        var r = !0;
      } catch (e) {}
      var o = i.call(e);
      return r && (t ? (e[l] = n) : delete e[l]), o;
    }
    var o = n(0),
      u = Object.prototype,
      c = u.hasOwnProperty,
      i = u.toString,
      l = o ? o.toStringTag : void 0;
    e.exports = r;
  },
  function (e, t) {
    function n(e) {
      return o.call(e);
    }
    var r = Object.prototype,
      o = r.toString;
    e.exports = n;
  },
  function (e, t) {
    function n(e) {
      return null != e && "object" == typeof e;
    }
    e.exports = n;
  },
]);
