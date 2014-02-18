;(function(){

	"use strict";

	function Greeed(elem, options){
		this.grid = elem;
		this.childs = this.columns = this.options = [];

		this.rootFontSize = getComputedStyle(document.documentElement).getPropertyValue('font-size').replace('px','');

		for(var key in options){
			if(options.hasOwnProperty(key)){
				this.defaults[key] = options[key];
			}
		}
		this.options = this.defaults;

		this.init();
	}

	Greeed.prototype = {

		defaults: {
			fakeItem: false,
			fakeItemClass: 'Greeed-item--fake'
		},
		init: function(){
			
			// Get elements
			this.childs = Array.prototype.slice.call(this.grid.children);

			this.checkMQ();

			var scope = this;
			this.startCheckMQ = function(event) { scope.checkMQ(event); };

			window.addEventListener('resize', this.startCheckMQ, false);

		},
		createColumns: function(){

			// create an Array
			this.columns = new Array(this.nbColumns);
			for (var i = 0; i < this.nbColumns; i++) {
				this.columns[i] = new Array();
				// set height
				this.columns[i]._offsetHeight = 0;
			};

			for (var i = 0; i < this.childs.length; i++) {

				var smallestColumnHeight = 0;
				var smallestColumnIndex = 0;
				// find the smallest column to place the next child
				for (var j = 0; j < this.columns.length; j++) {
					var columnHeight = this.columns[j]._offsetHeight;
					if(j == 0){
						smallestColumnHeight = columnHeight;
					}
					if(columnHeight == 0){
						smallestColumnIndex = j;
						break;
					}
					if(columnHeight < smallestColumnHeight){
						smallestColumnIndex = j;
					}
				}

				// add child to the smallest height column
				this.columns[smallestColumnIndex].push(this.childs[i]);

				// update column height
				this.columns[smallestColumnIndex]._offsetHeight += this.childs[i].offsetHeight;

			}

			console.log(this.columns);

			//already set _offsetHeight, dont need to find maxheight?

			// find the max-height column
			var maxHeightColumn = 0;
			for (var i = 0; i < this.columns.length; i++) {

				var height = this.columns[i]._offsetHeight;

				if( height >= maxHeightColumn){
					maxHeightColumn = height;
				}
			
			};

			var grid = document.createDocumentFragment();

			for (var i = 0; i < this.columns.length; i++) {
				
				var column = document.createElement('div');
					column.className = 'Greeed-column';
					column.style.float = 'left';
					column.style.width = 100 / this.nbColumns + '%';

				for (var j = 0; j < this.columns[i].length; j++) {
					column.appendChild(this.columns[i][j]);
				}

				if( this.columns[i]._offsetHeight < maxHeightColumn && this.options.fakeItem){
					
					var fake_elem = document.createElement('div');
						fake_elem.className = this.options.fakeItemClass;
						fake_elem.style.height = maxHeightColumn - this.columns[i]._offsetHeight + 'px';
							
						column.appendChild(fake_elem);
				}

				grid.appendChild(column);

			};


			this.grid.innerHTML = '';
			this.grid.appendChild(grid);
			

		},
		checkMQ: function(event){
			var scope = this;
			
			this.windowWidth = window.innerWidth;
			
			
			for (var i = 0; i < this.options.breakpoints.length; i++) {
				var point = this.options.breakpoints[i],
					size = point * this.rootFontSize;
				if(window.innerWidth < size){
					scope.nbColumns = i + 1;
					break;
				} else {
					scope.nbColumns = i + 2;
				}
				
			};
			this.createColumns();
		}
	}
	
	window.greeed = {
		bind: function(elem, options){
			var grid = document.querySelector(elem);
			new Greeed(grid, options);
		}
	}

})();