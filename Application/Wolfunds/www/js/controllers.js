var access_token = 'c92585a8dec3a3d632afc2e834d3849b';
var $_http = null;
angular.module('starter.controllers', [])
.controller('AppCtrl', function($scope, $ionicModal, $timeout) {
  // Form data for the login modal
  $scope.loginData = {};

  // Create the login modal that we will use later
  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.modal.show();
  };

  // Perform the login action when the user submits the login form
  $scope.doLogin = function() {
    console.log('Doing login', $scope.loginData);

    // Simulate a login delay. Remove this and replace with your login
    // code if using a login system
    $timeout(function() {
      $scope.closeLogin();
    }, 1000);
  };
})

.controller('PlaylistsCtrl', function($scope) {
  $scope.playlists = [
    { title: 'Reggae', id: 1 },
    { title: 'Chill', id: 2 },
    { title: 'Dubstep', id: 3 },
    { title: 'Indie', id: 4 },
    { title: 'Rap', id: 5 },
    { title: 'Cowbell', id: 6 }
  ];
})

.controller('PlaylistCtrl', function($scope, $stateParams) {

})
.controller('HomelistCtrl', function($scope) {
        var params = { params: { access_token: access_token } };
        _api('http://api.aiai.im/image/lists', params, function(err,data){
            $scope.images = [];
            if(err){
                console.log(err);
            }else{
                $scope.images = data.result;
                console.log(data);
            }
        });
})
.controller('ImagelistCtrl', function($scope,$http) {
    var params = { params: { access_token: access_token } };
    _api($http,'http://api.aiai.im/image/lists', params, function(err,data){
        $scope.images = [];
        if(err){
            console.log(err);
        }else{
            $scope.images = [];
            $scope.navs = [];
            for(item in data.result){
                if(item < 5){
                    $scope.navs.push(data.result[item]);
                }else{
                    $scope.images.push(data.result[item]);
                }
            }
            console.log($scope.navs);
            console.log($scope.images);
        }
    });
});

function _api($http, url, query, callback){
    $http.get(url, query)
        .success(function (data) {
            if(data.status == "OK"){
                callback(null, data);
            }else{
                callback(data.error);
            }
        })
        .error(function (e) {
            callback(e);
        });
}