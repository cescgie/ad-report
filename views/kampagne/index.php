<div id="head">
  <h2>CS Fillrate Overview</h2>
</div>
<hr>
<div id="wrapper">
  <div id="spalte1">
    <table>
      <tr>
        <td>Datum:</td>
        <td>
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
        </td>
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
        <td><form>
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
        </td>
        <td>
          <select name="select_stunde" id="select_stunde">
           <?php if(Session::get('Stunde')):?>
             <option value=""><?= (strlen(Session::get('Stunde'))==1) ? '0'.Session::get('Stunde') : Session::get('Stunde') ;?>:00</option>
           <?php endif;?>
           <!-- <option value="">Ganzer Tag</option> -->
           <?php foreach ($data['Stunde'] as $row):?>
             <?php if(Session::get('Stunde')!=$row['Stunde']):?>
               <option value="'<?=$row['Stunde']?>'"><?= (strlen($row['Stunde'])==1) ? '0'.$row['Stunde'] : $row['Stunde'] ;?>:00</option>
             <?php endif;?>
           <?php endforeach;?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Ganzer Tag:</td>
        <td><input type="checkbox"></td>
      </tr>
    </table>
    <h4>FILLRATE</h4>
    <table>
      <tr class="graphics">
        <?php foreach ($data["datas"] as $key => $value):?>
          <td><div class="chartboxsmall">
              <h5 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;max-width: 200px;"><?=$value['Kampagne']?></h5>
              <div id="circle<?=$key?>"> </div>
              <hr>
              <h5><?=$value['Impressions']?> | <?=$value['AdCounts']?></h5>
            </div></td>
            <script>
              $(document).ready(function(){
                var prozent = "<?=($value['AdCounts']/$value['Impressions'])?>";
                console.log(prozent);
                $("#circle<?=$key?>").circliful({
                  animationStep:5,foregroundColor:'#19c6f5',backgroundColor:'#eceaeb',fontColor:'#2A3440',foregroundBorderWidth:35,backgroundBorderWidth:35,percent:prozent*100
                });
              });
            </script>
          <td>
        <?php endforeach;?>
        <td><div class="chartboxbig">
            <h5>OVERALL FILLED IMPRESSION</h5>
            <div class="circleoverall" id="circleoverall1"></div>
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
      <td><div class="chartboxbig linecharts">
      <h5>Compare 27.06.2016</h5>
      <div class="" id="line1"></div>
      <hr>
      <h5>5.123.234 | 650.234</h5>
    </div></td>
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
