(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};

Data.ListItem = Backbone.Model.extend({
	defaults: function() {
		return {
			id: this.cid,
			name: 'No-title',
			health: 20,
			cleaningItems: ['Mop']
		}
	}
});
Data.List = Backbone.Collection.extend({
	model: Data.ListItem,
	initialize: function(options) {
		this.url = '/back/index.php/zones/'+options.id+'/units'
	}
});

View.Entry = Marionette.View.extend({
	template: '#units-entry',
	regions: new RegionSetter('list', 'map'),
	initialize: function(options) {

	},
	onRender: function() {
		this.getRegion('list').show(new View.List(this.options));
	},
	onChildviewShowMarker: function(model) {
		this.getRegion('map').show(new View.Map({
			model: model
		}))
	}
});

View.Map = Marionette.View.extend({
	template: '#t-map',
	map: null,
	className: 'mini-map',
	initialize: function(options) {
		this.model = options.model;
	},
	onAttach: function() {
		console.log(this.model);
		if (typeof L !== 'undefined') {
			console.warn(this.map);
			if (this.map != null) {
				this.map.remove();
			}
			this.triggerMethod('init:map')
		}
	},
	onInitMap: function() {
		var mapOptions = {
			scrollWheelZoom: false,
		}
		this.map = L.map(this.el, mapOptions);
		L.tileLayer('http://85.188.8.254/back/IB_floorplan_72.53mx72.53m/{z}/{x}/{y}.png', {
			attribution: '&copy; <a target="_blank" href="http://tieto.com">Tieto</a>',
			minZoom: 1,
			maxZoom: 6,
			continuousWorld: false,
			tms: true,
			noWrap: true
		}).addTo(this.map);

			dataset = this.model.attributes;
			var coords = this.model.get('coordinates');
			if (!_.isArray(coords)) {
				coords = [0,0];
			}
			var popup = _.template($('#t-list-map-popup').html())(dataset);
			var classPostfix = _.sample(['marker-success','marker-warning','marker-danger'])
			var myIcon = L.divIcon({
				className: 'marker '+classPostfix
			});
			var bounds = L.latLngBounds([[-180, -180], [180, 180]]);
			this.map.setMaxBounds(bounds);
			this.map.on('drag', () => {
				this.map.panInsideBounds(bounds, { animate: true });
			});
			var marker = L
				.marker(coords, {
					icon: myIcon,
				})
				.bindPopup(popup)
	
		this.map.addLayer(marker);
		this.map.setView(coords, 2);
		this.map.on('click', function(e) {
			console.log([e.latlng.lat,e.latlng.lng]);    
		});
		// this.map.addLayer(popup);
	},
})

View.ListItemEmpty = Marionette.View.extend({
	template: _.template('<div class="alert alert-info">No data, please try again later</div>'),
});
View.ListItem = Marionette.View.extend({
	template: '#units-listItem',
	className: 'list-group-item-light',
	tagName: 'li',
	regions: new RegionSetter('map','info','refillable','cleanable'),
	ui: {
		'btnHolder' : '.btn-holder',
		'btns': '[data-action="trigger"]',
		'mapBtn' : '[data-id="map"]',
		'toolsBtn' : '[data-id="tools"]',
		'closeBtn' : '[data-action="close"]',
	},
	triggers: {
		// click : 'open:map'
		'click header.main': 'toggle:buttons',
		'click @ui.mapBtn': 'show:map',
		'click @ui.toolsBtn': 'show:info',
		// 'click @ui.closeBtn': 'close',
 	},
 	onToggleButtons: function() {
 		this.$el.toggleClass('opened');
 		this.getUI('btnHolder').slideToggle();
 		this.triggerMethod('hide:regions');

 		// govnocode starts
 		if (this.$el.hasClass('opened')) {

 		}
 	},
 	onHideRegions: function() {
 		this.getRegion('map').empty();
 		// this.getRegion('cleanable').empty();
 		// this.getRegion('refillable').empty();
 		this.getRegion('info').empty();
 		this.getUI('btns').removeClass('btn-fill');
 	},
 	onShowMap: function() {
 		this.triggerMethod('hide:regions');

 		this.getUI('mapBtn').addClass('btn-fill');
 		this.getRegion('map').show(new View.Map({
 			model: this.model
 		}));
 	},
 	onShowInfo: function() {
 		this.triggerMethod('hide:regions');

 		this.getUI('toolsBtn').addClass('btn-fill');
 		this.getRegion('info').show(new View.Info({
 			model: this.model
 		}));

 	}
});

