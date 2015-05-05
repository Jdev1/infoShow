
var infoWebApp = angular.module('infoWebApp', [ 'googlechart','ngRoute' ]);
//Directive to handle client side routing
infoWebApp.config(
		function($routeProvider, $locationProvider) {
		    $routeProvider.
			    when('/', {
			    	templateUrl: 'views/main.html',
					controller: 'IndexCtrl'
			    }).
			    when('/home', {
			    	templateUrl: 'views/main.html',
					controller: 'a_user_controller'
			    }).
			    when('/admin_users', {
			    	templateUrl: 'views/a_user.html',
					controller: 'a_user_controller'
			    }).
			    when('/admin_exhibits', {
					templateUrl: 'views/a_exhibit.html',
					controller: 'a_exhibit_controller'
				  }).
			    when('/data_users', {
			    	templateUrl: 'views/d_user.html',
					controller: 'a_user_controller'
			    }).
			    when('/data_exhibits', {
					templateUrl: 'views/d_exhibit.html',
					controller: 'a_exhibit_controller'
				  }).
			    otherwise({
				redirectTo: '/views/main.html'
			    });
		}
	);
// Controller for modal for input
infoWebApp.controller('FormCtrl', function ($scope,$http) {
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

infoWebApp.controller('FormCtrlExhibit', function ($scope,$http) {
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

infoWebApp.controller('modalCtrl', function ($scope,$modalInstance) {
    $scope.showModal = false;
    $scope.toggleModal = function(){
        $scope.showModal = !$scope.showModal;
    };
  });

infoWebApp.directive('modal', function () {
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

infoWebApp.controller('IndexCtrl', function($scope) {  
    $scope.message = 'This is john';    
});

infoWebApp.controller('a_exhibit_controller', function($scope, $http) {
	var site = "http://localhost:8080";
	var page = "/project/dummyData/exhibits.txt"
	 $http.get(site + page).success(function(response){$scope.exhibits = response;});
	    
});

infoWebApp.controller('a_user_controller', function($scope, $http) {
	var site = "http://localhost:8080";
	var page = "/project/users.php"
	 $http.get(site + page).success(function(response){$scope.message = response;});
         
});

infoWebApp.controller('a_exhibit_controller', function($scope, $http) {
  var site = "http://localhost:8080";
  var page = "/project/exhibits.php"
  
   $http.get(site + page).success(function(response){$scope.exhibits = response;});

   $scope.remove = function(Tag){       
    var index = -1;   
    var comArr = eval( $scope.exhibits );
    for( var i = 0; i < comArr.length; i++ ) {
      if( comArr[i].Tag === Tag ) {
        index = i;
        alert(Tag);
        $http.delete(page+"?Tag="+Tag);
        break;
      }
    }
    if( index === -1 ) {
      alert( "Something gone wrong" );
    }
    $scope.exhibits.splice( index, 1 );    
  };
         
});

infoWebApp.controller('d_exhibit_controller', function($scope, $http) {
  var site = "http://localhost:8080";
  var page = "/project/interactions.php"
   $http.get(site + page ).success(function(response){$scope.message = response;});


 var page = "/project/chart.php"
 $http.get(site + page).success(function(response){$scope.chart3 = response;});

  var page = "/project/chart2.php"
 $http.get(site + page).success(function(response){$scope.chart4 = response;});

   var page = "/project/dateChart.php"
 $http.get(site + page).success(function(response){
  var temp = response;
  var max = temp.data.length;
  //Small hack to convert PHP-JSON date to JS date
  for (var i = 0 ; i <max; i++) {
    temp.data[i][0]=(new Date(temp.data[i][0]) );
  };
  console.log(temp);
  $scope.chart5 = temp;

  var page = "/project/heatMap.php"
  $http.get(site + page).success(function(response){$scope.heatMap= response;});
});
         
});

infoWebApp.controller("GaugeChartCtrl", function ($scope) {

    $scope.chartObject = {};
    $scope.chartObject.type = "Gauge";

    $scope.chartObject.options = {
      width: 400, height: 120,
      redFrom: 90, redTo: 100,
      yellowFrom:75, yellowTo: 90,
      minorTicks: 5
    };

    $scope.chartObject.data = [
      ['Label', 'Value'],
      ['Downloads', 2],
      ['Interactions', 55],
      ['AVG Rating', 7.2]
    ];
});

infoWebApp.controller('d_user_controller', function($scope, $http) {
  
  var site = "http://localhost:8080";
  var page = "/project/dummyData/graphs.txt"
  $http.get(site + page).success(function(response){$scope.chart = response;});
  //$scope.chart1;

  var page = "/project/dummyData/graphs2.txt"
  $http.get(site + page).success(function(response){$scope.chart1 = response;});

});

infoWebApp.controller('stats', function($scope, $http,$interval) {
  
  var site = "http://localhost:8080";
  var page = "/project/stats.php";
  $http.get(site + page).success(function(response){$scope.statistics= response;})
  $interval(call, 5000);

  function call() {
    
    $http.get(site + page).success(function(response){$scope.statistics = response;})
  }


});

infoWebApp.controller('infoStats', function($scope, $http,$interval) {
  
  var site = "http://localhost:8080";
  var page = "/project/infoStats.php";
  $http.get(site + page).success(function(response){$scope.infoStat = response;})
  $interval(call, 5000);

  function call() {
    
    $http.get(site + page).success(function(response){$scope.infoStat = response;})
  }


});

infoWebApp.controller('userPie', function($scope, $http,$interval) {
  
  var site = "http://localhost:8080";
  var page = "/project/userChart.php";
  $http.get(site + page).success(function(response){$scope.userPie = response;});
  $interval(call, 5000);

  function call() {
    
    $http.get(site + page).success(function(response){$scope.infoStat = response;})
  }


});

infoWebApp.controller('userCombo', function($scope, $http,$interval) {
  
  var site = "http://localhost:8080";
  var page = "/project/dateChartUser.php";
  $http.get(site + page).success(function(response){$scope.userCombo = response;});
  $interval(call, 5000);

  function call() {
    
    $http.get(site + page).success(function(response){$scope.infoStat = response;})
  }


});
infoWebApp.controller('userMonths', function($scope, $http,$interval) {
  console.log("HELLO");
  var site = "http://localhost:8080";
  var page = "/project/poularjoinChart.php";
  $http.get(site + page).success(function(response){$scope.userMonths = response;console.log($scope.userMonths)});
  /*$interval(call, 5000);

  function call() {
    
    $http.get(site + page).success(function(response){$scope.infoStat = response;})
  }*/


});


infoWebApp.directive('toggle', function(){
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

//The following clock directive is adapted from
//a tutorial found @
//http://jsdo.it/can.i.do.web/zHbM
infoWebApp.directive('clock', function($timeout, dateFilter){
    return function(scope, element, attrs){
       var timeId; 
      
      function updateLater() {

        timeoutId = $timeout(function() {
          element.text(dateFilter(new Date(), ' HH:mm:ss EEEE MMMM dd yyyy'));
          updateLater(); 
        }, 1000);
      }

      element.bind('$destroy', function() {
        $timeout.cancel(timeId);
      });
 
      updateLater(); 
    }
})

