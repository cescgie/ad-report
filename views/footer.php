</div> <!-- / .container -->


  <script type="text/javascript">
  $('#select_datum').on('change', function() {
    console.log("selectdatum : "+$(this).val());
    var selected = $(this).val();
    if(selected=='all'){
      window.location = "<?= DIR ?>kampagne/remove_date";
    }else{
      window.location = "<?= DIR ?>kampagne/set_Datum?datum="+selected;
    }
  });
  $('#select_stunde').on('change', function() {
    console.log("selectstunde : "+$(this).val());
    var selected = $(this).val();
    if(selected=='all'){
      window.location = "<?= DIR ?>kampagne/remove_stunde";
    }else{
      window.location = "<?= DIR ?>kampagne/set_Stunde?stunde="+selected;
    }
  });
  </script>
</body>
</html>
