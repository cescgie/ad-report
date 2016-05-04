angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne) {
    $scope.choosenDate = {};
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });

    $scope.datas = {};
    $scope.ovifillrates = {};

	$scope.setHour = function(h){
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
	};

	$scope.showDetailGraph = function(alpha){
		var date = $scope.choosenDate.Datum;
    $scope.datas.datum = date;
    $('#area-kampagne').empty();
    $('#line-example').hide();
    if (alpha.length>0) {
      Kampagne.getDetailGraph(date).then(function(response){
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
    $scope.setHour('all');
    $scope.setOverFilledImpressions($scope.choosenDate.Datum,'all');
    $scope.setOverallFillrate($scope.choosenDate.Datum,'all');
  }

  $scope.setOverFilledImpressions = function (datum,stunde){
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
      $('#donut-ofi').empty();
      Morris.Donut({
        element: 'donut-ofi',
        data: response.data,
        formatter: function (x, data) { return data.formatted; }
      });
    });
  }

  $scope.setOverallFillrate = function (datum,stunde){
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
      var response_data = response.data;
      var percent = JSON.stringify(response.data);
      for (var i = 0; i < response_data.length; i++) {
        response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
      };
      $scope.ovifillrates = response_data;
    });
  }
});
