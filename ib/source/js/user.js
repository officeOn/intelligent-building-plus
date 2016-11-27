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
})

View.Entry = Marionette.View.extend({
	template: '#user-entry',
	regions: new RegionSetter('about', 'actions'),
	className: 'user',
	onRender: function() {
		this.getRegion('actions').show(new View.Actions());
		this.getRegion('about').show(new View.About());
	},
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
	},
});
Data.About = Backbone.Model.extend({
	url: '/back/index.php/rating/1',
	defaults: function() {
		return {
			rating: 3,
			name: 'Nicole',

		}
	}
})
View.About = Marionette.View.extend({
	tagName: 'div',
	template: '#loading',
	className: 'user-top',
	initialize: function(options) {
		this.model = new Data.About();
		this.model.fetch();
	},
	ui: {
		'star' : '.glyphicon-star.super'
	},
	onRender: function() {
		this.template = '#user-about';
	},
	onStartCheck: function() {
		this.getUI('star').removeClass('go');
		$.ajax({
			url: this.model.url+'/long',
			dataType: 'json',
			success: (data) => {
				if (data.delivered == 1) {

				}
				this.getUI('star').addClass('go');
				// _.delay(()=>{
				// 	this.triggerMethod('start:check')
				// }, 5000)
			},
			error: function() {
				alert('Sorry, we can not reach the server')
			},
			always: (data) => {
			}
		});
	},
	onSync: function() {
		this.render();
		this.triggerMethod('start:check');
	},
	modelEvents: {
		'sync' : 'onSync'
	},

});
View.Action = Marionette.View.extend({
	className: 'list-group-item list-group-item-user',
	tagName: 'li',
	template: '#user-action'
});
View.Actions = Marionette.CollectionView.extend({
	className: 'list-group',
	tagName: 'ul',
	childView: View.Action,
	initialize: function(options) {
		this.collection = new Backbone.Collection([{
			id: 1,
			title: 'Report an issue',
			description: 'Pick from a list...',
			icon: 'warning-sign'
		},{
			id: 2,
			title: 'Need tech support',
			description: 'Choose a ... ',
			icon: 'wrench'
		},{
			id: 3,
			title: 'Order a lunch',
			description: 'Favorite cafeteria is <b>Floor 4, "Eat me!"</b>',
			icon: 'cutlery'
		},{
			id: 4,
			title: 'Vote up for ...',
			description: null,
			icon: 'thumbs-up'
		},{
			id: 5,
			title: 'Rate clealiness',
			description: 'A <b>toilet</b> around your geo-position',
			icon: 'star'
		},])
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
	},
})




window.module_user = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}
}());
