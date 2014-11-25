<?php 
defined('_JEXEC') or die('Restricted access');?>
<script type="text/javascript">
    function vote(id, name) {        
        xmlhttp=new XMLHttpRequest();
        //Una vez que el servidor envía la respuesta, realiza lo que está dentro de esta función
        xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        document.getElementById(id).innerHTML=xmlhttp.responseText;
        }
    }
    //Envía el pedido junto con las variables
    xmlhttp.open("GET","cambia.php?id=" + id + "&name=" + name,true);
    xmlhttp.send();
    }
</script>

<div id="1">
    <a id="1" onclick="vote(this.id, this.name)" name="up" href="javascript:void(0)">up [#]</a>
</div>