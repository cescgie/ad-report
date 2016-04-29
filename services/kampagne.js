angular.module('MyApp')
  .factory('Kampagne', function($http) {
    return {
      all: function() {
        return $http.get('api/kampagne/all');
      },
      updateKampagne: function(updateData) {
        return $http.put('api/update', updateData);
      },
      getDate: function(){
        return $http.get('api/kampagne/getDatum');
      }
    };
  });
