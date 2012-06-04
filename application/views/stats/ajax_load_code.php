<pre class="<?php echo $type;?>">
<?php
echo htmlentities($file_data);
?>
</pre>
<script language="javascript">
$(document).ready(function () {
    $("pre.html").snippet("html",{style:"the"});
    $("pre.php").snippet("php",{style:"the"});
    $("pre.css").snippet("css",{style:"the"});
    $("pre.js").snippet("javascript",{style:"the"});
});
</script>