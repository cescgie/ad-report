angular.module('MyApp', ['ngResource', 'ngMessages', 'ngAnimate', 'toastr', 'ui.router'])
  .config(function($stateProvider, $urlRouterProvider,$anchorScrollProvider) {

    $anchorScrollProvider.disableAutoScrolling();

    $stateProvider
      .state('kampagne', {
        url: '/',
        controller: 'KampagneCtrl',
        templateUrl: 'partials/kampagne.html'
      });

      $urlRouterProvider.otherwise('/');
  });
