!function(){var t=t||{},o=o||{};t.Entry=Marionette.View.extend({template:"#notify-btn",className:"fixed-btn",triggers:{click:"show:popup"},onShowPopup:function(){var t={title:"My actions",content:new module_user.View.Actions},o=new Popup(t);rootView.getRegion("popup").show(o)}}),t.EntryRoute=Marionette.View.extend({template:"#route-btn",className:"fixed-btn fixed-btn-route",triggers:{click:"show:popup"},onShowPopup:function(){var o={title:"My route",content:new t.Map},e=new Popup(o);rootView.getRegion("popup").show(e)}}),t.Map=Marionette.View.extend({template:"#t-map",map:null,className:"popup-map",initialize:function(t){this.collection=app.taskRouteCollection},onBeforeAttach:function(){this.$el.on("click",'[data-action="navigate"]',function(t){var o=t.currentTarget.dataset.id,e="zones/"+o+"/units";app.router.navigate(e,{trigger:!0})})},onAttach:function(){console.log(this.collection),"undefined"!=typeof L&&(console.warn(this.map),null!=this.map&&this.map.remove(),this.triggerMethod("init:map"))},onInitMap:function(){var t=this,o={scrollWheelZoom:!1};this.map=L.map(this.el,o),L.tileLayer("http://85.188.8.254/back/IB_floorplan_72.53mx72.53m/{z}/{x}/{y}.png",{attribution:'&copy; <a target="_blank" href="http://tieto.com">Tieto</a>',minZoom:1,maxZoom:6,continuousWorld:!1,tms:!0,noWrap:!0}).addTo(this.map),this.map.setView([0,-100],1);var e=L.latLngBounds([[-180,-180],[180,180]]);this.map.setMaxBounds(e),this.map.on("drag",function(){t.map.panInsideBounds(e,{animate:!0})}),_.forEach(this.collection.models,function(o){var e=JSON.parse(o.get("coordinates"));if(console.log(e),e){dataset=o.attributes;var n=(_.template($("#t-polygon-popup").html())(dataset),_.sample(["marker-success","marker-warning","marker-danger"])),a=(L.divIcon({className:"marker "+n}),_.random(10,100)),i=L.marker(e,{color:"hsl("+a+",70%,50%)"});t.map.addLayer(i)}}),this.map.on("click",function(t){console.log([t.latlng.lat,t.latlng.lng])})}}),window.module_notify={View:t,Data:o,Entry:t.Entry}}();
//# sourceMappingURL=notify.js.map