var Router = Marionette.AppRouter.extend({
	routes: { 
		"" : 				"welcome",
		"role/:id":			"role",
		"scenarios/:id":	"scenarios",
		"zones/:id/units":	"units",
		"reports(/p:pid)(/:-*params)" : "reports", // все репорты с фильтрацией и паджинаци
		
		"data/:entity(/p:page)(/f*params)":	"rootCollection",
		"data/:entity/:id/(:childEntity)(/p:page)(/f*params)":	"childCollection",
		"data/:entity/:id":			"testEntity",
		"data(/*path)" : "notValid",
		"welcome" : "welcome"
	},
	error: function() {
		alert('Ошибка')
	}, 
	welcome: function () {
		rootView.getRegion('content').show(new module_welcome.Entry());
	},
	role: function (role) {
		// app.getRegion()
	},
	units: function(zoneId) {

		var module = new window['module_units'].Entry({
			id: zoneId
		});
		rootView.getRegion('content').show(module);
	},
	scenarios: function(scenario) {
		var moduleName = 'module_'+scenario;
		console.log(moduleName);
		console.log(window[moduleName])
		var module = new window[moduleName].Entry();
		rootView.getRegion('content').show(module);
	
	},
	onRoute: function(routeName) {
		console.log(routeName)
		rootView.getRegion('breadcrumbs').show(new module_breadcrumbs.Entry({
			current: routeName
		}));
		rootView.getRegion('notify').show(new module_notify.Entry());
		// app.getRegion('breadcrumbs').show(new module_breadcrumbs.Entry());
	}

});

// init router, backbone.history.start initiated in main.js
app.router = new Router();
