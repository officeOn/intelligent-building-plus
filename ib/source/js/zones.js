(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};

Data.ZoneItem = Backbone.Model.extend({
	defaults: function() {
		return {
			id: this.cid,
			name: 'No-title',
			health: 20
		}
	}
});
Data.Zones = Backbone.Collection.extend({
	url: '/back/index.php/zones',
	model: Data.ZoneItem,
})

View.Entry = Marionette.View.extend({
	template: '#zones-entry',
	regions: new RegionSetter('content'),
	events: {
		'click [data-action="trigger"]' : 'showContent',
		'click [data-action="back"]' : 'historyBack',
	},
	initialize: function(options){
		console.log(this,options)
	},
	historyBack: function() {
		app.router.navigate('',{
			trigger: true
		})
	},
	onRender: function() {
		this.zonesCollection = new Data.Zones();
		this.zonesCollection.fetch();
		this.zonesCollection.on('sync', collection => {
			this.getRegion('content').show(new View.Map({
				collection: collection
			}));
		})
		
	},
	showContent: function(e) {
		var showType = e.currentTarget.dataset.id;
		if (showType == 'map') {
			view = new View.Map({
				collection: this.zonesCollection
			})
		} else {
			view = new View.List({
				collection: this.zonesCollection
			})
		};
		this.getRegion('content').show(view);
	}
});

View.Map = Marionette.View.extend({
	template: '#t-map',
	map: null,
	className: 'maxi-map',
	initialize: function(options) {
		this.collection = options.collection;
	},
	onBeforeAttach: function() {
		this.$el.on('click', '[data-action="navigate"]', (e) => {
			var zoneId = e.currentTarget.dataset.id;
			var url = 'zones/'+zoneId+'/units';
			app.router.navigate(url, {
				trigger: true
			})
		})
	},
	onAttach: function() {
		console.log(this.collection);
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
		this.map.setView([0,-100], 1);
		var bounds = L.latLngBounds([[-180, -180], [180, 180]]);
		this.map.setMaxBounds(bounds);
		this.map.on('drag', () => {
			this.map.panInsideBounds(bounds, { animate: true });
		});

		_.forEach(this.collection.models, model => {

			var coords = model.get('coordinates');
			if (coords) {
				dataset = model.attributes;
				var popup = _.template($('#t-polygon-popup').html())(dataset);
				var classPostfix = _.sample(['marker-success','marker-warning','marker-danger'])
				var myIcon = L.divIcon({
					className: 'marker '+classPostfix
				});
				var hue = _.random(10,100);
				var polygon = L
					.polygon(coords, {
						color: 'hsl('+hue+',70%,50%)'
					})
					.bindPopup(popup)
				this.map.addLayer(polygon);
			}
		});
		this.map.on('click', function(e) {
			console.log([e.latlng.lat,e.latlng.lng]);    
		});
		// this.map.addLayer(popup);
	},
})

View.ListItem = Marionette.View.extend({
	template: '#zones-listItem',
	className: 'list-group-item-light',
	tagName: 'li',
	regions: new RegionSetter('map','tools'),
	ui: {
	},
	triggers: {
		'click' : 'open:units',
 	},
 	onOpenUnits: function() {
 		zoneId = this.model.id;
		var url = 'zones/'+zoneId+'/units';
		app.router.navigate(url, {
			trigger: true
		})
 	}
 	
});

View.List = Marionette.CollectionView.extend({
	className: 'list-group',
	tagName: 'ul',
	childView: View.ListItem,
	initialize: function(options) {
		this.collection = options.collection
	},
	onRender: function() {
	}
})




window.module_zones = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}
console.log(window['module_zones'])
}());
