if(typeof(jQuery)!="undefined"){if(typeof(jQuery.fn.hoverIntent)=="undefined"){(function(b){b.fn.hoverIntent=function(p,r){var g={sensitivity:7,interval:100,timeout:0};g=b.extend(g,r?{over:p,out:r}:p);var a,f,t,v;var u=function(c){a=c.pageX;f=c.pageY};var w=function(c,d){d.hoverIntent_t=clearTimeout(d.hoverIntent_t);if((Math.abs(t-a)+Math.abs(v-f))<g.sensitivity){b(d).unbind("mousemove",u);d.hoverIntent_s=1;return g.over.apply(d,[c])}else{t=a;v=f;d.hoverIntent_t=setTimeout(function(){w(c,d)},g.interval)}};var s=function(c,d){d.hoverIntent_t=clearTimeout(d.hoverIntent_t);d.hoverIntent_s=0;return g.out.apply(d,[c])};var x=function(e){var d=this;var c=(e.type=="mouseover"?e.fromElement:e.toElement)||e.relatedTarget;while(c&&c!=this){try{c=c.parentNode}catch(e){c=this}}if(c==this){if(b.browser.mozilla){if(e.type=="mouseout"){d.mtout=setTimeout(function(){q(e,d)},30)}else{if(d.mtout){d.mtout=clearTimeout(d.mtout)}}}return}else{if(d.mtout){d.mtout=clearTimeout(d.mtout)}q(e,d)}};var q=function(e,d){var c=jQuery.extend({},e);if(d.hoverIntent_t){d.hoverIntent_t=clearTimeout(d.hoverIntent_t)}if(e.type=="mouseover"){t=c.pageX;v=c.pageY;b(d).bind("mousemove",u);if(d.hoverIntent_s!=1){d.hoverIntent_t=setTimeout(function(){w(c,d)},g.interval)}}else{b(d).unbind("mousemove",u);if(d.hoverIntent_s==1){d.hoverIntent_t=setTimeout(function(){s(c,d)},g.timeout)}}};return this.mouseover(x).mouseout(x)}})(jQuery)}jQuery(document).ready(function(b){var a=function(c,e){var f=b(e),d=f.attr("tabindex");if(d){f.attr("tabindex","0").attr("tabindex",d)}};b("#wpadminbar").removeClass("nojq").removeClass("nojs").find("li.menupop").hoverIntent({over:function(c){b(this).addClass("hover")},out:function(c){b(this).removeClass("hover")},timeout:180,sensitivity:7,interval:100});b("#wp-admin-bar-get-shortlink").click(function(c){c.preventDefault();b(this).addClass("selected").children(".shortlink-input").blur(function(){b(this).parents("#wp-admin-bar-get-shortlink").removeClass("selected")}).focus().select()});b("#wpadminbar li.menupop > .ab-item").bind("keydown.adminbar",function(d){if(d.which!=13){return}var c=b(d.target);d.stopPropagation();d.preventDefault();c.parent().toggleClass("hover");c.siblings(".ab-sub-wrapper").find(".ab-item").each(a)}).each(a)})}else{(function(i,k){var c=function(n,m,d){if(n.addEventListener){n.addEventListener(m,d,false)}else{if(n.attachEvent){n.attachEvent("on"+m,function(){return d.call(n,window.event)})}}},e,f=new RegExp("\\bhover\\b","g"),a=[],j=new RegExp("\\bselected\\b","g"),g=function(m){var d=a.length;while(d--){if(a[d]&&m==a[d][1]){return a[d][0]}}return false},h=function(s){var n,d,q,m,p,r,u=[],o=0;while(s&&s!=e&&s!=i){if("LI"==s.nodeName.toUpperCase()){u[u.length]=s;d=g(s);if(d){clearTimeout(d)}s.className=s.className?(s.className.replace(f,"")+" hover"):"hover";m=s}s=s.parentNode}if(m&&m.parentNode){p=m.parentNode;if(p&&"UL"==p.nodeName.toUpperCase()){n=p.childNodes.length;while(n--){r=p.childNodes[n];if(r!=m){r.className=r.className?r.className.replace(j,""):""}}}}n=a.length;while(n--){q=false;o=u.length;while(o--){if(u[o]==a[n][1]){q=true}}if(!q){a[n][1].className=a[n][1].className?a[n][1].className.replace(f,""):""}}},l=function(d){while(d&&d!=e&&d!=i){if("LI"==d.nodeName.toUpperCase()){(function(m){var n=setTimeout(function(){m.className=m.className?m.className.replace(f,""):""},500);a[a.length]=[n,m]})(d)}d=d.parentNode}},b=function(p){var n,d,o,m=p.target||p.srcElement;while(true){if(!m||m==i||m==e){return}if(m.id&&m.id=="wp-admin-bar-get-shortlink"){break}m=m.parentNode}if(p.preventDefault){p.preventDefault()}p.returnValue=false;if(-1==m.className.indexOf("selected")){m.className+=" selected"}for(n=0,d=m.childNodes.length;n<d;n++){o=m.childNodes[n];if(o.className&&-1!=o.className.indexOf("shortlink-input")){o.focus();o.select();o.onblur=function(){m.className=m.className?m.className.replace(j,""):""};break}}return false};c(k,"load",function(){e=i.getElementById("wpadminbar");if(i.body&&e){i.body.appendChild(e);if(e.className){e.className=e.className.replace(/nojs/,"")}c(e,"mouseover",function(d){h(d.target||d.srcElement)});c(e,"mouseout",function(d){l(d.target||d.srcElement)});c(e,"click",b)}if(k.location.hash){k.scrollBy(0,-32)}})})(document,window)};