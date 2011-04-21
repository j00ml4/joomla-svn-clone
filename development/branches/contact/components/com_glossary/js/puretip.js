function puretip () {
        var links = document.links || document.getElementsByTagName('a');
        var n = links.length;
        for (var i = 0; i < n; i++) {
                if (links[i].title && links[i].title != '' && links[i].className=="glossarylink") {
                        // add the title to anchor innerhtml
                        links[i].innerHTML += '<span>'+links[i].title+'</span>';
                        links[i].title = ''; // remove the title
                }
        }
};
if (typeof window.addEvent != 'function') window.addEvent('domready', function() {puretip();});
else window.onload = function(e) {puretip();}

