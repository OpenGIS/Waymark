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
    '  <a href="' + assetRoot + '../index.html" id="docs-header__logo" title="Waymark Docs">',
    '    <img src="' + assetRoot + 'img/waymark-icon-primary.png" alt="Waymark" />',
    "    <span>Waymark</span>",
    "  </a>",
    '  <nav id="docs-header__nav">',
    '    <a href="https://wordpress.org/plugins/waymark/" target="_blank" title="WordPress Plugin Directory">',
    '      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18"><path d="M10 .4C4.698.4.4 4.698.4 10s4.298 9.6 9.6 9.6 9.6-4.298 9.6-9.6S15.302.4 10 .4zM1.8 10c0-1.33.285-2.594.794-3.734L5.82 16.24C3.444 15.026 1.8 12.7 1.8 10zm8.2 8.2c-.9 0-1.768-.13-2.59-.37l2.75-7.99 2.82 7.73c.018.044.04.085.062.124A8.383 8.383 0 0110 18.2zm1.167-12.39l2.48 7.37-.69 2.3L10.3 7.81h.867zM10 5.13c-.43 0-.823.016-1.183.046l1.25 3.43L8.56 12.2 5.9 4.7A8.38 8.38 0 0110 3.8c1.618 0 3.124.46 4.4 1.254l-.46 1.505A6.589 6.589 0 0010 5.13zm6.052 9.107l-2.44-7.16.44-1.464A8.19 8.19 0 0118.2 10c0 1.597-.45 3.09-1.228 4.36l-.92-1.124z"/></svg>',
    "      WordPress",
    "    </a>",
    '    <a href="https://github.com/morehawes/waymark" target="_blank" title="GitHub Repository">',
    '      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>',
    "      GitHub",
    "    </a>",
    '    <a href="https://www.morehawes.ca" target="_blank" id="docs-header__author" title="Joe Morehawes">',
    '      <img src="https://www.morehawes.ca/assets/images/Joe1BW.jpg" alt="Joe Morehawes" />',
    "    </a>",
    "  </nav>",
    "</header>",
  ].join("\n");

  var footer = [
    '<footer id="docs-footer">',
    '  <nav id="docs-footer__nav">',
    '    <a href="' + assetRoot + '../index.html">Docs</a>',
    '    <a href="https://wordpress.org/plugins/waymark/" target="_blank">WordPress</a>',
    '    <a href="https://downloads.wordpress.org/plugin/waymark.zip">Download</a>',
    '    <a href="https://github.com/morehawes/waymark" target="_blank">GitHub</a>',
    '    <a href="https://www.morehawes.ca" target="_blank">morehawes.ca</a>',
    "  </nav>",
    "</footer>",
  ].join("\n");

  $("body").prepend(header).append(footer);
});
