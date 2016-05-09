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
        {id: '1', name: '00:01'},
        {id: '2', name: '00:02'},
        {id: '3', name: '00:03'},
        {id: '4', name: '00:04'},
        {id: '5', name: '00:05'},
        {id: '6', name: '00:06'},
        {id: '7', name: '00:07'},
        {id: '8', name: '00:08'},
        {id: '9', name: '00:09'},
        {id: '10', name: '00:10'},
        {id: '11', name: '00:11'},
        {id: '12', name: '00:12'},
        {id: '13', name: '00:13'},
        {id: '14', name: '00:14'},
        {id: '15', name: '00:15'},
        {id: '16', name: '00:16'},
        {id: '17', name: '00:17'},
        {id: '18', name: '00:18'},
        {id: '19', name: '00:19'},
        {id: '20', name: '00:20'},
        {id: '21', name: '00:21'},
        {id: '22', name: '00:22'},
        {id: '23', name: '00:23'}
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

	$scope.showDetailGraph = function(alpha){
    $scope.startSpin();
		var date = $scope.choosenDate.Datum;
    $scope.datas.datum = date;
    $('#line-example').hide();
    if (alpha.length>0) {
      Kampagne.getDetailGraph(date).then(function(response){
        $('#area-kampagne').empty();
        $scope.stopSpin();
  			Morris.Line({
  	          element: 'area-kampagne',
  	          data: response.data,
  	          xkey: 'hour',
  	          ykeys: alpha,
  	          labels: alpha,
  	          parseTime: false
          	});
  		});
    }
	};

  $scope.checkedKampagne = [];
  $scope.toggleCheck = function (kampagne) {
    if ($scope.checkedKampagne.indexOf(kampagne) === -1) {
        $scope.checkedKampagne.push(kampagne);
    } else {
        $scope.checkedKampagne.splice($scope.checkedKampagne.indexOf(kampagne), 1);
    }

    $scope.showDetailGraph($scope.checkedKampagne);
  };

  $scope.changeSelectedDate = function(){
    $scope.startSpin();
    toastr.clear();
    var stunde = $scope.datax.selectedStunde.id;
    if ($scope.choosenDate.Datum==null) {
      toastr.warning("Bitte ein Datum auswählen", "Warning!");
      $scope.stopSpin();
    }else if(datumExists($scope.choosenDate.Datum)){
      $scope.setFillrate($scope.choosenDate.Datum,stunde,null);
      $scope.setOverFilledImpressions($scope.choosenDate.Datum,stunde,null);
      $scope.setOverallFillrate($scope.choosenDate.Datum,stunde,null);
      $scope.setDetailAverage($scope.choosenDate.Datum);
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
  }

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
  }

  $scope.setDetailAverage = function(datum){
    $scope.startSpin();
		var date = $scope.choosenDate.Datum;
    $scope.datas.datum = date;
    $('#line-example-average').hide();
    Kampagne.getDetailAverage(date).then(function(response){
      $('#area-kampagne-average').empty();
        $scope.stopSpin();
  			Morris.Line({
  	          element: 'area-kampagne-average',
  	          data: response.data,
  	          xkey: 'hour',
  	          ykeys: ['Average'],
  	          labels: ['Durchschnitt'],
  	          parseTime: false
          	});
		});
  }

  //spinner loader
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

  $scope.choosenRangeDate = {};
  $scope.changeSelectedRange = function(){
    $scope.startSpin();
    toastr.clear();

    if ($scope.choosenRangeDate.Datum1==null || $scope.choosenRangeDate.Datum2==null) {
      toastr.warning("Bitte range Datum auswählen", "Warning!");
      $scope.stopSpin();
    }else{
      var startDate = new Date($scope.choosenRangeDate.Datum1);
      var endDate = new Date($scope.choosenRangeDate.Datum2);
      if (startDate > endDate){
        toastr.warning("Bitte 2.Datum richtig auswählen", "Warning!");
        $scope.stopSpin();
      }else{
        $scope.setparams = {};
    		$scope.setparams.datum1 = $scope.choosenRangeDate.Datum1;
    		$scope.setparams.datum2 = $scope.choosenRangeDate.Datum2;

        //Fillrate
        $scope.setFillrate($scope.setparams.datum1,null,$scope.setparams.datum2);
        //Overall Filled Impressions
        $scope.setOverFilledImpressions($scope.setparams.datum1,null,$scope.setparams.datum2);
        //Overall Fillrate
        $scope.setOverallFillrate($scope.setparams.datum1,null,$scope.setparams.datum2);
      }
    }
  };

});
