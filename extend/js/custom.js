/**
 * 获取Cookie过期时间（当天结束）
 * @returns {string} GMT格式的过期时间字符串
 */
function getCookieExpireTime() {
  const endOfDay = new Date();
  endOfDay.setHours(23, 59, 59, 999);
  return endOfDay.toGMTString();
}

/**
 * 应用深色模式状态
 * @param {boolean} isNight 是否为深色模式
 */
function applyNightMode(isNight) {
  document.body.classList.toggle("night", isNight);
  if ($(".colorSwitch").length) {
    $(".colorSwitch").attr(
      "class",
      `colorSwitch fa ${isNight ? "fa-sun" : "fas fa-moon"}`
    );
  }
}

/**
 * 切换深色模式
 */
function switchNightMode() {
  const nightMode =
    document.cookie.replace(
      /(?:(?:^|.*;\s*)night\s*=\s*([^;]*).*$)|^.*$/,
      "$1"
    ) || "0";
  const isNight = nightMode === "0";
  applyNightMode(isNight);
  document.cookie = `night=${
    isNight ? "1" : "0"
  };path=/;expires=${getCookieExpireTime()}`;
}

/**
 * 初始化深色模式
 */
function initNightMode() {
  const framework = window.pandastudio_framework;
  const darkModeConfig = (framework && framework.dark_mode) || {};
  const {
    enable = "unchecked",
    auto = "unchecked",
    time_start = "19:00",
    time_end = "07:00",
  } = darkModeConfig;

  if (enable !== "checked") {
    document.cookie = "night=0;path=/;expires=" + getCookieExpireTime();
    return applyNightMode(false);
  }

  const nightModeCookie = document.cookie.replace(
    /(?:(?:^|.*;\s*)night\s*=\s*([^;]*).*$)|^.*$/,
    "$1"
  );
  if (nightModeCookie) return applyNightMode(nightModeCookie === "1");

  if (auto === "checked") {
    const checkAutoNight = () => {
      const now = new Date();
      const [startH, startM] = time_start.split(":").map(Number);
      const [endH, endM] = time_end.split(":").map(Number);
      const nowMinutes = now.getHours() * 60 + now.getMinutes();
      const startMinutes = startH * 60 + startM;
      const endMinutes = endH * 60 + endM;

      const isNight =
        startMinutes > endMinutes
          ? nowMinutes >= startMinutes || nowMinutes < endMinutes
          : nowMinutes >= startMinutes && nowMinutes < endMinutes;

      applyNightMode(isNight);
    };

    checkAutoNight();
    setInterval(checkAutoNight, 60000);
  } else {
    document.cookie = `night=0;path=/;expires=${getCookieExpireTime()}`;
    applyNightMode(false);
  }
}

/**
 * 为代码块添加复制按钮
 * @param {HTMLElement} codeBlock - 代码块容器元素
 */
function addCopyButtonToCodeBlock(codeBlock) {
  const codeElement = codeBlock.querySelector("code");
  const copyButton = document.createElement("button");
  const buttonText = document.createTextNode("Copy Code");

  copyButton.appendChild(buttonText);
  copyButton.style.cssText = `
    position: absolute;
    top: 0;
    right: 0;
    margin: 4px;
    padding: 4px 8px;
    font-size: 12px;
    background-color: rgba(200, 200, 200, 0.2);
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    z-index: 1000;
  `;

  // 鼠标悬停效果
  copyButton.addEventListener("mouseenter", () => {
    copyButton.style.backgroundColor = "rgba(0, 0, 0, 0.1)";
  });

  copyButton.addEventListener("mouseleave", () => {
    copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
  });

  // 复制功能
  copyButton.addEventListener("click", () => {
    const range = document.createRange();
    range.selectNode(codeElement);

    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    document.execCommand("copy");
    selection.removeAllRanges();

    // 更新按钮状态
    copyButton.innerText = "Copied!";
    copyButton.style.backgroundColor = "#333";
    copyButton.style.color = "#fff";

    // 3秒后恢复原始状态
    setTimeout(() => {
      copyButton.innerText = "Copy Code";
      copyButton.style.backgroundColor = "rgba(200, 200, 200, 0.2)";
      copyButton.style.color = "#fff";
    }, 3000);
  });

  // 添加复制按钮到代码块
  codeBlock.style.position = "relative";
  codeBlock.insertBefore(copyButton, codeElement);
}

/**
 * 初始化代码块样式和功能
 */
function initCodeBlocks() {
  // 为所有代码块添加复制按钮
  document.querySelectorAll(".wp-block-code").forEach(addCopyButtonToCodeBlock);

  // 设置代码块样式
  document.querySelectorAll(".wp-block-code pre").forEach((preElement) => {
    preElement.style.color = "#fff";
  });
}

/**
 * 初始化评论区域代码高亮
 */
function initCommentCodeHighlight() {
  $(".comments pre").each(function (_, element) {
    const codeElement = $(element).children("code");
    const targetElement = codeElement.length > 0 ? codeElement.get(0) : element;

    if (
      !$(targetElement).hasClass("hljs") &&
      !$(targetElement).parent().hasClass("disable_highlightjs")
    ) {
      hljs.highlightBlock(targetElement);
      hljs.lineNumbersBlock(targetElement, { singleLine: true });
    }
  });
}

/**
 * 初始化卡片悬停动画
 */
function initCardHoverAnimations() {
  jQuery(document).ready(($) => {
    $(".postLists.lists .card h2, .pf_hotposts h4").hover(
      function () {
        $(this).stop().animate({ marginLeft: "15px" }, 300);
      },
      function () {
        $(this).stop().animate({ marginLeft: "0px" }, 300);
      }
    );

    // 点击动画
    $(".postLists.lists .card h2, .pf_hotposts h4").click(function () {
      const originalText = this.textContent;
      $(this).text("正在努力加载中 …");

      setTimeout(() => {
        $(this).text(originalText);
      }, 2011);
    });
  });
}

// 主初始化函数
document.addEventListener("DOMContentLoaded", () => {
  initCodeBlocks();
  initCommentCodeHighlight();
  initCardHoverAnimations();
  initNightMode();
});

// AJAX加载完成后重新初始化代码块
add_action("ajax_render_complete", () => {
  initCodeBlocks();

  // 重新设置代码块样式
  document.querySelectorAll(".wp-block-code pre").forEach((preElement) => {
    preElement.style.color = "#fff";
  });
});
