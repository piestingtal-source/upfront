define(["jquery","util.loader","knockout","deps/json2"],function($,loader,ko){save=function(){if(typeof isSavingAllowed==="undefined"||isSavingAllowed===false){return false;}if(typeof currentlySaving!=="undefined"&&currentlySaving===true){return false;}currentlySaving=true;savedTitle=$("title").text();saveButton=$("span#save-button");saveButton.text("Speichern...").addClass("active").css("cursor","wait");changeTitle("Visueller Editor: Speichern");startTitleActivityIndicator();$.post(UpFront.ajaxURL,{security:UpFront.security,action:"upfront_visual_editor",method:"save_options",options:JSON.stringify(GLOBALunsavedValues),layout:UpFront.viewModels.layoutSelector.currentLayout(),mode:UpFront.mode},function(response){delete currentlySaving;if(response==="0"){saveButton.stop(true);saveButton.text("Save");saveButton.removeClass("active");saveButton.css("cursor","pointer");return showErrorNotification({id:"error-wordpress-authentication",message:'<strong>Beachte!</strong><br /><br />Deine WordPress-Authentifizierung ist abgelaufen und Du musst Dichich anmelden, bevor Du speichern kannst.<br /><br /><a href="'+UpFront.adminURL+'" target="_blank">Klicke hier, um Dich anzumelden</a>, und wechsel dann zurück zu dem Fenster/der Registerkarte, in dem sich der visuelle Editor befindet.',closeTimer:false,closable:true});}else{if(typeof response.errors!=="undefined"||(typeof response!="object"&&response!="success")){saveButton.stop(true);saveButton.text("Speichern");saveButton.removeClass("active");saveButton.css("cursor","pointer");var errorMessage="Beim Speichern ist ein Fehler aufgetreten. Bitte versuche es erneut";if(typeof response.errors!="undefined"){errorMessage+="<br /><ul>";$.each(response.errors,function(errorIndex,errorValue){errorMessage+="<li>"+errorValue+"</li>";});errorMessage+="</ul>";}return showErrorNotification({id:"error-invalid-save-response",message:errorMessage,closeTimer:false,closable:true});}else{hideNotification("error-wordpress-authentication");hideNotification("error-invalid-save-response");saveButton.animate({boxShadow:"0 0 0 #7dd1e2"},350);setTimeout(function(){saveButton.css("boxShadow","");saveButton.stop(true);saveButton.text("Speichern");saveButton.removeClass("active");saveButton.css("cursor","pointer");if(typeof response["block-id-mapping"]!=="undefined"){$.each(response["block-id-mapping"],function(tempID,id){var block=$i('.block[data-temp-id="'+tempID+'"]');block.attr("id","block-"+id).data("id",id).attr("data-id",id).removeAttr("data-temp-id").removeAttr("data-desired-id").removeData("duplicateOf").removeData("temp-id").removeData("desired-id");updateBlockContentCover(block);if($("#block-"+tempID+"-tab").length){var currentSubTab=$("#block-"+tempID+"-tab").find(".sub-tabs .ui-tabs-active").attr("aria-controls");removePanelTab("block-"+tempID);openBlockOptions(block,currentSubTab);}});}if(typeof response["wrapper-id-mapping"]!=="undefined"){$.each(response["wrapper-id-mapping"],function(tempID,id){var wrapper=$i('.wrapper[data-temp-id="'+tempID+'"]');wrapper.attr("id","wrapper-"+id).data("id",id).attr("data-id",id).removeData("temp-id").removeData("desired-id");if($("#wrapper-"+tempID+"-tab").length){removePanelTab("wrapper-"+tempID);openWrapperOptions(id);}});}clearUnsavedValues();if(typeof response.snapshot!=="undefined"&&typeof response.snapshot.timestamp!=="undefined"){showNotification({id:"snapshot-saved",message:"Snapshot automatisch gespeichert.",success:true});UpFront.viewModels.snapshots.snapshots.unshift({id:response.snapshot.id,timestamp:response.snapshot.timestamp,comments:response.snapshot.comments});}if($("li.layout-selected").length){ko.dataFor($("li.layout-selected").get(0)).customized(true);}disallowSaving();setTimeout(function(){stopTitleActivityIndicator();changeTitle(savedTitle);showNotification({id:"saving-complete",message:"Speichern abgeschlossen!",closeTimer:3500,success:true});},150);},350);allowVEClose();}}});$.each(UpFront.codeMirrorEditors,function(index,editor){if(typeof editor.window!="undefined"&&!editor.window.closed){allowLiveCSSClose(editor.window);}});};clearUnsavedValues=function(){delete GLOBALunsavedValues;};allowSaving=function(){if((UpFront.mode=="grid"&&$i(".block").length===0)||(typeof UpFront.overlappingBlocks!="undefined"&&UpFront.overlappingBlocks)){return disallowSaving();}if(typeof isSavingAllowed!=="undefined"&&isSavingAllowed===true){return;}$("body").addClass("allow-saving");isSavingAllowed=true;prohibitVEClose();return true;};disallowSaving=function(){isSavingAllowed=false;$("body").removeClass("allow-saving");if(typeof UpFront.overlappingBlocks=="undefined"||!UpFront.overlappingBlocks){allowVEClose();}return true;};});