View.Info = Marionette.View.extend({
	template: '#Info',
	regions: new RegionSetter('cleanable','refillable'),
	initialize: function(options) {
		this.model = options.model;
	},
	onRender: function() {

		var refillable = new View.RefillableWrapper({
			id: this.model.id
		});
		this.getRegion('refillable').show(refillable);

		var cleanable = new View.CleanableWrapper({
			id: this.model.id
		});
		this.getRegion('cleanable').show(cleanable);

	}
})
// CLEANable
// CLEANable
// CLEANable
// CLEANable

Data.Cleanable = Backbone.Collection.extend({
	initialize: function(options) {
		this.url = '/back/index.php/units/'+options.id+'/cleanable';
	}
});

View.CleanableItem = Marionette.View.extend({
	template: '#CleanableItem',
	triggers: {
		'click' : 'show:popup'
	},
	onShowPopup: function() {
		console.log(this.model);
		var options = {
			title: this.model.get('name'),
			content: new View.CleanableTools({
				id: this.model.id,
				unitId: this.model.get('unitId')
			}),
		}
		var popup = new Popup(options);
		rootView.getRegion('popup').show(popup)
	},
});

View.CleanableTool = Marionette.View.extend({
	template: _.template('<header><%-cleaningToolName%></header>'),
	className: 'list-group-item-light',
	initialize: function() {
		console.log(this.model);
	}
});
View.CleanableToolEmpty = Marionette.View.extend({
	template: '#loading',
	initialize: function() {
		console.log(this.model);
	}
});
Data.CleanableTool = Backbone.Model.extend({
	defaults: function() {
		return {
			cleaningToolName: 'Sponge'
		}
	}
})
Data.CleanableTools = Backbone.Collection.extend({
	model: Data.CleanableTool,
	initialize: function(options) {
		this.url = '/back/index.php/units/'+options.unitId+'/cleanable/'+options.id
	}
});
View.CleanableTools = Marionette.CollectionView.extend({
	childView: View.CleanableTool,
	emptyView: View.CleanableToolEmpty,
	initialize: function(options) {
		this.collection = new Data.CleanableTools(options);
		this.collection.fetch();
	},
	collectionEvents: {
		'sync' : 'render'
	}
});

View.CleanableItemEmpty = Marionette.View.extend({
	template: _.template('EMPTY'),
});
View.CleanableWrapper = Marionette.View.extend({
	template: '#CleanableWrapper',
	regions: new RegionSetter('list'),
	initialize: function(options) {

	},
	onRender: function() {
		collection = new Data.Cleanable(this.options);
		collection.fetch();
		collection.on('sync', collection => {
			this.getRegion('list').show(new View.Cleanable({
				collection: collection
			}))
		})
	}
});
View.Cleanable = Marionette.CollectionView.extend({
	childView: View.CleanableItem,
	emptyView: View.CleanableItemEmpty,
	initialize: function(options) {
	},
});



// REFILLable
// REFILLable
// REFILLable
// REFILLable

Data.Refillable = Backbone.Collection.extend({
	initialize: function(options) {
		this.url = '/back/index.php/units/'+options.id+'/refillable'
	} 
});

View.RefillableItem = Marionette.View.extend({
	template: '#RefillableItem',
});
View.RefillableItemEmpty = Marionette.View.extend({
	template: _.template('EMPTY'),
});
View.RefillableWrapper = Marionette.View.extend({
	template: '#RefillableWrapper',
	regions: new RegionSetter('list'),
	initialize: function(options) {
		
	},
	onRender: function() {
		collection = new Data.Refillable(this.options);
		collection.fetch();
		collection.on('sync', collection => {
			this.getRegion('list').show(new View.Refillable({
				collection: collection
			}))
		})
	}
});
View.Refillable = Marionette.CollectionView.extend({
	childView: View.RefillableItem,
	emptyView: View.RefillableItemEmpty,
	initialize: function(options) {
		this.collection = options.collection
	},
});




// TOOLS
// TOOLS
// TOOLS
// TOOLS

View.Tools = Marionette.View.extend({
	className: 'list-group',
	template: '#units-tools',
	initialize: function (options) {
		this.model = options.model;
	},


});

View.List = Marionette.CollectionView.extend({
	className: 'list-group',
	tagName: 'ul',
	emptyView: View.ListItemEmpty,
	childView: View.ListItem,
	initialize: function(options) {
		this.collection = new Data.List(options);
		this.collection.fetch();
	},
	collectionEvents: {
		'sync' : 'onSync'
	},
	onChildviewOpenMap: function(view) {
		console.log(view.model);
		var model = view.model;
		this.triggerMethod('show:marker',model);
	},
	onSync: function() {
		console.log(this.collection.models);
		this.render();
	},
	onRender: function() {
	}
})




window.module_units = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}
}());
