jQuery.get(pandastudio_framework.route+"pandastudio/framework/get_option_json",function(t){"string"==typeof t&&(t=JSON.parse(t));var e=t;window.vue_rest=new Vue({el:"#vue_rest",data:function(){return{tabIndex:"0",loading:!0,show:!0,singleSelect:{loading:!1,query:[]},tabs:e}},mounted:function(){this.loading=!0;for(var t={},e=0;e<this.tabs.length;e++)for(var n=0;n<this.tabs[e].content.length;n++)null!=this.tabs[e].content[n].name&null!=this.tabs[e].content[n].value&&(t[this.tabs[e].content[n].name]="");var a=this;jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/get_option",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify(t)}).done(function(t){for(var e=0;e<a.tabs.length;e++)for(var n=0;n<a.tabs[e].content.length;n++)if(null!=a.tabs[e].content[n].name&null!=a.tabs[e].content[n].value)switch(a.tabs[e].content[n].type){case"slider":case"inputNumber":t[a.tabs[e].content[n].name]?a.tabs[e].content[n].value=parseFloat(t[a.tabs[e].content[n].name]):a.tabs[e].content[n].value=0;break;case"singleSelect":a.tabs[e].content[n].value=parseFloat(t[a.tabs[e].content[n].name]);break;case"multitypes":case"categoriesSelect":""==t[a.tabs[e].content[n].name]?a.tabs[e].content[n].value=[]:a.tabs[e].content[n].value=t[a.tabs[e].content[n].name];break;case"select":t[a.tabs[e].content[n].name]?a.tabs[e].content[n].value=t[a.tabs[e].content[n].name]:a.tabs[e].content[n].value=null;break;default:a.tabs[e].content[n].value=t[a.tabs[e].content[n].name]}var o=[];for(e=0;e<a.tabs.length;e++)for(n=0;n<a.tabs[e].content.length;n++)if(null!=a.tabs[e].content[n].name&null!=a.tabs[e].content[n].value)switch(a.tabs[e].content[n].type){case"singleSelect":var s=a.tabs[e].content[n].value;o.indexOf(s)<0&&o.push(s);break;case"multitypes":for(var i=0;i<a.tabs[e].content[n].types.length;i++)if("singleSelect"==a.tabs[e].content[n].types[i].type)for(var r=0;r<a.tabs[e].content[n].value.length;r++){s=a.tabs[e].content[n].value[r][a.tabs[e].content[n].types[i].name];o.indexOf(s)<0&&o.push(s)}}for(e=0;e<o.length;e++)a.queryExistValue(!0,o[e],!0);a.loading=!1}).fail(function(t){alert(t),a.loading=!1,a.show=!1,a.$alert("连接服务器失败或后台读取出错！","数据读取失败",{confirmButtonText:"确定"})})},methods:{updateOption:function(){for(var t={},e=0;e<this.tabs.length;e++)for(var n=0;n<this.tabs[e].content.length;n++)null!=this.tabs[e].content[n].name&null!=this.tabs[e].content[n].value&&(t[this.tabs[e].content[n].name]=this.tabs[e].content[n].value);var a=this;jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/update_option",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify(t)}).done(function(t){t.state?a.$message.success("保存成功！"):a.$notify.error({title:"保存失败",message:"授权错误！"})}).fail(function(){a.$notify.error({title:"保存失败",message:"连接服务器失败或后台保存出错！"})})},mediaUpload:function(t,e,n){var a=this,o=wp.media({title:"上传",button:{text:"插入"},multiple:!1});o.open(),o.on("select",function(){attachment=o.state().get("selection").first().toJSON(),value=attachment.url;for(var s=0;s<a.tabs.length;s++)for(var i=0;i<a.tabs[s].content.length;i++)a.tabs[s].content[i].name==t&&(""!=n?a.tabs[s].content[i].value[e][n]=value:a.tabs[s].content[i].value=value)})},multiMediaUpload:function(t){var e=this,n=wp.media({title:"按住 ctrl 批量选择",button:{text:"批量插入"},multiple:!0});n.open(),n.on("select",function(){for(var a=n.state().get("selection").toJSON(),o=[],s=0;s<a.length;s++)o[s]=a[s].url;for(s=0;s<e.tabs.length;s++)for(var i=0;i<e.tabs[s].content.length;i++)e.tabs[s].content[i].name==t&&("string"==typeof e.tabs[s].content[i].value?e.tabs[s].content[i].value=o:e.tabs[s].content[i].value=e.tabs[s].content[i].value.concat(o))})},multiMediaUpload_input:function(t){var e=this;this.$prompt("请输入图片地址","外链图片",{confirmButtonText:"确定",cancelButtonText:"取消"}).then(function(n){for(var a=n.value,o=0;o<e.tabs.length;o++)for(var s=0;s<e.tabs[o].content.length;s++)e.tabs[o].content[s].name==t&&("string"==typeof e.tabs[o].content[s].value?e.tabs[o].content[s].value=[a]:e.tabs[o].content[s].value=e.tabs[o].content[s].value.concat(a))})},addMultiTypes:function(t){for(var e=0;e<this.tabs.length;e++)for(var n=0;n<this.tabs[e].content.length;n++)if(this.tabs[e].content[n].name==t){for(var a=this.tabs[e].content[n],o={},s=0;s<a.types.length;s++)o[a.types[s].name]="";a.value.push(o)}},removeMultiTypes:function(t,e){for(var n=0;n<this.tabs.length;n++)for(var a=0;a<this.tabs[n].content.length;a++)if(this.tabs[n].content[a].name==t){var o=this.tabs[n].content[a];o.value.splice(e,1)}},removeMultiUpload:function(t,e){for(var n=0;n<this.tabs.length;n++)for(var a=0;a<this.tabs[n].content.length;a++)if(this.tabs[n].content[a].name==t){var o=this.tabs[n].content[a];o.value.splice(e,1)}},removeAllMultiUpload:function(t){for(var e=0;e<this.tabs.length;e++)for(var n=0;n<this.tabs[e].content.length;n++)this.tabs[e].content[n].name==t&&(this.tabs[e].content[n].value="")},categorySelect:function(){return pandastudio_framework.categorySelector},querySinglePosts:function(t){""!==t?(this.singleSelect.loading=!0,_this=this,setTimeout(function(){jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/wp_query",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify({keyword:t})}).done(function(t){_this.singleSelect.query=t,_this.singleSelect.loading=!1}).fail(function(){_this.singleSelect.query=[],_this.singleSelect.loading=!1})},200)):this.singleSelect.query=[]},queryExistValue:function(t,e,n){t&&(this.singleSelect.query=[],this.singleSelect.loading=!0,_this=this,jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/wp_query",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify({keyword:e})}).done(function(t){n?_this.singleSelect.query.push(t[0]):_this.singleSelect.query=t}).fail(function(){n||(_this.singleSelect.query=[])}).always(function(){_this.singleSelect.loading=!1}))},showSaveBtn:function(t){for(var e=!1,n=t.length-1;n>=0;n--)e|=null!=t[n].value;return e},swapItems:function(t,e,n){return t[e]=t.splice(n,1,t[e])[0],t},multitypeMoveUp:function(t,e){0!=e&&this.swapItems(t,e,e-1)},multitypeMoveDown:function(t,e){e!=t.length-1&&this.swapItems(t,e,e+1)},uploadData:function(){window.fileInput=document.createElement("input"),fileInput.type="file",fileInput.accept=".json",fileInput.onchange=this.readFile;var t=document.createEvent("MouseEvents");t.initMouseEvent("click",!0,!1,window,0,0,0,0,0,!1,!1,!1,!1,0,null),fileInput.dispatchEvent(t)},readFile:function(){var t=fileInput.files[0];if(t){var e=new FileReader;e.readAsText(t);var n=this;e.onload=function(){try{var t=JSON.parse(this.result);if(t){for(var e={},a=0;a<n.tabs.length;a++)for(var o=0;o<n.tabs[a].content.length;o++)null!=n.tabs[a].content[o].name&null!=n.tabs[a].content[o].value&&(e[n.tabs[a].content[o].name]="");var s=0,i=0;for(var r in e)for(var l in i++,t)if(r==l){e[r]=t[l],s++;break}var u=i-s;confirmStr=s==i?"此操作将使用您上传的设置项覆盖到服务器上":"从您上传的数据中匹配到 "+s+" 个设置项，共需要 "+i+"个设置项。未匹配到的 "+u+" 个选项将被置空",n.$confirm(confirmStr,"确认这样做",{confirmButtonText:"确定",cancelButtonText:"取消",type:"info"}).then(function(){jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/update_option",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify(e)}).done(function(t){t.state?(n.$message.success("成功导入，页面正在刷新..."),window.setTimeout(function(){window.location.reload()},500)):n.$notify.error({title:"保存失败",message:"授权错误！"})}).fail(function(){n.$notify.error({title:"保存失败",message:"连接服务器失败或后台保存出错！"})})}).catch(function(){})}}catch(t){n.$alert("请选择由本主题导出的 JSON 格式文件","数据类型错误",{confirmButtonText:"确定",type:"error"})}}}document.getElementById("uploadData").value=""},downloadData:function(){this.loading=!0;for(var t={},e=0;e<this.tabs.length;e++)for(var n=0;n<this.tabs[e].content.length;n++)null!=this.tabs[e].content[n].name&null!=this.tabs[e].content[n].value&&(t[this.tabs[e].content[n].name]="");var a=this;jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/get_option",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify(t)}).done(function(t){var e=new Date,n=e.getFullYear().toString()+(e.getMonth()+1).toString()+e.getDate().toString()+e.getHours().toString()+e.getMinutes().toString()+e.getSeconds().toString(),o=new Blob([JSON.stringify(t)]),s=document.createElement("a");s.download=pandastudio_framework.blog_name+"_"+n+".json",s.href=URL.createObjectURL(o);var i=document.createEvent("MouseEvents");i.initMouseEvent("click",!0,!1,window,0,0,0,0,0,!1,!1,!1,!1,0,null),s.dispatchEvent(i),URL.revokeObjectURL(s.href),a.loading=!1}).fail(function(){a.loading=!1,a.$alert("连接服务器失败或后台读取出错！","数据下载失败",{confirmButtonText:"确定"})})},clearData:function(){var t=this;this.$confirm("此操作将清空本主题的所有设置选项","请确认",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){for(var e={},n=0;n<t.tabs.length;n++)for(var a=0;a<t.tabs[n].content.length;a++)null!=t.tabs[n].content[a].name&null!=t.tabs[n].content[a].value&&(e[t.tabs[n].content[a].name]="");jQuery.ajax({url:pandastudio_framework.route+"pandastudio/framework/update_option",type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",pandastudio_framework.nonce)},data:JSON.stringify(e)}).done(function(e){e.state?(t.$message.success("已清空主题设置项，页面正在刷新..."),window.setTimeout(function(){window.location.reload()},500)):t.$notify.error({title:"数据表上传失败",message:"授权错误！"})}).fail(function(){t.$notify.error({title:"清空数据失败",message:"连接服务器失败或后台保存出错！"})})}).catch(function(){})},gear_show:function(t,e){if(null==t|""==t|null==e)return!0;for(var n=0;n<this.tabs.length;n++)for(var a=0;a<this.tabs[n].content.length;a++)if(this.tabs[n].content[a].name==t&this.tabs[n].content[a].value==e)return!0;return!1}}})}).error(function(){alert("Option数据获取失败！请检查：\n1、WordPress版本大于4.7\n2、Rest API是否被插件关闭\n3、服务器配置不正确导致“固定链接”故障，请将“设置-固定链接”设置为“朴素”并保存\n4、请检查“设置-常规”，WordPress安装地址是否与当前浏览器地址栏的地址不一致？")});