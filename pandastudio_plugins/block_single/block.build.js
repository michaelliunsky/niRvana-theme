!(function (e) {
  function t(l) {
    if (n[l]) return n[l].exports;
    var r = (n[l] = { i: l, l: !1, exports: {} });
    return e[l].call(r.exports, r, r.exports, t), (r.l = !0), r.exports;
  }
  var n = {};
  (t.m = e),
    (t.c = n),
    (t.d = function (e, n, l) {
      t.o(e, n) ||
        Object.defineProperty(e, n, {
          configurable: !1,
          enumerable: !0,
          get: l,
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
    t((t.s = 0));
})([
  function (e, t) {
    var n = wp.i18n,
      l = (n.__, n.setLocaleData, wp.blocks.registerBlockType),
      r = wp.element.Fragment,
      a = wp.editor,
      i = (a.RichText, a.MediaUpload, a.BlockControls),
      o = a.BlockAlignmentToolbar,
      c = a.InspectorControls,
      u = wp.components,
      s = u.Button,
      p = u.Modal,
      m = u.TextControl,
      d = u.Spinner,
      f = u.ServerSideRender,
      w = u.PanelBody,
      g = u.Disabled;
    wp.compose.withState;
    l("pandastudio/single", {
      title: "文章展示",
      icon: "format-aside",
      category: "pandastudio-block-category",
      description: "用于展示文章链接，部分类型的文章可能无法显示",
      supports: { html: !1 },
      attributes: { post_id: {}, align: {} },
      getEditWrapperProps: function (e) {
        var t = e.align;
        if (["left", "center", "right", "wide", "full"].includes(t))
          return { "data-align": t };
      },
      edit: function (e) {
        var t = e.attributes,
          n = t.post_id,
          l = t.showModal,
          a = t.searchText,
          u = t.list,
          E = t.isSearching,
          v = t.align,
          h = e.setAttributes;
        u || h({ list: [] });
        var y = function () {
          h({ isSearching: !0 }),
            jQuery
              .ajax({
                url:
                  pandastudio_framework.route +
                  "pandastudio/framework/wp_query",
                type: "POST",
                beforeSend: function (e) {
                  e.setRequestHeader("X-WP-Nonce", pandastudio_framework.nonce);
                },
                data: JSON.stringify({ keyword: a }),
              })
              .done(function (e) {
                h({ list: e });
              })
              .fail(function () {
                alert("异常错误！请联系开发者解决此问题");
              })
              .always(function () {
                h({ isSearching: !1 });
              });
        };
        return wp.element.createElement(
          r,
          null,
          wp.element.createElement(
            i,
            null,
            wp.element.createElement(o, {
              value: v,
              onChange: function (e) {
                return h({ align: e });
              },
            })
          ),
          wp.element.createElement(
            c,
            null,
            wp.element.createElement(
              w,
              { title: "设置文章ID" },
              wp.element.createElement(
                s,
                {
                  isPrimary: !0,
                  onClick: function () {
                    return h({ showModal: !0 });
                  },
                },
                n ? "更改文章ID" : "选择文章ID"
              ),
              n
                ? wp.element.createElement(
                    "p",
                    { style: { fontStyle: "italic", marginTop: "5px" } },
                    "已选文章ID：",
                    n
                  )
                : null,
              l
                ? wp.element.createElement(
                    p,
                    {
                      title: "选择文章ID",
                      onRequestClose: function () {
                        return h({ showModal: !1 });
                      },
                    },
                    wp.element.createElement(
                      "div",
                      { class: "clearfix" },
                      wp.element.createElement(
                        "div",
                        { class: "floatL" },
                        wp.element.createElement(m, {
                          value: a,
                          onChange: function (e) {
                            return h({ searchText: e });
                          },
                          placeholder: "请输入文章关键字...",
                        })
                      ),
                      wp.element.createElement(
                        "div",
                        { class: "floatR marginT2 marginL5" },
                        wp.element.createElement(
                          s,
                          { isDefault: !0, onClick: y },
                          "检索文章"
                        )
                      )
                    ),
                    wp.element.createElement(
                      "div",
                      { class: "marginT10 marginB10" },
                      E
                        ? wp.element.createElement(d, null)
                        : u.length > 0
                        ? wp.element.createElement(
                            "div",
                            null,
                            "请选择：",
                            u.map(function (e, t) {
                              return wp.element.createElement(
                                "div",
                                { class: "marginB5 marginT5" },
                                wp.element.createElement(
                                  s,
                                  {
                                    isDefault: !0,
                                    isPrimary: e.value == n,
                                    onClick: function () {
                                      return h({ post_id: e.value });
                                    },
                                  },
                                  e.label
                                )
                              );
                            })
                          )
                        : "暂无文章"
                    )
                  )
                : null
            )
          ),
          wp.element.createElement(
            g,
            null,
            wp.element.createElement(f, {
              block: "pandastudio/single",
              attributes: { post_id: n, align: v },
            })
          )
        );
      },
      save: function () {
        return null;
      },
    });
  },
]);
