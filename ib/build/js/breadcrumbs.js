!function(){var e=e||{},t=t||{};t.Model=Backbone.Model.extend({defaults:function(){return{title:"a crumb",code:"crumb"}}}),t.Collection=Backbone.Collection.extend({model:t.Model}),e.Crumb=Marionette.View.extend({template:"#breadcrumbs-last",tagName:"li",triggers:{},initialize:function(){this.model.get("last")===!0?this.template="#breadcrumbs-last":this.template="#breadcrumbs-link"}}),e.Crumbs=Marionette.CollectionView.extend({className:"breadcrumb",tagName:"ol",childView:e.Crumb,initialize:function(e){this.collection=e.collection}}),e.Entry=Marionette.View.extend({regions:new RegionSetter("crumbs"),className:"kek",template:"#breadcrumbs-entry",initialize:function(e){},onRender:function(){var i=new e.Crumbs({collection:new t.Collection([{id:1,title:"home"},{id:2,title:this.options.current,last:!0}])});this.getRegion("crumbs").show(i)}}),window.module_breadcrumbs={View:e,Data:t,Entry:e.Entry}}();
//# sourceMappingURL=breadcrumbs.js.map