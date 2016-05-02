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
      },
      getKampagne:function(setParams){
        return $http.get('api/kampagne/getFillrate?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getDetailGraph:function(datum){
        return $http.get('api/kampagne/getDetailGraph?datum='+datum);
      },
      getOFI:function(datum){
        return $http.get('api/kampagne/getOverallFilledImpression?datum='+datum);
      }
    };
  });
