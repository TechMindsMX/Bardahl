﻿/*
 Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
*/
(function(){function t(b){return CKEDITOR.env.ie?b.$.clientWidth:parseInt(b.getComputedStyle("width"),10)}function n(b,i){var a=b.getComputedStyle("border-"+i+"-width"),g={thin:"0px",medium:"1px",thick:"2px"};0>a.indexOf("px")&&(a=a in g&&"none"!=b.getComputedStyle("border-style")?g[a]:0);return parseInt(a,10)}function v(b){var i=[],a=-1,g="rtl"==b.getComputedStyle("direction"),c;c=b.$.rows;for(var p=0,e,f,d,h=0,o=c.length;h<o;h++)d=c[h],e=d.cells.length,e>p&&(p=e,f=d);c=f;p=new CKEDITOR.dom.element(b.$.tBodies[0]);
e=p.getDocumentPosition();f=0;for(d=c.cells.length;f<d;f++){var h=new CKEDITOR.dom.element(c.cells[f]),o=c.cells[f+1]&&new CKEDITOR.dom.element(c.cells[f+1]),a=a+(h.$.colSpan||1),k,j,l=h.getDocumentPosition().x;g?j=l+n(h,"left"):k=l+h.$.offsetWidth-n(h,"right");o?(l=o.getDocumentPosition().x,g?k=l+o.$.offsetWidth-n(o,"right"):j=l+n(o,"left")):(l=b.getDocumentPosition().x,g?k=l:j=l+b.$.offsetWidth);h=Math.max(j-k,3);i.push({table:b,index:a,x:k,y:e.y,width:h,height:p.$.offsetHeight,rtl:g})}return i}
function u(b){(b.data||b).preventDefault()}function z(b){function i(){h=0;d.setOpacity(0);k&&a();var A=e.table;setTimeout(function(){A.removeCustomData("_cke_table_pillars")},0);f.removeListener("dragstart",u)}function a(){for(var a=e.rtl,b=a?l.length:w.length,d=0;d<b;d++){var c=w[d],f=l[d],g=e.table;CKEDITOR.tools.setTimeout(function(b,d,c,e,f,h){b&&b.setStyle("width",j(Math.max(d+h,0)));c&&c.setStyle("width",j(Math.max(e-h,0)));f&&g.setStyle("width",j(f+h*(a?-1:1)))},0,this,[c,c&&t(c),f,f&&t(f),
(!c||!f)&&t(g)+n(g,"left")+n(g,"right"),k])}}function g(a){u(a);for(var a=e.index,b=CKEDITOR.tools.buildTableMap(e.table),g=[],i=[],j=Number.MAX_VALUE,n=j,s=e.rtl,r=0,v=b.length;r<v;r++){var m=b[r],q=m[a+(s?1:0)],m=m[a+(s?0:1)],q=q&&new CKEDITOR.dom.element(q),m=m&&new CKEDITOR.dom.element(m);if(!q||!m||!q.equals(m))q&&(j=Math.min(j,t(q))),m&&(n=Math.min(n,t(m))),g.push(q),i.push(m)}w=g;l=i;x=e.x-j;y=e.x+n;d.setOpacity(0.5);o=parseInt(d.getStyle("left"),10);k=0;h=1;d.on("mousemove",p);f.on("dragstart",
u);f.on("mouseup",c,this)}function c(a){a.removeListener();i()}function p(a){r(a.data.getPageOffset().x)}var e,f,d,h,o,k,w,l,x,y;f=b.document;d=CKEDITOR.dom.element.createFromHtml('<div data-cke-temp=1 contenteditable=false unselectable=on style="position:absolute;cursor:col-resize;filter:alpha(opacity=0);opacity:0;padding:0;background-color:#004;background-image:none;border:0px none;z-index:10"></div>',f);s||f.getDocumentElement().append(d);this.attachTo=function(a){h||(s&&(f.getBody().append(d),
k=0),e=a,d.setStyles({width:j(a.width),height:j(a.height),left:j(a.x),top:j(a.y)}),s&&d.setOpacity(0.25),d.on("mousedown",g,this),f.getBody().setStyle("cursor","col-resize"),d.show())};var r=this.move=function(a){if(!e)return 0;if(!h&&(a<e.x||a>e.x+e.width))return e=null,h=k=0,f.removeListener("mouseup",c),d.removeListener("mousedown",g),d.removeListener("mousemove",p),f.getBody().setStyle("cursor","auto"),s?d.remove():d.hide(),0;a-=Math.round(d.$.offsetWidth/2);if(h){if(a==x||a==y)return 1;a=Math.max(a,
x);a=Math.min(a,y);k=a-o}d.setStyle("left",j(a));return 1}}function r(b){var i=b.data.getTarget();if("mouseout"==b.name){if(!i.is("table"))return;for(var a=new CKEDITOR.dom.element(b.data.$.relatedTarget||b.data.$.toElement);a&&a.$&&!a.equals(i)&&!a.is("body");)a=a.getParent();if(!a||a.equals(i))return}i.getAscendant("table",1).removeCustomData("_cke_table_pillars");b.removeListener()}var j=CKEDITOR.tools.cssLength,s=CKEDITOR.env.ie&&(CKEDITOR.env.ie7Compat||CKEDITOR.env.quirks);CKEDITOR.plugins.add("tableresize",
{requires:"tabletools",init:function(b){b.on("contentDom",function(){var i;b.document.getBody().on("mousemove",function(a){var a=a.data,g=a.getPageOffset().x;if(i&&i.move(g))u(a);else{var a=a.getTarget(),c;if(a.is("table")||a.getAscendant("tbody",1)){c=a.getAscendant("table",1);if(!(a=c.getCustomData("_cke_table_pillars")))c.setCustomData("_cke_table_pillars",a=v(c)),c.on("mouseout",r),c.on("mousedown",r);a:{c=0;for(var j=a.length;c<j;c++){var e=a[c];if(g>=e.x&&g<=e.x+e.width){g=e;break a}}g=null}g&&
(!i&&(i=new z(b)),i.attachTo(g))}}})})}})})();