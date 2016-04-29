<div id="head">
  <h2>CS Fillrate Overview</h2>
</div>
<hr>
<div id="wrapper">
  <div id="spalte1">
    <table>
      <tr>
        <td>Datum:</td>
        <!-- <td>
          <form>
            <select>
              <option>Today</option>
              <option selected>Yesterday</option>
              <option>This Week</option>
              <option>Last 7 Days</option>
              <option>Last Week</option>
              <option>This Month</option>
              <option>Last Month</option>
            </select>
          </form>
        </td> -->
        <td>
          <select name="selectdatum" id="select_datum">
           <?php if(Session::get('Datum')):?>
             <option value=""><?=Session::get('Datum')?></option>
           <?php endif;?>
           <!-- <option value="">Alle</option> -->
           <?php foreach ($data['Datum'] as $row):?>
             <?php if(Session::get('Datum')!=$row['Datum']):?>
               <option value="<?=$row['Datum']?>"><?=$row['Datum']?></option>
             <?php endif;?>
           <?php endforeach;?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Stunde:</td>
        <!-- <td><form>
            <select>
              <option>00:00</option>
              <option>01:00</option>
              <option>02:00</option>
              <option>03:00</option>
              <option>04:00</option>
              <option>05:00</option>
              <option>06:00</option>
              <option>07:00</option>
              <option>08:00</option>
              <option>09:00</option>
              <option>10:00</option>
              <option>11:00</option>
              <option>12:00</option>
              <option>13:00</option>
              <option>14:00</option>
              <option selected>15:00</option>
              <option>16:00</option>
              <option>17:00</option>
              <option>18:00</option>
              <option>19:00</option>
              <option>20:00</option>
              <option>21:00</option>
              <option>22:00</option>
              <option>23:00</option>
            </select>
          </form>
        </td> -->
        <td>
          <select name="select_stunde" id="select_stunde">
           <?php if(Session::get('Stunde')):?>
             <option value=""><?= (strlen(str_replace("'","",Session::get('Stunde')))==1)? '0'.str_replace("'","",Session::get('Stunde')) : str_replace("'","",Session::get('Stunde'))?>:00</option>
           <?php endif;?>
           <option value="all">Ganzer Tag</option>
           <?php foreach ($data['Stunde'] as $row):?>
             <?php if(str_replace("'","",Session::get('Stunde'))!=$row['Stunde']):?>
               <option value="'<?=$row['Stunde']?>'"><?= (strlen($row['Stunde'])==1) ? '0'.$row['Stunde'] : $row['Stunde'] ;?>:00</option>
             <?php endif;?>
           <?php endforeach;?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Ganzer Tag:</td>
        <td><input type = "checkbox" id = "checkbox_tag" onchange = "change_tag(this)" value = "foo"></td>
      </tr>
    </table>
    <h4>FILLRATE</h4>
    <table>
      <tr class="graphics">
        <?php foreach ($data["datas"] as $key => $value):?>
          <td><div class="chartboxsmall">
              <h5 style="text-align:center;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;max-width: 125px;width:100%"><?=$value['Kampagne']?></h5>
              <div id="circle<?=$key?>"> </div>
              <hr>
              <h5><?=$value['Impressions']?> | <?=$value['AdCounts']?></h5>
            </div></td>
            <script>
              $(document).ready(function(){
                var colors = ['#0b62a4', '#7A92A3', '#4da74d'];
                var random_color = colors["<?=$key?>"];
                var prozent = "<?= $value['AdCounts']/$value['Impressions'];?>";
                // console.log(prozent);
                $("#circle<?=$key?>").circliful({
                  animationStep:5,foregroundColor:random_color,backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,percent:Math.round(prozent*100)
                });
              });
            </script>
          <td>
        <?php endforeach;?>
        <td><div class="chartboxbig">
            <h5>OVERALL FILLED IMPRESSION</h5>
            <!-- <div class="circleoverall" id="circleoverall1"></div> -->
            <div id="donut-ofi"></div>
            <hr>
            <h5>5.123.234 | 650.234</h5>
          </div></td>
        <td><div class="chartboxbig">
            <h5>OVERALL FILLRATE</h5>
            <div class="circleoverall" id="circleoverall2"></div>
            <hr>
            <h5>5.123.234 | 650.234</h5>
          </div></td>
      </tr>
    </table>
    <table>
      <tr> </tr>
    </table>
  </div>

  <div id="spalte2">
    <hr>
    <h4>DETAIL</h4>


     <table>
      <tr>
      <td>
        <div class="chartboxbig linecharts">
          <h5>Compare <?=Session::get('Datum')?></h5>
          <?=Session::get('Kampagne')?>
          <div id="area-kampagne"></div>
          <div class="" id="line1"></div>
          <hr>
          <!-- <h5>5.123.234 | 650.234</h5> -->
          <p><a href="<?=DIR?>kampagne/remove_Kampagne">Alle Kampagne vergleichen</a></p>
          <?php foreach ($data["datas"] as $key => $value):?>
            <p><input type="checkbox" id="checkbox-compare"><a href="<?=DIR?>kampagne/set_Kampagne/<?=$value['Kampagne']?>"><?=$value['Kampagne']?></a></p>
          <?php endforeach;?>
        </div>
      </td>
    <td>
    <div class="chartboxbig linecharts">
      <h5>Durchschnitt</h5>
      <div class="" id="line1"></div>
      <hr>
      <h5>5.123.234 | 650.234</h5>
    </div>
    </td>
      </tr>
    </table>
  </div>


