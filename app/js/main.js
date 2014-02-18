(function(){
	
	var heeereOptions = {
		elems: '.item'
	};
	var greeedOptions = {
		breakpoints : [34,48,65,88,110,140],
		fakeItem : true,
		fakeItemClass : 'item item--fake',
		afterLayout: function () {
			heeere.bind('.Greeed', heeereOptions);
		},
		afterInit: function () {
			/* 60fps scolling FTW */
			var childrens = document.querySelectorAll('.item'),
				timer,
				delay = 150;
			/*
			window.addEventListener('scroll', function() {
				clearTimeout(timer);
				if ( 'none' !== childrens[0].style.pointerEvents ){
					for (var i = childrens.length - 1; i >= 0; i--) {
						childrens[i].style.pointerEvents = 'none';
					};	
				}

				timer = setTimeout(function(){
					for (var i = childrens.length - 1; i >= 0; i--) {
						childrens[i].style.pointerEvents = 'auto';
					};
				}, delay);
			}, false);
			*/
		}
	};

	greeed.bind('.Greeed', greeedOptions);

	var IS_TOUCH_DEVICE = !!( 'ontouchstart' in window );
	if (IS_TOUCH_DEVICE) {
		document.body.className = 'istouch';
	}

})();