(function(){
	
var View = View || {};
var Data = Data || {};
var Behaviors = Behaviors || {};

	Data.Model = Backbone.Model.extend({
		defaults : function() {
			return {
				title: 'a crumb',
				code: 'crumb',
			}
		}
	})
	Data.Collection = Backbone.Collection.extend({
		model: Data.Model,
	});
	View.Crumb = Marionette.View.extend({
		template: '#breadcrumbs-last',
		tagName: 'li',
		triggers: {

		},
		initialize: function() {
			if (this.model.get('last') === true) {
				this.template = '#breadcrumbs-last';
			} else {
				this.template = '#breadcrumbs-link';
			}
		}

	});
	View.Crumbs = Marionette.CollectionView.extend({
		className: 'breadcrumb',
		tagName: 'ol',
		childView: View.Crumb,
		initialize: function(options) {
			this.collection = options.collection;

		}
	})
	View.Entry = Marionette.View.extend({
		regions: new RegionSetter('crumbs'),
		className: 'kek',
		template: '#breadcrumbs-entry',
		initialize: function(options){
			
		},
		onRender: function() {
			var breadcrumbsView = new View.Crumbs({
				collection: new Data.Collection([{
					id: 1,
					title: 'home'
				},{
					id: 2,
					title: this.options.current,
					last: true
				}])
			})
			this.getRegion('crumbs').show(breadcrumbsView);
		}
	})


window.module_breadcrumbs = {
	View: View,
	Data: Data,
	Entry: View.Entry,
}

}());