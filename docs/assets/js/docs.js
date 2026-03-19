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
    "    <span>Waymark</span>",
    "  </a>",
    '  <nav id="docs-header__nav">',
    '    <a href="https://wordpress.org/plugins/waymark/" target="_blank" title="WordPress Plugin Directory">',
    "      WordPress",
    "    </a>",
    '    <a href="https://github.com/OpenGIS/Waymark" target="_blank" title="GitHub Repository">',
    "      GitHub",
    "    </a>",

    "  </nav>",
    "</header>",
  ].join("\n");

  var footer = [
    '<footer id="docs-footer">',
    '  <nav id="docs-footer__nav">',
    '    <a href="' + assetRoot + '../index.html">Docs</a>',
    '    <a href="https://wordpress.org/plugins/waymark/" target="_blank">WordPress</a>',
    '    <a href="https://www.morehawes.ca" target="_blank" id="docs-header__author" title="Joe Hawes">',
    '      <img src="https://www.morehawes.ca/assets/images/Joe1BW.jpg" alt="Joe Hawes" />',
    "    </a>",
    '    <a href="https://downloads.wordpress.org/plugin/waymark.zip">Download</a>',
    '    <a href="https://github.com/OpenGIS/Waymark" target="_blank">GitHub</a>',
    "  </nav>",
    "</footer>",
  ].join("\n");

  $("body").prepend(header).append(footer);
});
