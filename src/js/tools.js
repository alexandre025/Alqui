var _ = {
    byId: function(id){
        return document.getElementById(id);
    },
    byClass: function(className){
        return document.getElementsByClassName(className);
    },
    selectorAll: function(el){
        return document.querySelectorAll(el);
    },
    selector: function(el){
        return document.querySelector(el);
    },

    async: function(verb,url,datas,callback){
	 var self=this;
	 var xhr = new XMLHttpRequest();
	   xhr.open(verb, url);
        xhr.onload = function() {
		  if(xhr.status === 200){
		   callback.call(self,xhr);
		  }else{
		    console.log('error');
		  }
		}
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.send(datas);
    }
};
module.exports = _;