(function(){var DOM=tinymce.DOM;tinymce.create('tinymce.plugins.FullScreenPlugin',{init:function(ed,url){var t=this,s={},vp;t.editor=ed;ed.addCommand('mceFullScreen',function(){var win,de=document.documentElement;if(ed.getParam('fullscreen_is_enabled')){if(ed.getParam('fullscreen_new_window'))closeFullscreen();else{window.setTimeout(function(){tinymce.dom.Event.remove(window,'resize',t.resizeFunc);tinyMCE.get(ed.getParam('fullscreen_editor_id')).setContent(ed.getContent({format:'raw'}),{format:'raw'});tinyMCE.remove(ed);DOM.remove('mce_fullscreen_container');de.style.overflow=ed.getParam('fullscreen_html_overflow');DOM.setStyle(document.body,'overflow',ed.getParam('fullscreen_overflow'));window.scrollTo(ed.getParam('fullscreen_scrollx'),ed.getParam('fullscreen_scrolly'));},10);}return;}if(ed.getParam('fullscreen_new_window')){win=window.open(url+"/fullscreen.htm","mceFullScreenPopup","fullscreen=yes,menubar=no,toolbar=no,scrollbars=no,resizable=yes,left=0,top=0,width="+screen.availWidth+",height="+screen.availHeight);try{win.resizeTo(screen.availWidth,screen.availHeight);}catch(e){}}else{s.fullscreen_overflow=DOM.getStyle(document.body,'overflow',1)||'auto';s.fullscreen_html_overflow=DOM.getStyle(de,'overflow',1);vp=DOM.getViewPort();s.fullscreen_scrollx=vp.x;s.fullscreen_scrolly=vp.y;if(tinymce.isOpera&&s.fullscreen_overflow=='visible')s.fullscreen_overflow='auto';if(tinymce.isIE&&s.fullscreen_overflow=='scroll')s.fullscreen_overflow='auto';if(s.fullscreen_overflow=='0px')s.fullscreen_overflow='';DOM.setStyle(document.body,'overflow','hidden');de.style.overflow='hidden';vp=DOM.getViewPort();window.scrollTo(0,0);if(tinymce.isIE)vp.h-=1;n=DOM.add(document.body,'div',{id:'mce_fullscreen_container',style:'position:absolute;top:0;left:0;width:'+vp.w+'px;height:'+vp.h+'px;z-index:150;'});DOM.add(n,'div',{id:'mce_fullscreen'});tinymce.each(ed.settings,function(v,n){s[n]=v;});s.id='mce_fullscreen';s.width=n.clientWidth;s.height=n.clientHeight-15;s.fullscreen_is_enabled=true;s.fullscreen_editor_id=ed.id;s.theme_advanced_resizing=false;s.save_onsavecallback=function(){ed.setContent(tinyMCE.get(s.id).getContent({format:'raw'}),{format:'raw'});ed.execCommand('mceSave');};tinymce.each(ed.getParam('fullscreen_settings'),function(v,k){s[k]=v;});if(s.theme_advanced_toolbar_location==='external')s.theme_advanced_toolbar_location='top';t.fullscreenEditor=new tinymce.Editor('mce_fullscreen',s);t.fullscreenEditor.onInit.add(function(){t.fullscreenEditor.setContent(ed.getContent());});t.fullscreenEditor.render();tinyMCE.add(t.fullscreenEditor);t.fullscreenElement=new tinymce.dom.Element('mce_fullscreen_container');t.fullscreenElement.update();t.resizeFunc=tinymce.dom.Event.add(window,'resize',function(){var vp=tinymce.DOM.getViewPort();t.fullscreenEditor.theme.resizeTo(vp.w,vp.h);});}});ed.addButton('fullscreen',{title:'fullscreen.desc',cmd:'mceFullScreen'});ed.onNodeChange.add(function(ed,cm){cm.setActive('fullscreen',ed.getParam('fullscreen_is_enabled'));});},getInfo:function(){return{longname:'Fullscreen',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/fullscreen',version:tinymce.majorVersion+"."+tinymce.minorVersion};}});tinymce.PluginManager.add('fullscreen',tinymce.plugins.FullScreenPlugin);})();