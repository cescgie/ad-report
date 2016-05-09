angular.module('MyApp')
  .factory('Kampagne', function($http) {
    return {
      getDate: function(){
        return $http.get('api/kampagne/getDatum');
      },
      getFillrate:function(setParams){
        return $http.get('api/kampagne/getFillrate?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getDetailGraph:function(datum){
        return $http.get('api/kampagne/getDetailGraph?datum='+datum);
      },
      getOverallFilledImpression:function(setParams){
        return $http.get('api/kampagne/getOverallFilledImpression?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getOverallFillrate:function(setParams){
        return $http.get('api/kampagne/getOverallFillrate?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getDetailAverage:function(datum){
        return $http.get('api/kampagne/getDetailAverage?datum='+datum);
      },
      getFillrateRange: function(setParams){
        return $http.get('api/kampagne/getFillrate?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      },
      getOverallFilledImpressionRange:function(setParams){
        return $http.get('api/kampagne/getOverallFilledImpression?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      },
      getOverallFillrateRange:function(setParams){
        return $http.get('api/kampagne/getOverallFillrate?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      }
    };
  });
