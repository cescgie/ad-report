angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne) {
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });

    $scope.datas = {};

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
				var index = i+1;
				response_data[i].circle = index;
				response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
			};
			$scope.kampagnes = response_data;
		});

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

  function init(){
    $scope.choosenDate = {"Datum":"2016-04-20"};
    $scope.choosenDate.Stunde = 'all';
    $scope.setHour($scope.choosenDate.Stunde);
    $scope.setOverFilledImpressions($scope.choosenDate.Datum);
  }

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
    $scope.setOverFilledImpressions($scope.choosenDate.Datum);
  }

  $scope.setOverFilledImpressions = function (date){
    $('#donut-ofi').empty();
    Kampagne.getOFI(date).then(function(response){
      $('#donut-ofi').empty();
      Morris.Donut({
        element: 'donut-ofi',
        data: response.data
      });
    });
  }

  init();

});
