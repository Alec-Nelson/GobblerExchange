 $(document).ready(function(){
    document.getElementById('title').style.width="600px";
    document.getElementById('note').style.height="275px";
    document.getElementById('note').style.width="600px";

    var node = document.getElementById('note').get(0);
    setCursor(node,node.value.length);
});