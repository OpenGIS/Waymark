# The Economics of Free WordPress Plugins — Research Dossier

This dossier collects publicly documented cases of what happens when WordPress and other open-source plugins are given away and funded by donations, and of the alternatives that have worked. All links were checked in October 2026. Quotes are transcribed verbatim, including original typos and spacing quirks.

## Contents

- [Part 1 — The challenge: giving away your work](#part-1--the-challenge-giving-away-your-work)
  - [1.1 The numbers: donations don't scale](#11-the-numbers-donations-dont-scale)
  - [1.2 Individual stories: "we never received a single donation"](#12-individual-stories-we-never-received-a-single-donation)
  - [1.3 When map makers stop (the mapping-plugin corner)](#13-when-map-makers-stop-the-mapping-plugin-corner)
  - [1.4 Maintenance burden and sustainability alarms](#14-maintenance-burden-and-sustainability-alarms)
- [Part 2 — What works: getting paid to work on open source](#part-2--what-works-getting-paid-to-work-on-open-source)
  - [2.1 Paid upgrades and freemium](#21-paid-upgrades-and-freemium)
  - [2.2 Getting hired to maintain your own project](#22-getting-hired-to-maintain-your-own-project)
  - [2.3 Corporate contribution programs](#23-corporate-contribution-programs)
  - [2.4 Acquisition and succession](#24-acquisition-and-succession)
  - [2.5 Platform funding](#25-platform-funding)
  - [2.6 The small-business path](#26-the-small-business-path)
- [Link status (verification notes)](#link-status-verification-notes)

# PART 1 — The challenge: giving away your work

> [!NOTE]
> Donations do not scale. Across more than a decade of documented cases — from a 2009 survey to a 2025 Reddit thread — conversion sits well below one percent of downloads, and frequently at zero. Even the era's best-case result, and the most-downloaded plugins, produced sums that could not sustain maintenance.

## 1.1 The numbers: donations don't scale

**tomuse.com study (Kevin Eklund, 15 June 2009)** — "WordPress Plugin Developers In Dire Need Of A Sustainable Business Model". The article surveyed plugin authors on the share of users who donate; the responses clustered below one percent.

- Alex Rabe (NextGEN Gallery): "Less than 1 percent for NextGEN Gallery, 800,000 downloads and approx. 200 donations"
- Alex King: "I'd say maybe .5%? Maybe lower…"
- Lester Chan: "I would say less than 1% based on my 4 years of doing plugin"
- Donncha O Caoimh: "I don't have an exact figure but it's probably far less than 1%"
- Michael Torbert (in-article update): "The approximate percentage of users who donate is .013%. This includes donations ranging from a few cents to tens of dollars, with a mean donation of $11"
- Roy Tanck (from the comments): "I have a few plugins up at wordpress.org, but only one that's moderately popular. It's been downloaded 200,000 times now and I've received something like 20 donations. I occasionally do paid modifications, but 99% of my efforts have gone unpaid."

— Source: tomuse.com, [archived article](https://web.archive.org/web/20101201080844/http://tomuse.com/wp-plugins-developer-business-model/), 15 June 2009; Hacker News, [discussion](https://news.ycombinator.com/item?id=659303) titled "WordPress Plugin Developers Get The Shaft, Donations Are 0.1% Of All Downloads", 16 June 2009.

**wp-hackers mailing list (Alex Rabe, March 2009)** — the era's _best-case_ result, from the developer of NextGEN Gallery.

> "During this two years I earned more than $3000 donations (hard to calculate cause I'm accepting Euro as well). If you donate, then you can choose the value you want and yes it's very often something between $5 - $20."

— Source: wp-hackers mailing list, [archive](https://lists.automattic.com/pipermail/wp-hackers/2009-March/025169.html), March 2009.

**Weblog Tools Collection (Jeff Chandler, 2 December 2010)** — "Open-Source Motivations – What's Yours?" Three views on donation income, from the article and its comments.

- Alex King: "I used to get about $100-200/month in the way of donations through my website. Unfortunately due to changes in the way plugins are presented on WordPress.org that has dried up to about $5/month."
- Commenter "Eric": "…but in the 3 years I've worked with WordPress I've received only $10 in community contributions."
- Commenter "Otto": "…then they use the donation box (which says \"Buy me a Beer\") to donate $20 or so."

— Source: Weblog Tools Collection, [article](https://weblogtoolscollection.com/archives/2010/12/02/open-source-motivations-whats-yours/), 2 December 2010.

**Roads and Bridges (Nadia Eghbal, Ford Foundation, 14 July 2016)** — the canonical study of digital-infrastructure funding.

> "Despite the number of individuals and companies relying on their software, OSF never received more than $2,000 in donations per year. … There was enough to pay the salary of one developer, Stephen Henson. That meant that two-thirds of the Web relied on encryption software maintained by just one full-time employee."

> "A 2015 study by the Federal University of Minas Gerais in Brazil looked at 133 of the most actively used projects hosted on GitHub, across programming languages, and found that 64%, or nearly two-thirds, relied upon just one or two developers to survive."

> "The Linux Foundation reported, for example, that more than 80 percent of Linux kernel development is done by developers who are paid for their work."

— Source: Ford Foundation, [landing page](https://www.fordfoundation.org/work/learning/research-reports/roads-and-bridges-the-unseen-labor-behind-our-digital-infrastructure/) · [PDF](https://www.fordfoundation.org/wp-content/uploads/2016/07/roads-and-bridges-the-unseen-labor-behind-our-digital-infrastructure.pdf), 14 July 2016.

## 1.2 Individual stories: "we never received a single donation"

**WangGuard / José Conti (2016)** — the flagship case: after six years of supporting the plugin he had received six donations and shut it down.

- WP Tavern (Sarah Gooding, 14 October 2016): "Conti was struggling to pay for the servers and, after six years of supporting the plugin, had only received six donations from the community."
- José Conti, shutdown tweet (9 October 2016, verbatim incl. missing spaces): "After 6 years of work and only 6 donations,I don't see that people appreciate WangGuard, so I'm Shutting Down WGG.Thanks for your confidence"

He then launched an Indiegogo campaign with a $35,000 goal; it ultimately raised $2,050 (the final figure on the archived page; WP Tavern reported $1,145 mid-campaign).

— Sources: WP Tavern, [article](https://wptavern.com/wangguard-plugin-launches-indiegogo-campaign-to-fund-development-and-support), 14 October 2016; X/Twitter, [tweet](https://twitter.com/sploghunter/status/785185033359360002), 9 October 2016; Indiegogo, [live campaign](https://www.indiegogo.com/projects/wangguard-service-plugin-development-and-support-service) · [archived copy](https://web.archive.org/web/20170101140600/https://www.indiegogo.com/projects/wangguard-service-plugin-development-and-support-service), 2016.

**WP Tavern comment by "Mark" (19 October 2016)** — in the WangGuard discussion.

> "About 8 years ago I deleted a plugin from the WP repo because I received maybe $5 in donations and people were demanding support and getting angry when they didn't get it as fast as a McDonald's drive-thru window."

— Source: WP Tavern, [comment #200988](https://wptavern.com/wangguard-plugin-launches-indiegogo-campaign-to-fund-development-and-support#comment-200988), 19 October 2016.

**cordova-plugin-googlemaps (2016)** — a mapping library. Maintainer wf9a5m75 offered a paid incentive for a specific feature.

> "If someone donate at least additional $800USD to me, I will create the marker cluster in one month. I already recieved $200USD donation from two people."

— Source: GitHub, [issue #1006](https://github.com/mapsplugin/cordova-plugin-googlemaps/issues/1006), 9 October 2016.

**Shade Map / Ted Piotrowski (8 June 2021)** — a personal project update.

> "It also has a blurb about me and a buy me a tea donation link plus my email address for suggestions. No donations yet, so I'm brewing my tea at home."

— Source: Ted Piotrowski, [Spring update on Shade Map](https://tedpiotrowski.svbtle.com/spring-update-on-shade-map), 8 June 2021.

**Garmin Connect IQ developer forum (February 2026)** — thread "I Monetized My Ski App, Here's What Happened". Four developers compare donation outcomes.

- jim_m_58: "Since CIQ started, I've used a \"donation\" model (if you like this app, buy me a beer...) The largest donation I've gotten was $50US."
- Charlie (OP): "…I added a buy-me-a-coffee link to both and got a few generous donations, but the total has been less than $20 over the lifetime of the apps."
- NoOne: "I have 100 plus downloads in almost 2 months. Zero donations."
- dpawlyk: "My apps have 4000 downloads each (with some overlap) over 7 years and I might have gotten 10 donations."

— Source: Garmin Forums, [base thread](https://forums.garmin.com/developer/connect-iq/f/showcase/430838/i-monetized-my-ski-app-here-s-what-happened) (direct post links: [jim_m_58](https://forums.garmin.com/developer/connect-iq/f/showcase/430838/i-monetized-my-ski-app-here-s-what-happened/2009310), [Charlie](https://forums.garmin.com/developer/connect-iq/f/showcase/430838/i-monetized-my-ski-app-here-s-what-happened/2009450), [NoOne](https://forums.garmin.com/developer/connect-iq/f/showcase/430838/i-monetized-my-ski-app-here-s-what-happened/2011141), [dpawlyk](https://forums.garmin.com/developer/connect-iq/f/showcase/430838/i-monetized-my-ski-app-here-s-what-happened/2012349)), February 2026.

**Reddit r/Wordpress (18 March 2025)** — "“Buy me a coffee” - Plugin developers how much do you make?"

- u/ImaginaryTime7615: "…At its peak, it was used on over 100,000 websites. We never received a single donation."
- u/ScatPack7: "My case, free plugin with over 100K active users, 1 single donator of $5 per month. That guy is the real deal :-)"

— Sources: Reddit, [live thread](https://www.reddit.com/r/Wordpress/comments/1jehjga/buy_me_a_coffee_plugin_developers_how_much_do_you/) (login wall) · [archived copy](https://web.archive.org/web/20250803203548/https://www.reddit.com/r/Wordpress/comments/1jehjga/buy_me_a_coffee_plugin_developers_how_much_do_you/), 18 March 2025.

**SEO Ultimate / Jeffrey L. Smith (4 October 2016)** — wp.org support thread "Plugin not updated for over a year?" explaining the move to a paid model.

- "…after the plugin grew to it's current size (after 9 years of being free) we have decided to focus on PRO…"
- "Originally this was a tool for our agency and we shared it back in 2008 (and have never taken a donation) and funded it ourselves…"

— Source: WordPress.org support forums, [thread](https://wordpress.org/support/topic/plugin-not-updated-for-over-a-year/), 4 October 2016.

**Broken Link Notifier changelog (v1.0.9, June 2024)** — the pivot stated in a changelog line.

> "Removed donate link, nobody donates anyway, working on premium version instead"

— Source: WordPress.org plugin SVN, [readme.txt](https://plugins.svn.wordpress.org/broken-link-notifier/trunk/readme.txt), June 2024.

**OinkAndStuff developer testimonial (21 October 2015)** — a browser-extension and app developer.

> "I have a Free App with over 6k Downloads and in 4 years not a single donation was received ."

— Source: OinkAndStuff, [testimonial](https://www.oinkandstuff.com/2015/10/21/browser-extensions-mobile-apps-website-blog-monetization-how-to-profit-developer-testimonial/), 21 October 2015.

**Vanilla Forums — "Monetizing Projects" (businessdad, 22 January 2014)** — on donation-based products.

> "Donation: put simply, it doesn't work. Very few users donate something, just ask @peregrine about it. Note: this doesn't only apply to Vanilla Forums. I released some donation-based products elsewhere, which are designed to handle payments (i.e. they are definitely running on a money making website, and they help collecting such money) and I never received a single donation."

— Sources: Vanilla Forums, [discussion](https://open.vanillaforums.com/discussion/25917/monetizing-projects) (page may be bot-blocked; [JSON endpoint](https://open.vanillaforums.com/discussion/25917/monetizing-projects.json) works), 22 January 2014.

**More voices on the same wall (outside mapping, for breadth)** — three developers explaining why donations did not fund their work.

- **smub (VeronaLabs, WP Statistics), circa January 2023:** "Since the inception of this plugin, it has been 100% free. We relied entirely on the donation model which simply isn't scalable as we have full-time team members working on the product." — [thread](https://wordpress.org/support/topic/free-version-for-woocommerce-is-completely-useless/)
- **stevengliebe, circa February 2018:** "Naturally we recommend our own product as a solution which helps fund development and support of this free plugin. Unfortunately, asking for donations wasn't effective." — [thread](https://wordpress.org/support/topic/great-plugin-saved-me-hours/)
- **ypraise, circa May 2015:** "Before when this plugin was free at WordPress I could get up to 40 people a week demanding support and getting it to work with their themes. And none were prepared to pay or donate for my time." — [thread](https://wordpress.org/support/topic/facebook-sharing-wrong-wp-image/)

## 1.3 When map makers stop (the mapping-plugin corner)

**Geo Mashup / Dylan Kuhn** — thread "This is volunteer support" (20 March 2014).

> "It's a free plugin and always has been, but I don't promise free support. If you want assistance from me and other experts I recommend WP Questions."

His own site shows a live counter reading "Donations in the past month: $ 0" (a dynamic value, as seen October 2026).

— Sources: WordPress.org support forums, [thread](https://wordpress.org/support/topic/this-is-volunteer-support/), 20 March 2014; cyberhobo.net, [Geo Mashup plugin page](https://www.cyberhobo.net/downloads/geo-mashup-plugin/).

**WP GPX Maps / Bastianon Massimo** — his own site's donation forum carries a standing description (typos as-is).

> "There are many ways to give me support: Geve me a free donation to my paypal account / Surf my website / Rate my plugin / Help with translation / Suggest my plugin / Help other users in this forum / ..or just leave a topic with a phrase in your own language!"

In around 14 years the donation forum has drawn just 2 topics.

— Source: devfarm.it, [WP GPX Maps forum](https://devfarm.it/forums/forum/wp-gpx-maps/).

**Leaflet Maps Marker / Robert Seyfriedsberger** — "We're retiring the free version" (6 January 2020).

> "Therefore, we'd rather be fully committed and retire the free version for good, than to compromise on quality."

— Source: WordPress.org support forums, [thread](https://wordpress.org/support/topic/were-retiring-the-free-version/), 6 January 2020.

**Comprehensive Google Map Plugin / Alexander Zagniotov** — discontinuation notice (26 September 2014).

> "Unfortunately, due to limited resources and changes in my professional life, I am not able anymore to offer the kind of professional support and development needed for a plugin with such a large userbase"

A user thread, "Its a shame you are so near sighted!" (23 June 2015), answers with angry arithmetic:

> "As of today, you have 705,973 downloaders!" … "sell even 3% (most common in marketing) a $20 up sell, that means you would make yourself a whooping $423,583.80 !!!!!"

— Sources: WordPress.org support forums, [discontinuation notice](https://wordpress.org/support/topic/notice-about-plugin-discontinuation-future-recommendations/), 26 September 2014; [user thread](https://wordpress.org/support/topic/its-a-shame-you-are-so-near-sighted/), 23 June 2015.

**Google Mapping getDirections / Jonathon Byrd** — readme, before the plugin was closed on 7 June 2018.

> "Even though this is a small plugin with a single purpose, I am providing it with as much support as I am capable of doing. If you would like me to extend its functionality, all I ask is that you buy me a beer."

— Source: WordPress.org plugin page, [archived copy](https://web.archive.org/web/20140129134423/http://wordpress.org/plugins/google-map-plugin/), 2014.

**google-routeplaner readme (v4.0)** — the end of the road, stated plainly.

> "Project now abandoned - no more support, no more ads, no more donations!"

— Source: WordPress.org plugin SVN, [readme.txt](https://plugins.svn.wordpress.org/google-routeplaner/trunk/readme.txt), v4.0.

**geo-masala readme (typos as-is)** — donation requests framed around the maintainer's travels.

> "Tell me your wishes, maybe get me a bowl of rice and some masala: Donate"

> "I'm hungry, cycling around the world and Geo-Masala is coded while sitting wet in a tent during Monsoon somwhere in south himalaya. I appreaciate any kind of donation or sponsorship."

— Source: WordPress.org plugin SVN, [readme.txt](https://plugins.svn.wordpress.org/geo-masala/trunk/readme.txt).

**WP Geo Posts / fyaconiello (circa February 2013)** — the same corner of the ecosystem, and the ask in its plainest form.

> "All that I ask, is that if you want me to develop out that extra feature + plan on using my plugin you donate a bit to the plugin fund. … I don't care how much you donate, or even IF you donate – that isn't a requirement for using the plugin – but donations do help motivate me to do non-client work."

— Source: WordPress.org support forums, [thread](https://wordpress.org/support/topic/radius/#post-3318232), circa February 2013.

## 1.4 Maintenance burden and sustainability alarms

**WordPress Coding Standards / Juliette Reinders Folmer (WP Tavern, 22 August 2023)** — the maintainer warns work will halt without funding.

> "WordPressCS 3.0.0 has cost thousands of hours of work and the vast majority of work has been done by one, mostly unpaid, contributor, with code review support from two fellow maintainers."

> "If we are being realistic, the bus factor of WordPressCS is 1, which is the most dangerous situation for any project to be in… This is an unsustainable situation and it ends now."

— Source: WP Tavern, [article](https://wptavern.com/wordpress-coding-standards-maintainer-warns-maintenance-will-be-halted-without-funding-this-is-an-unsustainable-situation), 22 August 2023.

**Joost de Valk (Post Status, 1 December 2023)** — on PHPCS funding.

> "The majority of Juliette's time has been unpaid for years."

> "A lot of gratitude, but gratitude alone doesn't pay the bills."

— Source: Post Status, [article](https://poststatus.com/phpcs-a-major-php-library-needs-support/) (browser-only), 1 December 2023.

**Support-burden economics (WP Tavern, 4 September 2020)** — Marius Jensen on commercial plugins freeloading on volunteer forums.

> "It's an unacceptable misuse of volunteers time to support a product someone else has been paid to support."

— Source: WP Tavern, [article](https://wptavern.com/wordpress-support-team-seeks-to-curb-support-requests-for-commercial-plugins-and-themes), 4 September 2020.

**Automattic cuts its sponsored contribution (9 January 2025)**.

> "Automattic will reduce its sponsored contributions to the WordPress project."

> "Automattic will match its volunteering pledge with those made by WP Engine and other players in the ecosystem, or about 45 hours a week…"

— Source: Automattic, [announcement](https://automattic.com/2025/01/09/aligning-automattics-sponsored-contributions-to-wordpress/), 9 January 2025.

# PART 2 — What works: getting paid to work on open source

> [!TIP]
> The models that work share a common thread: money arrives only when it is tied to something a user or a company values more than goodwill. Paid upgrades, being hired to maintain your own project, corporate contribution programs, acquisitions, platform funding, and small-business products all convert open-source work into income; donations alone rarely do.

## 2.1 Paid upgrades and freemium

**Maps Marker Pro** — the free Leaflet Maps Marker retired (10+ years old) so the paid Pro version could fund full-time work.

> "So I started developing Leaflet Maps Marker. I was overwhelmed by the positive response, and the user community grew fast. It all worked out so well that, 1.5 years later, I was able to later add a higher-powered version with Maps Marker Pro, and we've been improving it ever since."

> "Now we will send the free version of the plugin – Leaflet Maps Marker, which was first released more than 10 years ago – to its well-earned retirement."

Pricing: Personal €49 / Plus €149 / Professional €299 per year of updates and support.

— Sources: Maps Marker, [Our Story](https://www.mapsmarker.com/our-story); [retirement announcement](https://www.mapsmarker.com/news/leaflet-maps-marker-retirement); [pricing](https://www.mapsmarker.com/pricing).

**WP Go Maps (formerly WP Google Maps) / Nick Duncan** — started 2012; FoundersBeta founder spotlight (6 December 2020).

> "It all originally started with a WordPress plugin called WP Google maps… I made my own and launched it. It actually just started making money!"

> "That's the number one plugin in the world in terms of the map industry. This is our bread and butter…"

— Sources: FoundersBeta, [founder spotlight](https://www.foundersbeta.com/founder-spotlight/building-50-products-in-10-months/), 6 December 2020; product site [wpgmaps.com](https://www.wpgmaps.com/) (browser-only).

**MapPress Pro / Chris Richardson** — free plugin on WordPress.org plus paid Pro.

> "Subscriptions include free support and updates, and renew automatically after one year. You may cancel at any time."

— Source: MapPress Pro, [mappresspro.com](https://mappresspro.com/) (browser-only).

(Cross-reference the Broken Link Notifier item in 1.2 — the same "donations don't work → premium version" pivot.)

## 2.2 Getting hired to maintain your own project

**WPGraphQL / Jason Bahl** — the clearest "maintainer follows the funding" path.

- 2019 (WP Tavern, 26 September 2019): Gatsby raised $15M and "plans to use the funding to invest $3 million per year in open source"; it had hired Bahl. — [article](https://wptavern.com/gatsby-raises-15m-plans-to-invest-more-heavily-in-wordpress-and-cms-integrations)
- 2021 (WPGraphQL blog, 7 February 2021): "WP Engine pays my salary, and in exchange I will be maintaining WPGraphQL and helping grow the headless WordPress ecosystem…" — [post](https://www.wpgraphql.com/2021/02/07/whats-next-for-wpgraphql)
- 2024 (WPGraphQL blog, 7 October 2024): "I'm excited to announce that after 3.5 wonderful years at WP Engine, I've accepted an offer with Automattic to continue my work on WPGraphQL as it transitions into becoming a canonical community plugin on WordPress.org." — [post](https://www.wpgraphql.com/2024/10/07/wpgraphql-becomes-a-canonical-plugin-my-move-to-automattic) · corroboration: [WP Tavern](https://wptavern.com/wpgraphql-to-become-a-canonical-plugin-as-its-creator-joins-automattic), [WordPress.org news](https://wordpress.org/news/2024/10/wpgraphql/)

## 2.3 Corporate contribution programs

**Five for the Future** — WordPress's flagship corporate-sponsorship program.

> "The Five for the Future program allows organizations to contribute time or sponsor resources to support the sustained growth of the WordPress project, ensuring the longevity of WordPress and open source while investing in their own success."

> "The 5% in Five for the Future is an aspirational target, not a strict requirement."

- Origin, Matt Mullenweg (30 September 2014): "organizations that want to grow the WordPress pie (and not just their piece of it) should dedicate 5% of their people to working on something to do with core…" and "This ratio is probably the bare minimum for a sustainable ecosystem, avoiding the tragedy of the commons." — [ma.tt](https://ma.tt/2014/09/five-for-the-future/)
- Automattic's own pledge is published at [wordpress.org](https://wordpress.org/five-for-the-future/pledge/automattic/) (and see the 2025 reduction item in 1.4).

— Sources: WordPress.org, [Five for the Future](https://wordpress.org/five-for-the-future/) · [handbook](https://wordpress.org/five-for-the-future/handbook/about-five-for-the-future/); Matt Mullenweg, [ma.tt](https://ma.tt/2014/09/five-for-the-future/), 30 September 2014.

## 2.4 Acquisition and succession

**Advanced Custom Fields** — creator Elliot Condon sold to Delicious Brains (June 2021), which sold it to WP Engine (June 2022).

- Condon, June 2021: "Stepping away from ACF has not been an easy decision to make… As the number of installs have grown from thousands to millions, the needs of the product have outgrown my ability to develop solutions." — WP Tavern, [article](https://wptavern.com/delicious-brains-acquires-advanced-custom-fields-plugin); primary, [Delicious Brains](https://deliciousbrains.com/epic-wordpress-announcement/)
- June 2022 (WP Tavern): "Delicious Brains, a WordPress product company founded in 2012 by Brad Touesnard, has sold five of its plugins to WP Engine: Advanced Custom Fields (ACF), WP Migrate, WP Offload Media, WP Offload SES, and Better Search Replace." — [article](https://wptavern.com/wp-engine-acquires-5-plugins-from-delicious-brains); primary, [Delicious Brains](https://deliciousbrains.com/wp-engine-acquisition/) · [WP Engine press release](https://wpengine.com/press-releases/wp-engine-acquires-delicious-brains-plugins-including-advanced-custom-fields/)
- Post Status framed the 2021 deal as removing the bus factor: "The acquisition removes the 'bus factor' — dependency on one (very good) developer — which is one, big, single point of failure." — [Post Status](https://poststatus.com/delicious-brains-acquires-advanced-custom-fields/) (browser-only)

## 2.5 Platform funding

**GitHub Sponsors** — vendor-reported figures differ by source.

- Product page: "$40M+ Given back to our maintainers" — [github.com/sponsors](https://github.com/sponsors)
- 2026 blog post: "more than $100 million has been invested in open source maintainers and projects through GitHub Sponsors", and that the programme "supports over 70,000 maintainers and organizations, and includes more than 280,000 sponsors" — [GitHub blog](https://github.blog/open-source/maintainers/100-million-for-open-source-a-milestone-built-by-the-community/)

**Open Collective** — "Raise, manage and disburse money with full transparency."

— Source: [opencollective.com](https://opencollective.com/)

Open Source Collective reports that it will "consistently pay open source maintainers over $1m a month", and that "in January, we surpassed $50m in contributions received since we started in 2017".

— Source: Open Collective, [update](https://opencollective.com/opensource/updates/open-source-collective-is-paying-maintainers-usd1m-a-month).

**Buy Me a Coffee** — "Loved by 2,000,000+ creators".

— Source: [buymeacoffee.com](https://buymeacoffee.com/)

**Tidelift** — acquired by Sonar (announced 17 December 2024).

> "Tidelift helps improve the health and security of open source by paying the maintainers behind thousands of the world's most-relied-upon open source projects… Paid open source maintainers are 55% more likely to implement critical security and maintenance practices than unpaid maintainers."

— Source: Sonar, [press release](https://www.sonarsource.com/company/press-releases/sonar-to-acquire-tidelift/), 17 December 2024.

## 2.6 The small-business path

**"You Don't Need to Know Code to Build a Successful WP Plugin Business" (Toby Cryns, WP Tavern, 29 February 2024)** — the case for small, unglamorous plugin businesses.

> "With all the consolidation happening in the WordPress plugin ecosystem, it's easy to think that it'd be futile to start your own WP plugin business. But the reality is that there are many small plugin developers making a living flying under the radar of the big dogs."

ShopWP's Andrew Robbins: "The early years of ShopWP were basically the opposite [of the consulting business]. ShopWP's income was rare and unpredictable."

— Source: WP Tavern, [article](https://wptavern.com/you-dont-need-to-know-code-to-build-a-successful-wp-plugin-business), 29 February 2024.

## Link status (verification notes)

- All standard links were curl-checked in October 2026; browser-only links below were confirmed working in a browser and behave normally despite bot-blocking to automated checks. Archived fallbacks are provided for the Reddit thread and the Indiegogo campaign.
- Known browser-only: Indiegogo campaign page, X/Twitter tweet, Reddit thread (login wall), poststatus.com articles, open.vanillaforums.com (JSON endpoint works), wpgmaps.com, mappresspro.com.
- Quotes are transcribed verbatim, including original typos and spacing quirks.
