var RegionSetter = function() {
	this.arguments = arguments;
	if (this.arguments.length == 1 && _.isArray(this.arguments[0])) {
		this.arguments = arguments[0]
	}
	this.prefix = 'r-';
	return this.builder()
};
RegionSetter.prototype.builder = function() {
	regions = {};
	for (var i in this.arguments) {
		var regionName = this.arguments[i]
		regions[regionName] = {
			el: this.prefix+regionName,
			replaceElement: true
		}
	}
	return regions
};
data = {
	contacts :[
		{
			title: 'Вконтакте',
			url: 'https://vk.com/pavepy',
		},{
			title: 'Facebook',
			url: 'https://facebook.com/p.shchegolev',
		},{
			title: 'GitHub',
			url: 'https://github.com/carduelis',
		},{
			title: 'GitHub pages',
			url: 'http://carduelis.github.io'
		},{
			title: 'Twitter',
			url: 'http://carduelis.github.io'
		},{
			title: '+79055332433',
			icon: 'phone',
			url: 'tel:+79055332433'
		},{
			title: 'pavepy@gmail.com',
			icon: 'pencil',
			url: 'mailto:pavepy@gmail.com'
		}
	],
	roles: [
		{
			id: 1,
			name: 'Guest',
			code: 'guest'
		},{
			id: 2,
			name: 'Courier',
			code: 'courier'
		},{
			id: 3,
			name: 'Employee',
			code: 'employee'
		},{
			id: 4,
			name: 'Cleaning manager',
			code: 'clean'
		}
	],
	users: [
		{
			id: 1,
			userName: 'Иван Иванов',
			userPic: 'http://placecage.com/100/100',
			balance: 200,
			totalOrders: 5
		},{
			id: 2,
			userName: 'Петр Кузнецов',
			userPic: 'http://placecage.com/101/101',
			balance: 1200,
			totalOrders: 3
		},{
			id: 3,
			userName: 'Константин Константинопольский',
			userPic: 'http://placecage.com/99/99',
			balance: -575,
			totalOrders: 4
		}
	]
}
var History = History || {};
History.View = History.View || {}; 
History.Data = History.Data || {};

var Orders = Orders || {};
Orders.View = Orders.View || {};
Orders.Data = Orders.Data || {};

var App = Marionette.Application.extend({
	region: '#app',
	initialize: function(options) {
		console.log('My options:', options);
	},
	onStart: function() {
		console.log(this.getRegion());
		window.rootView = new RootView();
		this.getRegion().show(rootView);
		// this.showView(new RootView()); // are the same
	},

});
app = new App();
app.on('start', 		(app,options) => {
	console.log(app,options);


	Backbone.history.start();
});
app.on('before:start', 	(e) => console.log(e));
RootView = Marionette.View.extend({
	template: '#t-app',
	regions: new RegionSetter('popup','breadcrumbs','content','notify'),
	onRender: function () {
	},

	onChildviewClosePopup: function() {
		rootView.getRegion('popup').empty();
	}
});
var ContactsModel = Backbone.Model.extend({
	defaults : function() {
		return {
			icon: 'ok',
			title: 'Связаться',
			url: null
		}
	}
})
var ContactsCollection = Backbone.Collection.extend({
	model: ContactsModel,
});
var ContactsItem = Marionette.View.extend({
	template: '#t-me-contacts-item',
	tag: 'li',
	className: 'contact-item',
	triggers: {
		'click' : 'contact:me'
	},
	onContactMe : function(){

	},
});




var Contacts = Marionette.CollectionView.extend({
	childView: ContactsItem,
	collection: new ContactsCollection(data.contacts)
});

var AboutMe = Backbone.Model.extend({
	defaults : function() {
		return {
			firstName: 'Pavel',
			lastName: 'Shchegolev',
			avatar: '//placecage.com/150/150',
			profession: 'Front-end web developer'
		}
	}
});
var Popup = Marionette.View.extend({
	template: '#popup',
	className: 'fixed-popup',
	regions: new RegionSetter('content'),
	initialize: function(options) {
		this.templateContext = {
			title: options.title
		};
	},
	triggers: {
		'click [data-action="close"]' : 'fade:out:popup'
	},
	onFadeOutPopup : function() {
		this.$el.addClass('fadeout');
		_.delay(()=>{
			this.triggerMethod('close:popup')
		},1000)
	},
	onClosePopup : function() {

	},
	onRender: function() {
		this.getRegion('content').show(this.options.content)
	}
});
var AboutMeView = Marionette.View.extend({
	_name: 'AboutMeView',
	template: '#t-me',
	className: 'about',
	model: new AboutMe(),
 	initialize: function(options) {
 		console.debug(this._name);
 		console.debug(this.model);
 		_.extend(this,options);
 	},
 	regions: {
 		contacts: '.contacts'
 	},
	
	onRender: function () {
		this.getRegion('contacts').show(new Contacts());
	}
});

