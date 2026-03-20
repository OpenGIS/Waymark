jQuery(function ($) {
  // Determine asset root relative to current page
  var scripts = document.getElementsByTagName("script");
  var docsJsPath = "";
  for (var i = 0; i < scripts.length; i++) {
    if (scripts[i].src && scripts[i].src.indexOf("docs.js") !== -1) {
      docsJsPath = scripts[i].src;
      break;
    }
  }
  // assets/js/docs.js → assets/
  var assetRoot = docsJsPath.replace(/assets\/js\/docs\.js.*$/, "assets/");

  var header = [
    '<header id="docs-header">',
    '  <a href="' +
      assetRoot +
      '../index.html" id="docs-header__logo" title="Waymark Docs">',
    '    <img src="' +
      assetRoot +
      'img/waymark-icon-primary.png" alt="Waymark" />',
    '    <span>Waymark for WordPress</span>',
    "  </a>",
    "</header>",
  ].join("\n");

  var footer = [
    '<footer id="docs-footer">',
    '  <nav id="docs-footer__nav">',
    '    <a href="https://wordpress.org/plugins/waymark/" target="_blank">WordPress</a>',
    '    <a href="https://www.morehawes.ca" target="_blank" id="docs-header__author" title="Joe Hawes">',
    '      <img src="https://www.morehawes.ca/assets/images/Joe1BW.jpg" alt="Joe Hawes" />',
    "    </a>",
    '    <a href="https://github.com/OpenGIS/Waymark" target="_blank">GitHub</a>',
    "  </nav>",
    "</footer>",
  ].join("\n");

  $("body").prepend(header).append(footer);
});

// Shiki syntax highlighting — runs on any page with data-lang code blocks
if (document.querySelector("pre > code[data-lang]")) {
  var shikiScript = document.createElement("script");
  shikiScript.type = "module";
  shikiScript.textContent = [
    'import { codeToHtml } from "https://esm.sh/shiki@3.0.0";',
    'for (const code of document.querySelectorAll("pre > code[data-lang]")) {',
    '  const lang = code.dataset.lang;',
    '  const pre = code.closest("pre");',
    '  try {',
    '    const html = await codeToHtml(code.innerText, { lang, theme: "ayu-dark" });',
    '    pre.outerHTML = html;',
    '  } catch (e) {',
    '    console.warn("Shiki failed for lang:", lang, e);',
    '  }',
    '}',
  ].join("\n");
  document.head.appendChild(shikiScript);
}
