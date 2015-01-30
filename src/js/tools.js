var $ = {
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
    }
}