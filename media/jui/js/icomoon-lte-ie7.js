/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'IcoMoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-joomla' : '&#xe200;',
			'icon-chevron-up' : '&#xe005;',
			'icon-uparrow' : '&#xe005;',
			'icon-arrow-up' : '&#xe005;',
			'icon-chevron-right' : '&#xe006;',
			'icon-rightarrow' : '&#xe006;',
			'icon-arrow-right' : '&#xe006;',
			'icon-chevron-down' : '&#xe007;',
			'icon-downarrow' : '&#xe007;',
			'icon-arrow-down' : '&#xe007;',
			'icon-chevron-left' : '&#xe008;',
			'icon-leftarrow' : '&#xe008;',
			'icon-arrow-left' : '&#xe008;',
			'icon-arrow-first' : '&#xe003;',
			'icon-arrow-last' : '&#xe004;',
			'icon-arrow-up-2' : '&#xe009;',
			'icon-arrow-right-2' : '&#xe00a;',
			'icon-arrow-down-2' : '&#xe00b;',
			'icon-arrow-left-2' : '&#xe00c;',
			'icon-arrow-up-3' : '&#xe00f;',
			'icon-arrow-right-3' : '&#xe010;',
			'icon-arrow-down-3' : '&#xe011;',
			'icon-arrow-left-3' : '&#xe012;',
			'icon-menu-2' : '&#xe00e;',
			'icon-arrow-up-4' : '&#xe201;',
			'icon-arrow-right-4' : '&#xe202;',
			'icon-arrow-down-4' : '&#xe203;',
			'icon-arrow-left-4' : '&#xe204;',
			'icon-share' : '&#x27;',
			'icon-redo' : '&#x27;',
			'icon-undo' : '&#x28;',
			'icon-forward-2' : '&#xe205;',
			'icon-backward-2' : '&#xe206;',
			'icon-reply' : '&#xe206;',
			'icon-unblock' : '&#x6c;',
			'icon-refresh' : '&#x6c;',
			'icon-redo-2' : '&#x6c;',
			'icon-undo-2' : '&#xe207;',
			'icon-move' : '&#x7a;',
			'icon-expand' : '&#x66;',
			'icon-contract' : '&#x67;',
			'icon-expand-2' : '&#x68;',
			'icon-contract-2' : '&#x69;',
			'icon-play' : '&#xe208;',
			'icon-pause' : '&#xe209;',
			'icon-stop' : '&#xe210;',
			'icon-previous' : '&#x7c;',
			'icon-backward' : '&#x7c;',
			'icon-next' : '&#x7b;',
			'icon-forward' : '&#x7b;',
			'icon-first' : '&#x7d;',
			'icon-last' : '&#xe000;',
			'icon-play-circle' : '&#xe00d;',
			'icon-pause-circle' : '&#xe211;',
			'icon-stop-circle' : '&#xe212;',
			'icon-backward-circle' : '&#xe213;',
			'icon-forward-circle' : '&#xe214;',
			'icon-loop' : '&#xe001;',
			'icon-shuffle' : '&#xe002;',
			'icon-search' : '&#x53;',
			'icon-zoom-in' : '&#x64;',
			'icon-zoom-out' : '&#x65;',
			'icon-apply' : '&#x2b;',
			'icon-edit' : '&#x2b;',
			'icon-pencil' : '&#x2b;',
			'icon-pencil-2' : '&#x2c;',
			'icon-brush' : '&#x3b;',
			'icon-save-new' : '&#x5d;',
			'icon-plus-2 ' : '&#x5d;',
			'icon-ban-circle' : '&#x5e;',
			'icon-minus-sign' : '&#x5e;',
			'icon-minus-2' : '&#x5e;',
			'icon-delete' : '&#x49;',
			'icon-remove' : '&#x49;',
			'icon-cancel-2' : '&#x49;',
			'icon-publish' : '&#x47;',
			'icon-save' : '&#x47;',
			'icon-ok' : '&#x47;',
			'icon-checkmark' : '&#x47;',
			'icon-new' : '&#x2a;',
			'icon-plus' : '&#x2a;',
			'icon-plus-circle' : '&#xe215;',
			'icon-minus' : '&#x4b;',
			'icon-not-ok' : '&#x4b;',
			'icon-minus-circle' : '&#xe216;',
			'icon-unpublish' : '&#x4a;',
			'icon-cancel' : '&#x4a;',
			'icon-cancel-circle' : '&#xe217;',
			'icon-checkmark-2' : '&#xe218;',
			'icon-checkmark-circle' : '&#xe219;',
			'icon-info' : '&#xe220;',
			'icon-info-2' : '&#xe221;',
			'icon-info-circle' : '&#xe221;',
			'icon-question' : '&#x45;',
			'icon-question-sign' : '&#x45;',
			'icon-help' : '&#x45;',
			'icon-question-2' : '&#xe222;',
			'icon-question-circle' : '&#xe222;',
			'icon-notification' : '&#xe223;',
			'icon-notification-2' : '&#xe224;',
			'icon-notification-circle' : '&#xe224;',
			'icon-pending' : '&#x48;',
			'icon-warning' : '&#x48;',
			'icon-warning-2' : '&#xe225;',
			'icon-warning-circle' : '&#xe225;',
			'icon-checkbox-unchecked' : '&#x3d;',
			'icon-checkin' : '&#x3e;',
			'icon-checkbox' : '&#x3e;',
			'icon-checkbox-checked' : '&#x3e;',
			'icon-checkbox-partial' : '&#x3f;',
			'icon-square' : '&#xe226;',
			'icon-radio-unchecked' : '&#xe227;',
			'icon-radio-checked' : '&#xe228;',
			'icon-circle' : '&#xe229;',
			'icon-signup' : '&#xe230;',
			'icon-grid' : '&#x58;',
			'icon-grid-view' : '&#x58;',
			'icon-grid-2' : '&#x59;',
			'icon-grid-view-2' : '&#x59;',
			'icon-menu' : '&#x5a;',
			'icon-list' : '&#x31;',
			'icon-list-view' : '&#x31;',
			'icon-list-2' : '&#xe231;',
			'icon-menu-3' : '&#xe232;',
			'icon-folder-open' : '&#x2d;',
			'icon-folder' : '&#x2d;',
			'icon-folder-close' : '&#x2e;',
			'icon-folder-2' : '&#x2e;',
			'icon-folder-plus' : '&#xe234;',
			'icon-folder-minus' : '&#xe235;',
			'icon-folder-3' : '&#xe236;',
			'icon-folder-plus-2' : '&#xe237;',
			'icon-folder-remove' : '&#xe238;',
			'icon-file' : '&#xe016;',
			'icon-file-2' : '&#xe239;',
			'icon-file-add' : '&#x29;',
			'icon-file-plus' : '&#x29;',
			'icon-file-remove' : '&#xe017;',
			'icon-file-minus' : '&#xe017;',
			'icon-file-check' : '&#xe240;',
			'icon-file-remove' : '&#xe241;',
			'icon-save-copy' : '&#xe018;',
			'icon-copy' : '&#xe018;',
			'icon-stack' : '&#xe242;',
			'icon-tree' : '&#xe243;',
			'icon-tree-2' : '&#xe244;',
			'icon-paragraph-left' : '&#xe246;',
			'icon-paragraph-center' : '&#xe247;',
			'icon-paragraph-right' : '&#xe248;',
			'icon-paragraph-justify' : '&#xe249;',
			'icon-screen' : '&#xe01c;',
			'icon-tablet' : '&#xe01d;',
			'icon-mobile' : '&#xe01e;',
			'icon-box-add' : '&#x51;',
			'icon-box-remove' : '&#x52;',
			'icon-download' : '&#xe021;',
			'icon-upload' : '&#xe022;',
			'icon-home' : '&#x21;',
			'icon-home-2' : '&#xe250;',
			'icon-out-2' : '&#xe024;',
			'icon-new-tab' : '&#xe024;',
			'icon-out-3' : '&#xe251;',
			'icon-new-tab-2' : '&#xe251;',
			'icon-link' : '&#xe252;',
			'icon-picture' : '&#x2f;',
			'icon-image' : '&#x2f;',
			'icon-pictures' : '&#x30;',
			'icon-images' : '&#x30;',
			'icon-palette' : '&#xe014;',
			'icon-color-palette' : '&#xe014;',
			'icon-camera' : '&#x55;',
			'icon-camera-2' : '&#xe015;',
			'icon-video' : '&#xe015;',
			'icon-play-2' : '&#x56;',
			'icon-video-2' : '&#x56;',
			'icon-youtube' : '&#x56;',
			'icon-music' : '&#x57;',
			'icon-user' : '&#x22;',
			'icon-users' : '&#xe01f;',
			'icon-vcard' : '&#x6d;',
			'icon-address' : '&#x70;',
			'icon-share-alt' : '&#x26;',
			'icon-out' : '&#x26;',
			'icon-enter' : '&#xe257;',
			'icon-exit' : '&#xe258;',
			'icon-comment' : '&#x24;',
			'icon-comments' : '&#x24;',
			'icon-comments-2' : '&#x25;',
			'icon-quote' : '&#x60;',
			'icon-quotes-left' : '&#x60;',
			'icon-quote-2' : '&#x61;',
			'icon-quotes-right' : '&#x61;',
			'icon-quote-3' : '&#xe259;',
			'icon-bubble-quote' : '&#xe259;',
			'icon-phone' : '&#xe260;',
			'icon-phone-2' : '&#xe261;',
			'icon-envelope' : '&#x4d;',
			'icon-mail' : '&#x4d;',
			'icon-envelope-opened' : '&#x4e;',
			'icon-mail-2' : '&#x4e;',
			'icon-unarchive' : '&#x4f;',
			'icon-drawer' : '&#x4f;',
			'icon-archive' : '&#x50;',
			'icon-drawer-2' : '&#x50;',
			'icon-briefcase' : '&#xe020;',
			'icon-tag' : '&#xe262;',
			'icon-tag-2' : '&#xe263;',
			'icon-tags' : '&#xe264;',
			'icon-tags-2' : '&#xe265;',
			'icon-options' : '&#x38;',
			'icon-cog' : '&#x38;',
			'icon-cogs' : '&#x37;',
			'icon-screwdriver' : '&#x36;',
			'icon-tools' : '&#x36;',
			'icon-wrench' : '&#x3a;',
			'icon-equalizer' : '&#x39;',
			'icon-dashboard' : '&#x78;',
			'icon-switch' : '&#xe266;',
			'icon-filter' : '&#x54;',
			'icon-purge' : '&#x4c;',
			'icon-trash' : '&#x4c;',
			'icon-checkedout' : '&#x23;',
			'icon-lock' : '&#x23;',
			'icon-locked' : '&#x23;',
			'icon-unlock' : '&#xe267;',
			'icon-key' : '&#x5f;',
			'icon-support' : '&#x46;',
			'icon-database' : '&#x62;',
			'icon-scissors' : '&#xe268;',
			'icon-health' : '&#x6a;',
			'icon-wand' : '&#x6b;',
			'icon-eye-open' : '&#x3c;',
			'icon-eye' : '&#x3c;',
			'icon-eye-close' : '&#xe269;',
			'icon-eye-blocked' : '&#xe269;',
			'icon-eye-2' : '&#xe269;',
			'icon-clock' : '&#x6e;',
			'icon-compass' : '&#x6f;',
			'icon-broadcast' : '&#xe01b;',
			'icon-connection' : '&#xe01b;',
			'icon-wifi' : '&#xe01b;',
			'icon-book' : '&#xe271;',
			'icon-lightning' : '&#x79;',
			'icon-flash' : '&#x79;',
			'icon-print' : '&#xe013;',
			'icon-printer' : '&#xe013;',
			'icon-feed' : '&#x71;',
			'icon-calendar' : '&#x43;',
			'icon-calendar-2' : '&#x44;',
			'icon-calendar-3' : '&#xe273;',
			'icon-pie' : '&#x77;',
			'icon-bars' : '&#x76;',
			'icon-chart' : '&#x75;',
			'icon-power-cord' : '&#x32;',
			'icon-cube' : '&#x33;',
			'icon-puzzle' : '&#x34;',
			'icon-attachment' : '&#x72;',
			'icon-paperclip' : '&#x72;',
			'icon-flag-2' : '&#x72;',
			'icon-lamp' : '&#x74;',
			'icon-pin' : '&#x73;',
			'icon-pushpin' : '&#x73;',
			'icon-location' : '&#x63;',
			'icon-shield' : '&#xe274;',
			'icon-flag' : '&#x35;',
			'icon-flag-3' : '&#xe275;',
			'icon-bookmark' : '&#xe023;',
			'icon-bookmark-2' : '&#xe276;',
			'icon-heart' : '&#xe277;',
			'icon-heart-2' : '&#xe278;',
			'icon-thumbs-up' : '&#x5b;',
			'icon-thumbs-down' : '&#x5c;',
			'icon-unfeatured' : '&#x40;',
			'icon-asterisk' : '&#x40;',
			'icon-star-empty' : '&#x40;',
			'icon-star-2' : '&#x41;',
			'icon-featured' : '&#x42;',
			'icon-default' : '&#x42;',
			'icon-star' : '&#x42;',
			'icon-smiley' : '&#xe279;',
			'icon-smiley-happy' : '&#xe279;',
			'icon-smiley-2' : '&#xe280;',
			'icon-smiley-happy-2' : '&#xe280;',
			'icon-smiley-sad' : '&#xe281;',
			'icon-smiley-sad-2' : '&#xe282;',
			'icon-smiley-neutral' : '&#xe283;',
			'icon-smiley-neutral-2' : '&#xe284;',
			'icon-cart' : '&#xe019;',
			'icon-basket' : '&#xe01a;',
			'icon-credit' : '&#xe286;',
			'icon-credit-2' : '&#xe287;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};
