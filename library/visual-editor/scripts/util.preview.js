define(["jquery"],function($){devicesMenu=function(){$(document).on("click",".devices-wrapper button",function(){applyMode($(this).data("device"));});};applyMode=function(mode){$(".devices-wrapper button").removeClass("active");$("#customize-preview").removeClass("preview-desktop preview-tablet preview-mobile");$(".preview-"+mode).addClass("active");$("#customize-preview").addClass("preview-"+mode);localStorage["visual-editor-preview-mode"]=mode;if(mode=="tablet"){$("#customize-preview iframe#content").attr("width",720);}else{if(mode=="mobile"){$("#customize-preview iframe#content").attr("width",320);}else{$("#customize-preview iframe#content").removeAttr("width");}}};return{init:function(){var mode=localStorage["visual-editor-preview-mode"];if(mode==undefined){mode="desktop";}applyMode(mode);devicesMenu();}};});