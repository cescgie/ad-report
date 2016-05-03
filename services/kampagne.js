angular.module('MyApp')
  .factory('Kampagne', function($http) {
    return {
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
      },
      getOverallFillrate:function(datum){
        return $http.get('api/kampagne/getOverallFillrate?datum='+datum);
      }
    };
  });
