define(["jquery","util.misc"],function($){createCog=function(element,deprecatedAnimate,append,context,opacity){if($(element).length===0||$(element).find(".cog-container:visible").length){return false;}var append=typeof append=="undefined"?false:append;var cogString='<div class="lotus"><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div><div class="lotus_leaf"></div></div>';if(append){element.append(cogString);}else{element.html(cogString);}if(typeof opacity!="undefined"){element.find(".lotus").css({opacity:opacity});}return true;};changeTitle=function(title){return $("title").text(title);};startTitleActivityIndicator=function(){if(typeof titleActivityIndicatorInstance==="number"){return false;}titleActivityIndicatorInstance=window.setInterval(titleActivityIndicator,500);titleActivityIndicatorSavedTitle=$("title").text();return true;};stopTitleActivityIndicator=function(){if(typeof titleActivityIndicatorInstance!=="number"){return false;}window.clearInterval(titleActivityIndicatorInstance);changeTitle(titleActivityIndicatorSavedTitle);delete titleActivityIndicatorCounter;delete titleActivityIndicatorSavedTitle;delete titleActivityIndicatorInstance;return true;};titleActivityIndicator=function(){if(typeof titleActivityIndicatorCounter=="undefined"){titleActivityIndicatorCounter=0;titleActivityIndicatorCounterPos=true;}if(titleActivityIndicatorCounterPos===true){++titleActivityIndicatorCounter;}else{--titleActivityIndicatorCounter;}if(titleActivityIndicatorCounter===3){titleActivityIndicatorCounterPos=false;}else{if(titleActivityIndicatorCounter===0){titleActivityIndicatorCounterPos=true;}}var title=titleActivityIndicatorSavedTitle+".".repeatStr(titleActivityIndicatorCounter);changeTitle(title);};});