(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};

Data.ListItem = Backbone.Model.extend({
	defaults: function() {
		return {
			id: this.cid,
			name: 'No-title',
			health: 20
		}
	}
});
Data.List = Backbone.Collection.extend({
	url: '/back/index.php/health',
	model: Data.ListItem,
	initialize: function(options) {
		if (options.id) {
			this.url = '/back/index.php/zones/'+options.id+'/units'
		}
	}
});

View.Entry = Marionette.View.extend({
	template: '#buildingHealth-entry',
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

View.ListItem = Marionette.View.extend({
	template: '#buildingHealth-listItem',
	className: 'list-group-item-light',
	tagName: 'li',
	regions: new RegionSetter('map','tools'),
	ui: {
		'btnHolder' : '.btn-holder',
		'btns': '[data-action="trigger"]',
		'mapBtn' : '[data-id="map"]',
		'toolsBtn' : '[data-id="tools"]',
		'closeBtn' : '[data-action="close"]',
	},
	triggers: {
		// click : 'open:map'
		'click header': 'toggle:buttons',
		'click @ui.mapBtn': 'show:map',
		'click @ui.toolsBtn': 'show:tools',
		// 'click @ui.closeBtn': 'close',
 	},
 	onToggleButtons: function() {
 		this.$el.toggleClass('opened');
 		this.getUI('btnHolder').slideToggle();
 		this.triggerMethod('hide:regions');
 	},
 	onHideRegions: function() {
 		this.getRegion('map').empty();
 		this.getRegion('tools').empty();
 		this.getUI('btns').removeClass('btn-fill');
 	},
 	onShowMap: function() {
 		this.triggerMethod('hide:regions');

 		this.getUI('mapBtn').addClass('btn-fill');
 		this.getRegion('map').show(new View.Map({
 			model: this.model
 		}));
 	},
 	onShowTools: function() {
 		this.triggerMethod('hide:regions');

 		this.getUI('toolsBtn').addClass('btn-fill');
 		this.getRegion('tools').show(new View.Tools({
 			model: this.model
 		}));

 	}
});


View.Tools = Marionette.View.extend({
	className: 'list-group',
	template: '#buildingHealth-tools',
	initialize: function (options) {
		this.model = options.model;
	},


});

View.List = Marionette.CollectionView.extend({
	className: 'list-group',
	tagName: 'ul',
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




window.module_buildingHealth = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}
console.log(window['module_buildingHealth'])
}());
