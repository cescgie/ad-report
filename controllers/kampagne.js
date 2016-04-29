angular.module('MyApp')
  .controller('KampagneCtrl', function($scope, $location, toastr, Kampagne) {
    Kampagne.getDate().then(function(response){
      $scope.dates = response.data;
    });
    $scope.choosenDate = {"Datum":"2016-04-20"};
  });
