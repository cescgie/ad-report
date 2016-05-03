var myTemplate = '<div data-text="{{$scope.value}}"></div>';
var percentage = {};
var colors = ['#0b62a4', '#b388ff', '#4da74d','#ff6600','#03a9f4','#00bcd4','#1de9b6'];

angular.module('MyApp')
  .directive('circful', function() {
    return {
      scope:{
         value : '='
      },
      restrict: 'E',
      template: myTemplate,
      link : function(scope,element){
        percentage.animationStep = 5;
        percentage.foregroundColor = colors[Math.floor(Math.random() * 6) + 1];
        percentage.backgroundColor = '#eceaeb';
        percentage.fontColor = '#2A3440';
        percentage.foregroundBorderWidth = 35;
        percentage.backgroundBorderWidth = 35;
        percentage.pointSize = 100;
        percentage.percent = scope.value;
        element.circliful(percentage);
      }
    };
  });