History.Data.Model = Backbone.Model.extend({
	_name: 'User model',
	defaults: function() {
		return {
			id: this.id, 
			userName: 'Иванов Иван',
			userPic: 'http://placecage.com/200/200',
			balance: 200,
			totalOrders: 5
		}
	},
	initialize : function() {
		console.log(this.url());
	},

});


History.Data.Collection = Backbone.Collection.extend({
	model: History.Data.Model,	
	urlRoot: '/',
	initialize: function(options) {
		this.url = this.urlRoot + options.url;
	},
});
History.View.User = Marionette.View.extend({
	_name: 'History.View',
	className: 'user',
	template: '#t-user-snippet',
 	initialize: function(options) {
 		console.debug(this._name);
 		_.extend(this,options);
 	},
	onRender: function() {
		// this.getRegion('input').show(this.input(this.model))
	}
});
History.View.HistoryCollection = Marionette.CollectionView.extend({
	childView: History.View.User,
	collection: new History.Data.Collection(data.users),
	initialize: function (options) {
		console.log(options);
		console.log(this.childView);
		this.render();
	}
});


// Orders.View.Order = Marionette.View.extend({
// 	_name: 'Users.View',
// 	className: 'user',
// 	template: '#t-user-snippet',
//  	initialize: function(options) {
//  		console.debug(this._name);
//  		_.extend(this,options);
//  	},
// 	onRender: function() {
// 		// this.getRegion('input').show(this.input(this.model))
// 	}
// });
// Orders.View.OrderCollection = Marionette.CollectionView.extend({
// 	childView: Orders.View.Order,
// 	collection: new Orders.Data.Collection([{
// 		id: 1,
// 		userName: 'Иван Иванов',
// 		userPic: 'http://placecage.com/100/100',
// 		balance: 200,
// 		totalOrders: 5
// 	},{
// 		id: 2,
// 		userName: 'Петр Кузнецов',
// 		userPic: 'http://placecage.com/101/101',
// 		balance: 1200,
// 		totalOrders: 3
// 	},{
// 		id: 3,
// 		userName: 'Константин Константинопольский',
// 		userPic: 'http://placecage.com/99/99',
// 		balance: -575,
// 		totalOrders: 4
// 	}]),
// 	initialize: function (options) {
// 		console.log(options);
// 		console.log(this.childView);
// 		this.render();
// 	}
// });



$(document).ready(function () {
	$.getJSON('/back/index.php/session_data', function(data){
		console.warn(data.id);
		app.access = data;
		$('[data-action="account"][data-id="'+data.id+'"]').addClass('active');
		app.start();
	});
	$('[data-action="account"]').on('click', function(){
		$('[data-action="account"]').removeClass('active');
		var id = $(this).attr('data-id');
		$.get('/back/index.php/session_data/'+id, (data) => {
			window.location.reload();
		})
	});
});


function kek(height, width, left, top) {
	var height = _.random(200,400);
	var width = _.random(200,400);
	var left = _.random(20,70);
	var top = _.random(20,70);
	_.delay(function() {
		// if (_.random(228, 420) === 420) {
		$('body').append('<div class="kek'+height+'"></div>');
		$el = $('body').children('.kek'+height);
		var url = 'http://placecage.com/'+width+'/'+height;
		$el.append('<img src="'+url+'">')
		var css = function() {return{
			position: 'fixed',
			width: width+'px',
			height: height+'px',
			left: left+'%',
			'margin-left': '-'+width/2+'px',
			'margin-top': '-'+height/2+'px',
			top: top+'%',
			'z-index': 999
		}}
		$el.find('img').css(css());
		_.delay(function(){
			$el.remove();
		}, 2000);
		kek();
	}, 3000);
};

// kek();