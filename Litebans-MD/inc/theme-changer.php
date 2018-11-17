<form method="post" style="display:inline-block;">
<select class="form-control themechanger" name='theme'style="margin-top:0rem; height:50px; margin-bottom:0rem; font-size:13px; border:none;" onchange='this.form.submit();'>
<?php
$themes = Array('wilikath','dreswen','cerrav','america','abyn'); 
if(!isset($_SESSION['theme'])){
?>
<option>Wilikath</option>
<option>Dreswen</option>
<option>Cerrav</option>
<option>America</option>
<option>Abyn</option>
  <?php
}else{
   foreach($themes AS $themey){
      if(strtolower($themey)==$_SESSION['theme']){
         echo "<option selected>" . $themey . "</option>";
      }else{
         echo '<option>'.$themey."</option>";
      }
}
}
?>
</select>
<noscript><input type="submit" value="Change"></noscript>
</form>