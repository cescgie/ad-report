angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne, usSpinnerService, $rootScope) {
    $scope.choosenDate = {};
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });

    $scope.datas = {};

    $scope.ovifillrates = {};

    $scope.data = {
      availableOptions: [
        {id: '1', name: 'Specific date'},
        {id: '2', name: 'Range dates'},
      ],
      selectedOption: {id: '1', name: 'Specific date'} //This sets the default value of the select in the ui
    };

    $scope.datax = {
      availableStunde: [
        {id: 'all', name: 'Ganzer Tag'},
        {id: '0', name: '00:00'},
        {id: '1', name: '01:00'},
        {id: '2', name: '02:00'},
        {id: '3', name: '03:00'},
        {id: '4', name: '04:00'},
        {id: '5', name: '05:00'},
        {id: '6', name: '06:00'},
        {id: '7', name: '07:00'},
        {id: '8', name: '08:00'},
        {id: '9', name: '09:00'},
        {id: '10', name: '10:00'},
        {id: '11', name: '11:00'},
        {id: '12', name: '12:00'},
        {id: '13', name: '13:00'},
        {id: '14', name: '14:00'},
        {id: '15', name: '15:00'},
        {id: '16', name: '16:00'},
        {id: '17', name: '17:00'},
        {id: '18', name: '18:00'},
        {id: '19', name: '19:00'},
        {id: '20', name: '20:00'},
        {id: '21', name: '21:00'},
        {id: '22', name: '22:00'},
        {id: '23', name: '23:00'}
      ],
      selectedStunde: {id: 'all', name: 'Ganzer Tag'} //This sets the default value of the select in the ui
    };

  	$scope.setFillrate = function(datum1,stunde,datum2){
      $scope.startSpin();
      var date = datum1;
    	$scope.setparams = {};

      if (datum2!=null && stunde==null) {
        //2 dates range
        $scope.setparams.datum1 = datum1;
        $scope.setparams.datum2 = datum2;
        $scope.setFillRateDate = {"Datum":datum1+' bis '+datum2};
        Kampagne.getFillrateRange($scope.setparams).then(function(response){
          $scope.stopSpin();
          var response_data = response.data;
          var percent = JSON.stringify(response.data);
          for (var i = 0; i < response_data.length; i++) {
            response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
          };
          $scope.kampagnes = response_data;
        });

      }else{
        $scope.setparams.stunde = stunde;
        $scope.setparams.datum = date;
        $scope.setFillRateDate = {"Datum":date};
        var h_reform = stunde;

        if (stunde== "all") {
          //Ganzer Tag
          $scope.setparams.stunde = null;
          $scope.setFillRateDate.Stunde = '';
        }else{
          // Nach Stunde
          if (h_reform.length == 1) {
            h_reform = '0'+h_reform;
          }
          $scope.setFillRateDate.Stunde = ' um '+h_reform+':00';
        }

        Kampagne.getFillrate($scope.setparams).then(function(response){
          $scope.stopSpin();
          var response_data = response.data;
          var percent = JSON.stringify(response.data);
          for (var i = 0; i < response_data.length; i++) {
            response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
          };
          $scope.kampagnes = response_data;
        });
      }
  	};

    $scope.setOverFilledImpressions = function (datum1,stunde,datum2){
      $scope.startSpin();
      var date = datum1;
      $scope.setparams = {};
      $('#donut-ofi').empty();

      if (datum2!=null && stunde==null) {
        //2 dates range
        $scope.setparams.datum1 = datum1;
        $scope.setparams.datum2 = datum2;
        $scope.setOverFilledImpressionsDate = {"Datum":datum1+' bis '+datum2};
        Kampagne.getOverallFilledImpressionRange($scope.setparams).then(function(response){
          $scope.stopSpin();
          $('#donut-ofi').empty();
          Morris.Donut({
            element: 'donut-ofi',
            data: response.data,
            formatter: function (x, data) { return data.formatted; }
          });
        });
      }else{
        $scope.setparams.stunde = stunde;
        $scope.setparams.datum = datum1;
        var h_reform = stunde;
        $scope.setOverFilledImpressionsDate = {"Datum":date};
        if (stunde== "all") {
          //Ganzer Tag
          $scope.setparams.stunde = null;
          $scope.setOverFilledImpressionsDate.Stunde = '';
        }else{
          // Nach Stunde
          if (h_reform.length == 1) {
            h_reform = '0'+h_reform;
          }
          $scope.setOverFilledImpressionsDate.Stunde = ' um '+h_reform+':00';
        }
        Kampagne.getOverallFilledImpression($scope.setparams).then(function(response){
          $scope.stopSpin();
          $('#donut-ofi').empty();
          Morris.Donut({
            element: 'donut-ofi',
            data: response.data,
            formatter: function (x, data) { return data.formatted; }
          });
        });
      }
    };

    $scope.setOverallFillrate = function (datum1,stunde,datum2){
      $scope.startSpin();
      var date = datum1;
      $scope.setparams = {};
      $('#donut-ofi').empty();

      if (datum2!=null && stunde==null) {
        //2 dates range
        $scope.setparams.datum1 = datum1;
        $scope.setparams.datum2 = datum2;
        $scope.setOverallFillrateDate = {"Datum":datum1+' bis '+datum2};

        Kampagne.getOverallFillrateRange($scope.setparams).then(function(response){
          $scope.stopSpin();
          var response_data = response.data;
          var percent = JSON.stringify(response.data);
          for (var i = 0; i < response_data.length; i++) {
            response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
          };
          $scope.ovifillrates = response_data;
        });
      }else{
        $scope.setparams.stunde = stunde;
    		$scope.setparams.datum = date;

        var h_reform = stunde;
        $scope.setOverallFillrateDate = {"Datum":date};
        if (stunde== "all") {
          //Ganzer Tag
    			$scope.setparams.stunde = null;
          $scope.setOverallFillrateDate.Stunde = '';
        }else{
          // Nach Stunde
          if (h_reform.length == 1) {
            h_reform = '0'+h_reform;
          }
          $scope.setOverallFillrateDate.Stunde = ' um '+h_reform+':00';
        }

        Kampagne.getOverallFillrate($scope.setparams).then(function(response){
          $scope.stopSpin();
          var response_data = response.data;
          var percent = JSON.stringify(response.data);
          for (var i = 0; i < response_data.length; i++) {
            response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
          };
          $scope.ovifillrates = response_data;
        });
      }
    };

  	$scope.showDetailGraph = function(alpha){
      $scope.startSpin();

      if ($scope.choosenRangeDate.Datum1!=null && $scope.choosenRangeDate.Datum2!=null) {
        $('#line-example').hide();
        var datum1 = $scope.choosenRangeDate.Datum1;
        var datum2 = $scope.choosenRangeDate.Datum2;

        $scope.setparams = {};
        $scope.setparams.datum1 = datum1;
        $scope.setparams.datum2 = datum2;
        $scope.setCompareDate = {"Datum":datum1+' bis '+datum2};

        if (alpha.length>0) {
          Kampagne.getDetailGraphRange($scope.setparams).then(function(response){
            $('#area-kampagne').empty();
            $scope.stopSpin();
      			Morris.Line({
      	          element: 'area-kampagne',
      	          data: response.data,
      	          xkey: 'x_achse_label',
      	          ykeys: alpha,
      	          labels: alpha,
      	          parseTime: false
              	});
      		});
        }
      }else{
        var date = $scope.choosenDate.Datum;
        $scope.setCompareDate = {"Datum":date};
        $('#line-example').hide();
        if (alpha.length>0) {
          Kampagne.getDetailGraph(date).then(function(response){
            $('#area-kampagne').empty();
            $scope.stopSpin();
      			Morris.Line({
      	          element: 'area-kampagne',
      	          data: response.data,
      	          xkey: 'x_achse_label',
      	          ykeys: alpha,
      	          labels: alpha,
      	          parseTime: false
              	});
      		});
        }
      }
  	};

    $scope.checkedKampagne = [];

    $scope.toggleCheck = function (kampagne) {
      if ($scope.checkedKampagne.indexOf(kampagne) === -1) {
          $scope.checkedKampagne.push(kampagne);
      } else {
          $scope.checkedKampagne.splice($scope.checkedKampagne.indexOf(kampagne), 1);
      }

      if ($scope.checkedKampagne.length>null) {
        $scope.showDetailGraph($scope.checkedKampagne);
      }else{
        $('#area-kampagne').empty();
        $('#line-example').show();
        $scope.stopSpin();
      }
    };

    $scope.setDetailAverage = function(datum1,datum2){
      $scope.startSpin();
  		var date = datum1;
      $scope.setparams ={};
      $('#line-example-average').hide();

      if (datum2!=null) {
        //2 dates range
        $scope.setparams.datum1 = datum1;
        $scope.setparams.datum2 = datum2;
        $scope.setDetailAverageDate = {"Datum":datum1+' bis '+datum2};

        Kampagne.getDetailAverageRange($scope.setparams).then(function(response){
          $('#area-kampagne-average').empty();
            $scope.stopSpin();
      			Morris.Line({
      	          element: 'area-kampagne-average',
      	          data: response.data,
      	          xkey: 'x_achse_label',
      	          ykeys: ['Average'],
      	          labels: ['Durchschnitt'],
      	          parseTime: false
              	});
    		});
      }else{
        $scope.setDetailAverageDate = {"Datum":date};
        $scope.setparams.datum = date;

        Kampagne.getDetailAverage($scope.setparams).then(function(response){
          $('#area-kampagne-average').empty();
            $scope.stopSpin();
      			Morris.Line({
      	          element: 'area-kampagne-average',
      	          data: response.data,
      	          xkey: 'x_achse_label',
      	          ykeys: ['Average'],
      	          labels: ['Durchschnitt'],
      	          parseTime: false
              	});
    		});
      }
    }

    /**
    * Menu Controllers
    * 1. changeSelectedDate for Specific date
    * 2. changeSelectedRange for Range dates
    */
    $scope.changeSelectedDate = function(){
      $scope.startSpin();
      toastr.clear();

      var stunde = $scope.datax.selectedStunde.id;
      if ($scope.choosenDate.Datum==null || $scope.choosenDate.Datum == '') {
        toastr.warning("Bitte ein Datum auswählen", "Warning!");
        $scope.stopSpin();
      }else if(datumExists($scope.choosenDate.Datum)){
        $scope.setFillrate($scope.choosenDate.Datum,stunde,null);
        $scope.setOverFilledImpressions($scope.choosenDate.Datum,stunde,null);
        $scope.setOverallFillrate($scope.choosenDate.Datum,stunde,null);
        $scope.setDetailAverage($scope.choosenDate.Datum,null);
        //clear choosenRangeDate
        $scope.choosenRangeDate.Datum1 = null;
        $scope.choosenRangeDate.Datum2 = null;
        //clear current graph and change to default graph
        $('#area-kampagne').empty();
        $('#line-example').show();
        //clear check box
        $scope.checkedKampagne = [];
        //clear detail date
        $scope.setCompareDate = [];
      }else{
        toastr.error('Kein Report am '+$scope.choosenDate.Datum, "Error!");
        $scope.stopSpin();
      }
    }

    function datumExists(Datum) {
      return $scope.dates.some(function(el) {
        return el.Datum === Datum;
      });
    }

    $scope.choosenRangeDate = {};
    $scope.changeSelectedRange = function(){
      $scope.startSpin();
      toastr.clear();

      if ($scope.choosenRangeDate.Datum1==null || $scope.choosenRangeDate.Datum1== '') {
        toastr.warning("Bitte range 1. Datum auswählen", "Warning!");
        $scope.stopSpin();
      }else  if ($scope.choosenRangeDate.Datum2==null || $scope.choosenRangeDate.Datum2== '') {
        toastr.warning("Bitte range 2. Datum auswählen", "Warning!");
        $scope.stopSpin();
      }else{
        var startDate = new Date($scope.choosenRangeDate.Datum1);
        var endDate = new Date($scope.choosenRangeDate.Datum2);
        if (startDate > endDate){
          toastr.warning("Bitte 2.Datum richtig auswählen", "Warning!");
          $scope.stopSpin();
        }else{
          //clear current graph and change to default graph
          $('#area-kampagne').empty();
          $('#line-example').show();
          //clear check box
          $scope.checkedKampagne = [];
          //clear detail date
          $scope.setCompareDate = [];

          $scope.setparams = {};
      		$scope.setparams.datum1 = $scope.choosenRangeDate.Datum1;
      		$scope.setparams.datum2 = $scope.choosenRangeDate.Datum2;

          //Fillrate
          $scope.setFillrate($scope.setparams.datum1,null,$scope.setparams.datum2);
          //Overall Filled Impressions
          $scope.setOverFilledImpressions($scope.setparams.datum1,null,$scope.setparams.datum2);
          //Overall Fillrate
          $scope.setOverallFillrate($scope.setparams.datum1,null,$scope.setparams.datum2);
          //Durchschnitt
          $scope.setDetailAverage($scope.setparams.datum1,$scope.setparams.datum2);
        }
      }
    };

    /**
    * Spinning Loader
    */
    $scope.startSpin = function() {
      if (!$scope.spinneractive) {
        usSpinnerService.spin('spinner-1');
      }
    };

    $scope.stopSpin = function() {
      if ($scope.spinneractive) {
        usSpinnerService.stop('spinner-1');
      }
    };

    $scope.spinneractive = false;

    $rootScope.$on('us-spinner:spin', function(event, key) {
      $scope.spinneractive = true;
    });

    $rootScope.$on('us-spinner:stop', function(event, key) {
      $scope.spinneractive = false;
    });

});
