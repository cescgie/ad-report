angular.module('MyApp')
  .factory('Kampagne', function($http) {
    return {
      getDate: function(){
        return $http.get('api/kampagne/getDatum');
      },
      getFillrate:function(setParams){
        return $http.get('api/kampagne/getFillrate?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getFillrateRange: function(setParams){
        return $http.get('api/kampagne/getFillrate?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      },
      getOverallFilledImpression:function(setParams){
        return $http.get('api/kampagne/getOverallFilledImpression?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getOverallFilledImpressionRange:function(setParams){
        return $http.get('api/kampagne/getOverallFilledImpression?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      },
      getOverallFillrate:function(setParams){
        return $http.get('api/kampagne/getOverallFillrate?datum='+setParams.datum+'&stunde='+setParams.stunde);
      },
      getOverallFillrateRange:function(setParams){
        return $http.get('api/kampagne/getOverallFillrate?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      },
      getDetailGraph:function(datum){
        return $http.get('api/kampagne/getDetailGraph?datum='+datum);
      },
      getDetailAverage:function(setParams){
        return $http.get('api/kampagne/getDetailAverage?datum='+setParams.datum);
      },
      getDetailAverageRange:function(setParams){
        return $http.get('api/kampagne/getDetailAverage?datum1='+setParams.datum1+'&datum2='+setParams.datum2);
      }
    };
  });
