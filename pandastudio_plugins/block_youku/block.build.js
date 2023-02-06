!(function (e) {
  function t(n) {
    if (r[n]) return r[n].exports;
    var a = (r[n] = { i: n, l: !1, exports: {} });
    return e[n].call(a.exports, a, a.exports, t), (a.l = !0), a.exports;
  }
  var r = {};
  (t.m = e),
    (t.c = r),
    (t.d = function (e, r, n) {
      t.o(e, r) ||
        Object.defineProperty(e, r, {
          configurable: !1,
          enumerable: !0,
          get: n,
        });
    }),
    (t.n = function (e) {
      var r =
        e && e.__esModule
          ? function () {
              return e.default;
            }
          : function () {
              return e;
            };
      return t.d(r, "a", r), r;
    }),
    (t.o = function (e, t) {
      return Object.prototype.hasOwnProperty.call(e, t);
    }),
    (t.p = ""),
    t((t.s = 0));
})([
  function (e, t) {
    var r = wp.i18n,
      n = (r.__, r.setLocaleData, wp.blocks.registerBlockType),
      a = wp.element.Fragment,
      o = wp.editor,
      l =
        (o.RichText,
        o.MediaUpload,
        o.BlockControls,
        o.AlignmentToolbar,
        o.InspectorControls),
      i = (o.InnerBlocks, wp.components),
      c = (i.Panel, i.PanelBody, i.RadioControl, i.TextControl);
    n("pandastudio/youku", {
      title: "优酷视频",
      icon: function () {
        return wp.element.createElement(
          "svg",
          {
            xmlns: "http://www.w3.org/2000/svg",
            width: "39",
            height: "38",
            viewBox: "0 0 39 38",
          },
          wp.element.createElement("path", {
            id: "椭圆_1_拷贝_2",
            "data-name": "椭圆 1 拷贝 2",
            class: "cls-1",
            d: "M5.86,6.729a2.969,2.969,0,1,1,0,5.938A2.969,2.969,0,1,1,5.86,6.729ZM32.938,24.74a2.969,2.969,0,1,1-3.031,2.969A3,3,0,0,1,32.938,24.74Zm5.838-4.645a18.7,18.7,0,0,1-1.67,6.74,4.32,4.32,0,0,0-4.269-3.48,4.448,4.448,0,0,0-.89.09,12.746,12.746,0,0,0,.8-5.017A13.367,13.367,0,0,0,10.1,9.719c0-.04.006-0.08,0.006-0.12A4.291,4.291,0,0,0,6,5.356C6.1,5.269,6.183,5.181,6.279,5.1A19.249,19.249,0,0,1,20.491.048,19.1,19.1,0,0,1,38.776,20.095ZM13.542,11.034a3.138,3.138,0,0,1,4.368-.726c2.546,1.7,5.083,3.422,7.626,5.131,0.555,0.372,1.133.711,1.679,1.095a2.9,2.9,0,0,1,.007,4.871c-1.831,1.156-3.6,2.4-5.435,3.551-1.284.8-2.5,1.757-3.765,2.622a3.122,3.122,0,0,1-4.456-.591,3,3,0,0,1,1.073-4.361c1.8-1.2,3.6-2.381,5.494-3.631-1.945-1.306-3.776-2.543-5.615-3.768A2.917,2.917,0,0,1,13.542,11.034Zm-7.783,2.82a4.406,4.406,0,0,0,1.588-.3,12.409,12.409,0,0,0-1.176,5.778c0.05,7.259,6.661,13.031,13.9,12.677a13.525,13.525,0,0,0,8.5-3.58,4.323,4.323,0,0,0,4.262,3.433,4.431,4.431,0,0,0,.984-0.114A19.714,19.714,0,0,1,19.466,38,19.057,19.057,0,0,1,1.738,11.208,4.345,4.345,0,0,0,5.759,13.854Z",
          })
        );
      },
      category: "pandastudio-block-category",
      description: "插入优酷视频",
      attributes: { ratio: {}, iframe: {} },
      edit: function (e) {
        var t = e.attributes,
          r = t.ratio,
          n = t.iframe,
          o = e.setAttributes;
        e.isSelected;
        return (
          r || o({ ratio: "16vs9" }),
          wp.element.createElement(
            a,
            null,
            wp.element.createElement(
              l,
              null,
              wp.element.createElement(
                "div",
                { className: "pf_inspectorTitle" },
                "请粘贴嵌入代码"
              ),
              wp.element.createElement(c, {
                value: n,
                onChange: function (e) {
                  return o({ iframe: e });
                },
                placeholder: "点击视频下方的分享->复制“嵌入代码”",
                help: "注意：这里粘贴的任何html都将当做代码来执行，请不要填写不安全的代码！",
              })
            ),
            wp.element.createElement("div", {
              className: "youku_video_wrap",
              ratio: r,
            })
          )
        );
      },
      save: function (e) {
        var t = e.attributes,
          r = t.ratio,
          n = t.iframe;
        return (
          (n = n.replace(
            "player.youku.com/player.html?",
            "player.youku.com/player.html?high_quality=1&"
          )),
          wp.element.createElement(
            "div",
            { className: "youku_video_wrap", ratio: r },
            wp.element.createElement("figure", {
              dangerouslySetInnerHTML: { __html: n },
            })
          )
        );
      },
    });
  },
]);
