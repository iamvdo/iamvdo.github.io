/**
 * animOnScroll.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( window ) {
	
	'use strict';
	
	var docElem = window.document.documentElement;

	var list = null;

	function refresh(){
		requestAnimFrame( refresh );

		//list.items[0].classList.toggle('toggle');

		//console.log('update');
		//list.update();
	}

	function scrollTopY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	function AnimOnScroll( el, options ) {

		this.element = el;
		//this.options = extend( this.defaults, options );

		for(var key in options){
			if(options.hasOwnProperty(key)){
				this.defaults[key] = options[key];
			}
		}
		this.options = this.defaults;
		
		// list of items
		this.items = [];

		var elems = document.querySelectorAll(this.element + ' *');
		for (var i = elems.length - 1; i >= 0; i--) {
			this.items.push(elems[i]);
		};


		//this.items = Array.prototype.slice.call( this.element.children );

		
		this.sync();
		//this.update();

		var scope = this;
		this.startUpdate = function() { scope.update(); };
		this.startSync = function() { scope.sync(); };
		this.startMove = function(event) { scope.move(event); };

		window.addEventListener('scroll', this.startUpdate, false );
		// window.addEventListener('resize', this.startSync, false );
		// resize not necessary (isotope do it already), maybe test orientationchange too

		window.addEventListener('touchmove', this.startMove, false );

		//tmp
		var IS_TOUCH_DEVICE = !!( 'ontouchstart' in window );
		if(IS_TOUCH_DEVICE){
			for( var i = 0, len = this.items.length; i < len; i++ ) {
				this.items[i].classList.add('istouch');
			}
		}
	}

	AnimOnScroll.prototype = {
		defaults: {
			viewportFactor : .25
		},
		sync: function(){
			console.log('Gosync');

			// set DOM properties for each items
			for( var i = 0, len = this.items.length; i < len; i++ ) {
				var item = this.items[i];
				item._offsetHeight = item.offsetHeight;
				if(item.offsetParent !== null){
				item._offsetTop = item.offsetTop + (item.offsetParent.offsetTop);
			} else {
				item._offsetTop = item.offsetTop;
			}
				item._offsetBottom = item._offsetTop + item._offsetHeight;
				//item._state = '';
			}

			this.update();
		},
		update: function(){
			
			// get the scrollTop and scrollBottom
			var scrollTop = scrollTopY(),
				scrollBottom = scrollTop + window.innerHeight;

			//console.log(scrollTop + ', ' +scrollBottom);
			//console.log(this.items);

			// One loop to make our changes to the DOM
			for( var i = 0, len = this.items.length; i < len; i++ ) {
				var item = this.items[i];

				/*
				item.classList.remove('past');
				item.classList.remove('future');
				*/

				//console.log(this.options.viewportFactor);
				//console.log(item._offsetTop + (item._offsetHeight * this.options.viewportFactor));
				//console.log(item.textContent);
				//console.log('top' + item._offsetTop + (item._offsetHeight * this.options.viewportFactor))
				// Above list viewport
				if( item._offsetBottom - (item._offsetHeight * this.options.viewportFactor) < scrollTop ) {
					// Exclusion via string matching improves performance
					if( item._state !== 'past' ) {
						item._state = 'past';
						item.classList.add( 'past' );
						item.classList.add( 'show' );
						item.classList.remove( 'future' );
					}
				}
				// Below list viewport
				else if( item._offsetTop + (item._offsetHeight * this.options.viewportFactor) > scrollBottom ) {
					// Exclusion via string matching improves performance
					if( item._state !== 'future' ) {

						item._state = 'future';
						item.classList.add( 'future' );
						item.classList.remove( 'past' );
						item.classList.remove( 'show' );

					}
				}
				// Inside of list viewport
				else {
					if( item._state === 'past' ) item.classList.remove( 'past' );
					if( item._state === 'future' ) item.classList.remove( 'future' );
					item._state = '';
				}

			}

		},
		move: function(event){
			// update
			this.update();
		}
	}

	// add to global namespace
	window.animOnScroll = {
		bind: function( target, options ) {
			// new list
			list = new AnimOnScroll(target, options);
			// start refreshing
			window.addEventListener('resize', function(){
				list.sync();
			}, false);
		}
	};
	

	window.requestAnimFrame = (function(){
		return  window.requestAnimationFrame       ||
				window.webkitRequestAnimationFrame ||
				window.mozRequestAnimationFrame    ||
				window.oRequestAnimationFrame      ||
				window.msRequestAnimationFrame     ||
				function( callback ){
					window.setTimeout(callback, 1000 / 60);
				};
	})();

} )( window );