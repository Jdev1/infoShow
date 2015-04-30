

//The $route provider directive handles the client side navigation
// each "view" is mapped to the n-view div on the index page
var mInfoWebApp = angular.module('mInfoWebApp', ['ngRoute' ]);
mInfoWebApp.config(
		function($routeProvider, $locationProvider) {
		    $routeProvider.
			    when('/', {
			    	templateUrl: 'views/mLogin.html',
					controller: 'IndexCtrl'
			    }).
			    when('/home', {
			    	templateUrl: 'views/mExhibitDetails.html',
					controller: 'media_controller'
			    }).
			    otherwise({
				redirectTo: '/views/mLogin.html'
			    });
		}
	);

// Simple controller to display modal form and
// post to php page
mInfoWebApp.controller('FormCtrl', function ($scope,$http) {
    	$scope.errors = [];
        $scope.msgs = [];
 		   $scope.showModal = false;
    $scope.toggleModal = function(){
        $scope.showModal = !$scope.showModal;
    };
        $scope.SignUp = function() {
            $scope.errors.splice(0, $scope.errors.length); 
            $scope.msgs.splice(0, $scope.msgs.length);
 
            $http.post('submit.php', {'uname': $scope.LastName, 'pswd': $scope.userpassword, 'email': $scope.useremail}
                ).success(function(data, status, headers, config) {
                    if (data.msg != '')
                    {
                        $scope.msgs.push(data.msg);
                    }
                    else
                    {
                        $scope.errors.push(data.error);
                    }
                    }).error(function(data, status) { 
                        $scope.errors.push(status);
                  });
                  $scope.toggleModal();
            }
  });
//As above
// 
mInfoWebApp.controller('FormCtrlExhibit', function ($scope,$http) {
      $scope.errors = [];
        $scope.msgs = [];
       $scope.showModal = false;
    $scope.toggleModal = function(){
        $scope.showModal = !$scope.showModal;
    };
        $scope.SignUp = function() {
            $scope.errors.splice(0, $scope.errors.length); 
            $scope.msgs.splice(0, $scope.msgs.length);
 
            $http.post('submitExhibit.php', {'title': $scope.titles, 'altTitle': $scope.altTitle, 'artist': $scope.artist, 'date':$scope.date, 'tag':$scope.tag}
                ).success(function(data, status, headers, config) {
                    if (data.msg != '')
                    {
                        $scope.msgs.push(data.msg);
                    }
                    else
                    {
                        $scope.errors.push(data.error);
                    }
                    }).error(function(data, status) { 
                        $scope.errors.push(status);
                    });
                    $scope.toggleModal();
            }
  });
//  Controller to toggle the display of the modal forms
// 
mInfoWebApp.controller('modalCtrl', function ($scope,$modalInstance) {
    $scope.showModal = false;
    $scope.toggleModal = function(){
        $scope.showModal = !$scope.showModal;
    };
  });

//Associated Directive
// 
mInfoWebApp.directive('modal', function () {
    return {
      template: '<div class="modal fade">' + 
          '<div class="modal-dialog">' + 
            '<div class="modal-content">' + 
              '<div class="modal-header">' + 
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' + 
                '<h4 class="modal-title">{{ title }}</h4>' + 
              '</div>' + 
              '<div class="modal-body" ng-transclude></div>' + 
            '</div>' + 
          '</div>' + 
        '</div>',
      restrict: 'E',
      transclude: true,
      replace:true,
      scope:true,
      link: function postLink(scope, element, attrs) {
        scope.title = attrs.title;

        scope.$watch(attrs.visible, function(value){
          if(value == true)
            $(element).modal('show');
          else
            $(element).modal('hide');
        });

        $(element).on('shown.bs.modal', function(){
          scope.$apply(function(){
            scope.$parent[attrs.visible] = true;
          });
        });

        $(element).on('hidden.bs.modal', function(){
          scope.$apply(function(){
            scope.$parent[attrs.visible] = false;
          });
        });
      }
    };
  });

//Controller for get Call.  gets Details from Php page and assigns them to the scope
// 
mInfoWebApp.controller('media_controller', function($scope, $http) {
  
  var site = "http://localhost:8080";
  var page = "/project/asset.php"
  $http.get(site + page).success(function(response){$scope.asset = response;console.log($scope.asset);});

  
});

mInfoWebApp.controller('Rating_Ctrl', ['$scope','$http', function($scope, $http) {
    $scope.rating = 0;
    $scope.ratings = [{
        current: 1,
        max: 10
    }];

    var save =  function() {
      $http.post('submitExhibit.php', {'title': $scope.titles, 'altTitle': $scope.altTitle, 'artist': $scope.artist, 'date':$scope.date, 'tag':$scope.tag}
          ).success(function(data, status, headers, config) {
              
              }).error(function(data, status) { 
                  console.log(status);
              });
              
      }
    $scope.getSelectedRating = function (rating) { 
      save(rating);
    } 
}]);

mInfoWebApp.directive('starRating', function () {
    return {
        restrict: 'A',
        template: '<ul class="rating">' +
            '<li ng-repeat="star in stars" ng-class="star" ng-click="toggle($index)">' +
            '\u2605' +
            '</li>' +
            '</ul>',
        scope: {
            ratingValue: '=',
            max: '=',
            onRatingSelected: '&'
        },
        link: function (scope, elem, attrs) {

            var updateStars = function () {
                scope.stars = [];
                for (var i = 0; i < scope.max; i++) {
                    scope.stars.push({
                        filled: i < scope.ratingValue
                    });
                }
            };
            scope.toggle = function (index) {
                scope.ratingValue = index + 1;
                scope.onRatingSelected({
                    rating: index + 1
                });
            };

            scope.$watch('ratingValue', function (oldVal, newVal) {
                if (newVal) {
                    updateStars();
                }
            });
        }
    }
});
//Directive for tooltips
// 
mInfoWebApp.directive('toggle', function(){
  return {
    restrict: 'A',
    link: function(scope, element, attrs){
      if (attrs.toggle=="tooltip"){
        $(element).tooltip();
      }
      if (attrs.toggle=="popover"){
        $(element).popover();
      }
    }
  };
})