</div>
</div>


<!-- <script>$(document).ready(function(){$("#circle2").circliful({animationStep:5,foregroundColor:'#ff6600',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,percent:Math.floor((Math.random()*100)+1),});});</script> -->
<!-- <script>$(document).ready(function(){$("#circle3").circliful({animationStep:5,foregroundColor:'#96cd00',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,percent:Math.floor((Math.random()*100)+1),});});</script> -->
<!-- <script>$(document).ready(function(){$("#circle4").circliful({animationStep:5,foregroundColor:'#9744de',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,percent:Math.floor((Math.random()*100)+1),});});</script> -->
<script>$(document).ready(function(){$("#circleoverall1").circliful({animationStep:5,foregroundColor:'#0014d7',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,pointSize:100,percent:Math.floor((Math.random()*100)+1),});});</script>
<script>$(document).ready(function(){$("#circleoverall2").circliful({animationStep:5,foregroundColor:'#d70000',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,pointSize:100,percent:Math.floor((Math.random()*100)+1),});});</script>


<script type="text/javascript">
$('#select_datum').on('change', function() {
  // console.log("selectdatum : "+$(this).val());
  var selected = $(this).val();
  if(selected=='all'){
    window.location = "<?= DIR ?>kampagne/remove_date";
  }else{
    window.location = "<?= DIR ?>kampagne/set_Datum?datum="+selected;
  }
});
$('#select_stunde').on('change', function() {
  // console.log("selectstunde : "+$(this).val());
  var selected = $(this).val();
  if(selected=='all'){
    window.location = "<?= DIR ?>kampagne/remove_stunde";
  }else{
    window.location = "<?= DIR ?>kampagne/set_Stunde?stunde="+selected;
  }
});
</script>

<script type="text/javascript" src="<?= URL::SCRIPTS('raphael.min') ?>"></script>
<script type="text/javascript" src="<?= URL::SCRIPTS('morris') ?>"></script>

<script type="text/javascript">
$.ajax({    //create an ajax request to load_page.php
    type: "GET",
    url: "<?=DIR?>kampagne/getGraph",
    async: false,
    dataType: "json",   //expect html to be returned
    success: function(response){
        Morris.Area({
          element: 'area-kampagne',
          data: response,
          xkey: 'y',
          ykeys: ['a', 'b', 'c'],
          labels: ['Kampagne 1', 'Kampagne 2', 'Kampagne 3']
        });
        //console.log("success");
        console.log(response);
    }
});
</script>

<script type="text/javascript">
function change_tag(id){
  var selected = id.checked;
  if(selected==true) {
    console.log(selected);
  }else{
    console.log(selected);
  }
}

</script>

<script type="text/javascript">
$.ajax({    //create an ajax request to load_page.php
    type: "GET",
    url: "<?=DIR?>kampagne/getOverllFilledImpression",
    async: false,
    dataType: "json",   //expect html to be returned
    success: function(response){
      Morris.Donut({
      element: 'donut-ofi',
      data: response
      });
    }
  })
</script>
