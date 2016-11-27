(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};

	Data.Model = Backbone.Model.extend({
		defaults : function() {
			return {
				id: 1,
				name: 'DefaultRole',
				code: 'role',
			}
		}
	})
	Data.Collection = Backbone.Collection.extend({
		model: Data.Model,
	});
	View.Role = Marionette.View.extend({
		template: '#welcome-role',
		className: 'list-group-item',
		tagName: 'li',
		triggers: {
			'click' : 'open:role'
		},
		onOpenRole: function() {
			var roleCode = this.model.get('code');
			app.router.navigate('role/'+roleCode,{
				trigger: true
			})
		}

	});
	View.Roles = Marionette.CollectionView.extend({
		className: 'list-group',
		tagName: 'ul',
		childView: View.Role,
		initialize: function(options) {
			this.collection = options.collection;

		}
	})
	View.Entry = Marionette.View.extend({
		regions: new RegionSetter('roles', 'scenarios'),
		className: 'kek',
		template: '#welcome-entry',
		onRender: function() {
			var rolesView = new View.Roles({
				collection: new Data.Collection(data.roles)
			})
			var scenariosView = new View.Scenarios();
			// this.getRegion('roles').show(rolesView);
			this.getRegion('scenarios').show(scenariosView);
		}
	});

	View.Scenarios = Marionette.View.extend({
		template: '#welcome-scenarios',
		initialize: function() {

		},
		ui: {
			link: '[data-go]'
		},
		events: {
			'click @ui.link' : 'navigate'
		},
		navigate: function(e) {
			var target = e.currentTarget.dataset.go;

			app.router.navigate('scenarios/'+target, {
				trigger: true
			});
		}
	})


window.module_welcome = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}

}());