angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne, usSpinnerService, $rootScope) {
    $scope.choosenDate = {};
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });

    $scope.datas = {};
    $scope.ovifillrates = {};

	$scope.setHour = function(h){
    $scope.startSpin();
    if ($scope.choosenDate.Datum==null) {
      toastr.warning("Bitte ein Datum auswählen", "Warning!");
      $scope.stopSpin();
    }else if(!datumExists($scope.choosenDate.Datum)){
      toastr.error('Kein Report am '+$scope.choosenDate.Datum, "Error!");
      $scope.stopSpin();
    }else{
      var date = $scope.choosenDate.Datum;
  		$scope.setparams = {};
  		$scope.setparams.stunde = h;
  		$scope.setparams.datum = date;

      var h_reform = h;

    	if (h== "all") {
        //Ganzer Tag
  			$scope.setparams.stunde = null;
        $scope.choosenDate.Stunde = '';
  		}else{
        // Nach Stunde
        if (h_reform.length == 1) {
          h_reform = '0'+h_reform;
        }
        $scope.choosenDate.Stunde = ' um '+h_reform+':00';
      }

  		Kampagne.getKampagne($scope.setparams).then(function(response){
        $scope.stopSpin();
  			var response_data = response.data;
  			var percent = JSON.stringify(response.data);
  			for (var i = 0; i < response_data.length; i++) {
  				response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
  			};
  			$scope.kampagnes = response_data;
  		});

      //update both graphs
      $scope.setOverFilledImpressions(date,h);
      $scope.setOverallFillrate(date,h);
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
    if(datumExists($scope.choosenDate.Datum)){
      $scope.setHour('all');
      $scope.setOverFilledImpressions($scope.choosenDate.Datum,'all');
      $scope.setOverallFillrate($scope.choosenDate.Datum,'all');
      $scope.setDetailAverage($scope.choosenDate.Datum);
    }else{
      toastr.error('Kein Report am '+$scope.choosenDate.Datum, "Error!");
    }
    $scope.stopSpin();
  }

  function datumExists(Datum) {
    return $scope.dates.some(function(el) {
      return el.Datum === Datum;
    });
  }

  $scope.setOverFilledImpressions = function (datum,stunde){
    $scope.startSpin();
    var date = datum;
    $scope.setparams = {};
		$scope.setparams.stunde = stunde;
		$scope.setparams.datum = date;

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

    $('#donut-ofi').empty();
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

  $scope.setOverallFillrate = function (datum,stunde){
    $scope.startSpin();
    var date = datum;
    $scope.setparams = {};
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

});
