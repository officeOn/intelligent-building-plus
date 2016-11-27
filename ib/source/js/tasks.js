(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};


View.Entry = Marionette.View.extend({
	template: '#TaskEntry',
	triggers: {
		// 'click' : 'show:popup'
	},
	regions: new RegionSetter('tasks'),
	onRender: function() {
		this.getRegion('tasks').show(new View.Tasks());
	}
	// onShowPopup: function() {
	// 	var options = {
	// 		title: 'My actions',
	// 		content: new module_user.View.Actions(),
	// 	};
	// 	var popup = new Popup(options);
	// 	rootView.getRegion('popup').show(popup)
	// },
});


Data.Model = Backbone.Model.extend({
	defaults: function() {
		return {
			author: 'Billy'
		}
	}
});
Data.Collection = Backbone.Collection.extend({
	url: '/back/index.php/tasks',
});

View.Task = Marionette.View.extend({
	className: 'task',
	template: '#Task',
	templateContext: function() {
		return {
			state: 'default',
			stateName: 'clean',
			highlighted: this.model.id % 3 == 1,
		}
	},
	onBeforeRender: function() {
		if (this.model.id % 3 == 1) {
			this.$el.addClass('highlighted');
		}
	},
	initialize: function() {
		app.taskRouteCollection = new Data.RouteCollection();
		
	},
	triggers: {
		'click [data-action="add"]' : 'add',
		'click [data-action="remove"]' : 'remove'
	},
	onAdd: function() {
		this.$el.find('.actions > div').toggleClass('hide');
		app.taskRouteCollection.add(this.model);
	},
	onRemove: function() {
		this.$el.find('.actions > div').toggleClass('hide');
		app.taskRouteCollection.remove(this.model);
	}
});

Data.RouteCollection = Backbone.Collection.extend({
	initialize: function() {
		this.on('update', function(){
			rootView.getRegion('notify').show(new module_notify.View.EntryRoute());
			if (this.length == 0) {
				rootView.getRegion('notify').show(new module_notify.View.Entry());
			}
		});
	}
});

View.Tasks = Marionette.CollectionView.extend({
	childView: View.Task,
	className: 'tasks',
	initialize: function(options) {
		this.collection = new Data.Collection();
		this.collection.fetch();
		this.collection.on('sync', collection => this.render()); 
	},

})


window.module_tasks = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}

}());