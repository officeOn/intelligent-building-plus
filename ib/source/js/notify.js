(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};


View.Entry = Marionette.View.extend({
	template: '#notify-btn',
	className: 'fixed-btn',
	triggers: {
		'click' : 'show:popup'
	},
	onShowPopup: function() {
		var options = {
			title: 'My actions',
			content: new module_user.View.Actions(),
		}
		var popup = new Popup(options);
		rootView.getRegion('popup').show(popup)
	},
});


View.EntryRoute = Marionette.View.extend({
	template: '#route-btn',
	className: 'fixed-btn fixed-btn-route',
	triggers: {
		'click' : 'show:popup'
	},
	onShowPopup: function() {
		var options = {
			title: 'My route',
			content: new View.Map(),
		}
		var popup = new Popup(options);
		rootView.getRegion('popup').show(popup)
	},
});


View.Map = Marionette.View.extend({
	template: '#t-map',
	map: null,
	className: 'popup-map',
	initialize: function(options) {
		this.collection = app.taskRouteCollection;
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

			var coords = JSON.parse(model.get('coordinates'));
			console.log(coords);
			if (coords) {
				dataset = model.attributes;
				var popup = _.template($('#t-polygon-popup').html())(dataset);
				var classPostfix = _.sample(['marker-success','marker-warning','marker-danger'])
				var myIcon = L.divIcon({
					className: 'marker '+classPostfix
				});
				var hue = _.random(10,100);
				var polyline = L
					.marker(coords, {
						color: 'hsl('+hue+',70%,50%)'
					})
					// .bindPopup(popup)
				this.map.addLayer(polyline);
			}
		});
		this.map.on('click', function(e) {
			console.log([e.latlng.lat,e.latlng.lng]);    
		});
		// this.map.addLayer(popup);
	},
})



window.module_notify = {
	View: View,
	Data: Data,
	Entry: View.Entry,

}

}());