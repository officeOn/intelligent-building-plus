function kek(e,t,o,i){var e=_.random(200,400),t=_.random(200,400),o=_.random(20,70),i=_.random(20,70);_.delay(function(){$("body").append('<div class="kek'+e+'"></div>'),$el=$("body").children(".kek"+e);var n="http://placecage.com/"+t+"/"+e;$el.append('<img src="'+n+'">');var a=function(){return{position:"fixed",width:t+"px",height:e+"px",left:o+"%","margin-left":"-"+t/2+"px","margin-top":"-"+e/2+"px",top:i+"%","z-index":999}};$el.find("img").css(a()),_.delay(function(){$el.remove()},2e3),kek()},3e3)}var RegionSetter=function(){return this.arguments=arguments,1==this.arguments.length&&_.isArray(this.arguments[0])&&(this.arguments=arguments[0]),this.prefix="r-",this.builder()};RegionSetter.prototype.builder=function(){regions={};for(var e in this.arguments){var t=this.arguments[e];regions[t]={el:this.prefix+t,replaceElement:!0}}return regions},data={contacts:[{title:"Вконтакте",url:"https://vk.com/pavepy"},{title:"Facebook",url:"https://facebook.com/p.shchegolev"},{title:"GitHub",url:"https://github.com/carduelis"},{title:"GitHub pages",url:"http://carduelis.github.io"},{title:"Twitter",url:"http://carduelis.github.io"},{title:"+79055332433",icon:"phone",url:"tel:+79055332433"},{title:"pavepy@gmail.com",icon:"pencil",url:"mailto:pavepy@gmail.com"}],roles:[{id:1,name:"Guest",code:"guest"},{id:2,name:"Courier",code:"courier"},{id:3,name:"Employee",code:"employee"},{id:4,name:"Cleaning manager",code:"clean"}],users:[{id:1,userName:"Иван Иванов",userPic:"http://placecage.com/100/100",balance:200,totalOrders:5},{id:2,userName:"Петр Кузнецов",userPic:"http://placecage.com/101/101",balance:1200,totalOrders:3},{id:3,userName:"Константин Константинопольский",userPic:"http://placecage.com/99/99",balance:-575,totalOrders:4}]};var History=History||{};History.View=History.View||{},History.Data=History.Data||{};var Orders=Orders||{};Orders.View=Orders.View||{},Orders.Data=Orders.Data||{};var App=Marionette.Application.extend({region:"#app",initialize:function(e){console.log("My options:",e)},onStart:function(){console.log(this.getRegion()),window.rootView=new RootView,this.getRegion().show(rootView)}});app=new App,app.on("start",function(e,t){console.log(e,t),Backbone.history.start()}),app.on("before:start",function(e){return console.log(e)}),RootView=Marionette.View.extend({template:"#t-app",regions:new RegionSetter("popup","breadcrumbs","content","notify"),onRender:function(){},onChildviewClosePopup:function(){rootView.getRegion("popup").empty()}});var ContactsModel=Backbone.Model.extend({defaults:function(){return{icon:"ok",title:"Связаться",url:null}}}),ContactsCollection=Backbone.Collection.extend({model:ContactsModel}),ContactsItem=Marionette.View.extend({template:"#t-me-contacts-item",tag:"li",className:"contact-item",triggers:{click:"contact:me"},onContactMe:function(){}}),Contacts=Marionette.CollectionView.extend({childView:ContactsItem,collection:new ContactsCollection(data.contacts)}),AboutMe=Backbone.Model.extend({defaults:function(){return{firstName:"Pavel",lastName:"Shchegolev",avatar:"//placecage.com/150/150",profession:"Front-end web developer"}}}),Popup=Marionette.View.extend({template:"#popup",className:"fixed-popup",regions:new RegionSetter("content"),initialize:function(e){this.templateContext={title:e.title}},triggers:{'click [data-action="close"]':"fade:out:popup"},onFadeOutPopup:function(){var e=this;this.$el.addClass("fadeout"),_.delay(function(){e.triggerMethod("close:popup")},1e3)},onClosePopup:function(){},onRender:function(){this.getRegion("content").show(this.options.content)}}),AboutMeView=Marionette.View.extend({_name:"AboutMeView",template:"#t-me",className:"about",model:new AboutMe,initialize:function(e){console.debug(this._name),console.debug(this.model),_.extend(this,e)},regions:{contacts:".contacts"},onRender:function(){this.getRegion("contacts").show(new Contacts)}});History.Data.Model=Backbone.Model.extend({_name:"User model",defaults:function(){return{id:this.id,userName:"Иванов Иван",userPic:"http://placecage.com/200/200",balance:200,totalOrders:5}},initialize:function(){console.log(this.url())}}),History.Data.Collection=Backbone.Collection.extend({model:History.Data.Model,urlRoot:"/",initialize:function(e){this.url=this.urlRoot+e.url}}),History.View.User=Marionette.View.extend({_name:"History.View",className:"user",template:"#t-user-snippet",initialize:function(e){console.debug(this._name),_.extend(this,e)},onRender:function(){}}),History.View.HistoryCollection=Marionette.CollectionView.extend({childView:History.View.User,collection:new History.Data.Collection(data.users),initialize:function(e){console.log(e),console.log(this.childView),this.render()}}),$(document).ready(function(){$.getJSON("/back/index.php/session_data",function(e){console.warn(e.id),app.access=e,$('[data-action="account"][data-id="'+e.id+'"]').addClass("active"),app.start()}),$('[data-action="account"]').on("click",function(){$('[data-action="account"]').removeClass("active");var e=$(this).attr("data-id");$.get("/back/index.php/session_data/"+e,function(e){window.location.reload()})})});
//# sourceMappingURL=main.js.map
