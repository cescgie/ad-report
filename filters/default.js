angular.module('MyApp')
.filter('default', [function(){
  return function(value, def) {
    return value || def;
  };
}]);
