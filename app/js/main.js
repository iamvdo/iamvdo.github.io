(function(){

	var heeereOptions = {
		elems: '.item'
	};
	var greeedOptions = {
		breakpoints : [34,48,65,88,110,140],
		fakeItem : true,
		fakeItemClass : 'item item--fake',
		afterLayout: function () {
			heeere.bind(heeereOptions);
		},
		afterInit: function () {
			/* 60fps scolling FTW */
			var body = document.body,
				cover = document.createElement('div');
				cover.setAttribute('class','scroll-cover'),
				timer = null;

			window.addEventListener('scroll', function() {

				clearTimeout(timer);
				body.appendChild(cover);

				timer = setTimeout( function () {
							body.removeChild(cover);
						}, 100);

			}, false);
		}
	};

	greeed.bind('.Greeed', greeedOptions);

	var IS_TOUCH_DEVICE = !!( 'ontouchstart' in window );
	if (IS_TOUCH_DEVICE) {
		document.body.className = 'istouch';
	}

})();