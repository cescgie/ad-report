angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne) {
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });
    $scope.choosenDate = {"Datum":"2016-04-20"};

    $scope.datas = {};

	$scope.setHour = function(h){
		var date = $scope.choosenDate.Datum;

		$scope.setparams = {};
		$scope.setparams.stunde = h;
		$scope.setparams.datum = date;

		if (h== "all") {
			$scope.setparams.stunde = null;
		    console.log("Ganzer Tag");
		}
		Kampagne.getKampagne($scope.setparams).then(function(response){
			var response_data = response.data;
			var percent = JSON.stringify(response.data);
			for (var i = 0; i < response_data.length; i++) {
				var index = i+1;
				response_data[i].circle = index;
				response_data[i].percent = Math.round((response_data[i].AdCounts/response_data[i].Impressions)*100);
				$scope.percentage = {};
			   	$scope.percentage.percent = response_data[i].percent;
			};

			$scope.kampagnes = response_data;
		});

	};

	$scope.showDetailGraph = function(){
		var date = $scope.choosenDate.Datum;
		$scope.datas.datum = date;
		$('#area-kampagne').empty();
		Kampagne.getDetailGraph(date).then(function(response){
			$scope.detailkampagnes = response.data;
			Morris.Line({
	          element: 'area-kampagne',
	          data: response.data,
	          xkey: 'hour',
	          ykeys: ['a', 'b', 'c'],
	          labels: ['Kampagne 1', 'Kampagne 2', 'Kampagne 3'],
	          parseTime: false
        	});
		});
	};

  });
