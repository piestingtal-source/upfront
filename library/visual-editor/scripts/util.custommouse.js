define(["jquery","jqueryUI"],function($){$.widget("ui.custommouse",$.ui.mouse,{options:{mouseStart:function(e){},mouseDrag:function(e){},mouseStop:function(e){},mouseCapture:function(e){return true;}},_mouseStart:function(e){return this.options.mouseStart(e);},_mouseDrag:function(e){return this.options.mouseDrag(e);},_mouseStop:function(e){return this.options.mouseStop(e);},_mouseCapture:function(e){return this.options.mouseCapture(e);},widgetEventPrefix:"custommouse",_init:function(){return this._mouseInit();},_create:function(){return this.element.addClass("ui-custommouse");},_destroy:function(){this._mouseDestroy();return this.element.removeClass("ui-custommouse");}});});