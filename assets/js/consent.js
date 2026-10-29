/* ==========================================================================
   Cookie-Banner fuer Google Consent Mode v2 (Variante "Advanced").
   Der Default (alles "denied") und die Wiederherstellung einer gespeicherten
   Zustimmung stehen inline im <head> jeder Seite, vor gtag('config').
   Diese Datei zeigt nur das Banner und sendet das Update.
   ========================================================================== */

(function () {
	var KEY = 'msc_consent';
	var MAX_AGE = 365 * 24 * 60 * 60 * 1000;
	var banner = null;

	function read() {
		try {
			var c = JSON.parse(localStorage.getItem(KEY) || 'null');
			if (c && c.v === 1 && (c.ads === 'granted' || c.ads === 'denied') && Date.now() - c.ts < MAX_AGE) {
				return c;
			}
		} catch (e) {}
		return null;
	}

	function save(ads) {
		try {
			localStorage.setItem(KEY, JSON.stringify({ v: 1, ads: ads, ts: Date.now() }));
		} catch (e) {}
	}

	function update(ads) {
		if (typeof window.gtag !== 'function') { return; }
		window.gtag('consent', 'update', {
			ad_storage: ads,
			ad_user_data: ads,
			ad_personalization: ads,
			analytics_storage: ads
		});
	}

	function choose(ads) {
		save(ads);
		update(ads);
		hide();
	}

	function hide() {
		if (banner) { banner.hidden = true; }
	}

	function build() {
		banner = document.createElement('div');
		banner.className = 'consent-banner';
		banner.setAttribute('role', 'dialog');
		banner.setAttribute('aria-label', 'Cookie-Einstellungen');
		banner.innerHTML =
			'<div class="consent-inner">' +
				'<p class="consent-text">Wir verwenden Cookies von Google Ads, um zu messen, welche Anzeigen zu Anfragen führen. ' +
				'Mit „Alle akzeptieren“ stimmen Sie dem zu. Ohne Zustimmung sendet Google nur anonyme Signale ohne Cookies. ' +
				'Mehr dazu in der <a href="datenschutz.html#google-ads">Datenschutzerklärung</a>.</p>' +
				'<div class="consent-actions">' +
					'<button type="button" class="theme-btn consent-btn" data-consent="granted">Alle akzeptieren</button>' +
					'<button type="button" class="theme-btn consent-btn" data-consent="denied">Nur notwendige</button>' +
				'</div>' +
			'</div>';
		banner.addEventListener('click', function (e) {
			var btn = e.target.closest && e.target.closest('[data-consent]');
			if (btn) { choose(btn.getAttribute('data-consent')); }
		});
		document.body.appendChild(banner);
	}

	function show() {
		if (!banner) { build(); }
		banner.hidden = false;
		var first = banner.querySelector('.consent-btn');
		if (first) { first.focus({ preventScroll: true }); }
	}

	function init() {
		if (!read()) { show(); }
		document.addEventListener('click', function (e) {
			var link = e.target.closest && e.target.closest('[data-consent-open]');
			if (!link) { return; }
			e.preventDefault();
			show();
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
