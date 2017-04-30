var schoolApp = angular.module("schoolApp", ['ui.router']);

// schoolApp.directive("fileread", [function () {
//     return {
//         scope: {
//             fileread: "="
//         },
//         link: function (scope, element, attributes) {
//             element.bind("change", function (changeEvent) {
//                 var reader = new FileReader();
//                 reader.onload = function (loadEvent) {
//                     scope.$apply(function () {
//                         scope.fileread = loadEvent.target.result;
//                     });
//                 }
//                 reader.readAsDataURL(changeEvent.target.files[0]);
//             });
//         }
//     }
// }]);

schoolApp.factory('SessionData', function(){
	var data = {
		username: 'username',
		image: 'image',
		role_id: '12',
	};
	return {
		get: () => data,
		put: newData => {data = newData;console.log(data);},
		patch: (fieldName, fieldValue) => {data[fieldName] = fieldValue},
	}
})

schoolApp.service('SessionManager', function($http, SessionData) {

    console.log("SessionManager service");

    this.login = function($username, $password) {
		var requestData = {
			action: 'login',
			username: $username,
			password: $password
		};
		var config = {
			headers: {'Content-Type': 'application/json; charset=utf-8'}
		};
		// Don't forget to return the promise from login function
		return $http
			.post('./php/server.php', requestData, config)
			.then(function(response) {

				// If one or more credentials is missed or wrong, you can return 400 Bad Request response from the server.
				// Here you can check if the response code is 200 Ok. If it is to continue, if it isn't show a warning

				console.log('Admin login data received');
				console.log(response);

				if (response.status === 200) {
					SessionData.put({
						username: response.data[0],
						role_id: response.data[1],
					});
					return response.data;
					// $state.go('school.summary-sc');
					// Redirecting logic should be placed in controller or specific service. Login service should to make a login attempt only.
				} else {
					console.log(response.data);
					window.alert(response.data);
					// $state.go('login');
				}
			}, function(data, status, headers, config){
				console.log(this);
				console.log('Admin login data not send');
				console.log(data);
			});
	}

});

schoolApp.config(function($stateProvider, $urlRouterProvider) {
	$stateProvider
		.state("login", {
			url: "/login",
			templateUrl: "./templates/login.html",
			controller: 'loginController'
		})
		.state("school", {
			url: "/school",
			templateUrl: "./templates/school.html"
		})
			.state("school.summary-sc", {
				url: "/summary-sc",
				templateUrl:"./templates/summary-sc.html"
			})
			.state("school.insert-st", {
				url: "/insert-st",
				templateUrl:"./templates/student-form.html"
			})
			.state("school.insert-cr", {
				url: "/insert-cr",
				templateUrl:"./templates/course-form.html"
			})
			.state("school.read-st", {
				url: "/read-st/:id/:name/:course/:phone/:email/:image",
				templateUrl:"./templates/student-read.html",
				controller: 'readStudentController'
			})
			.state("school.read-cr", {
				url: "/read-cr/:id/:name/:description/:image",
				templateUrl:"./templates/course-read.html",
				controller: 'readCourseController'
			})
			.state("school.update-st", {
				url: "/update-st",
				templateUrl:"./templates/student-form.html"
			})
			.state("school.update-cr", {
				url: "/update-cr",
				templateUrl:"./templates/course-form.html"
			})
		.state("admin", {
			url: "/admin",
			templateUrl: "./templates/admin.html"
		})
			.state("admin.summary-ad", {
				url: "/summary-ad",
				templateUrl: "./templates/summary-ad.html"
			})
			.state("admin.insert-ad", {
				url: "/insert-ad",
				templateUrl: "./templates/admin-form.html"
			})
			.state("admin.read-ad", {
				url: "/read-ad/:id/:name/:role/:phone/:email/:image",
				templateUrl: "./templates/admin-read.html",
				controller: 'readAdminController'
			})
			.state("admin.update-ad", {
				url: "/update-ad",
				templateUrl: "./templates/admin-form.html"
			});

		$urlRouterProvider.otherwise("/login");

});

schoolApp.controller('HeaderController', function ($scope, SessionData) {
	$scope.test = 'test';
	$scope.login = true;

	$scope.$watch(() => SessionData.get(), (newVal, oldVal) => {
		$scope.sessionData = newVal;
	})
})

schoolApp.controller('loginController', ['$scope', '$http', '$state', 'SessionManager', 'SessionData', function($scope, $http, $state, SessionManager, SessionData) {
	$scope.login = function() {
		console.log('loginController login function');
		SessionManager.login(SessionData.get().username, SessionData.get().password).then(function(data) {
			// console.log(data);
			console.log('the data comes from a promise');
		});
	}
	// $scope.login = function() {
	// 	var data = {
	// 		action: 'login',
	// 		username: 'shai',
	// 		password: '12345666'
	// 	};
	// 	$scope.sessionData = {
	// 			username: 'name',
	// 			image: 'picture',
	// 			role_id: 'role'
	// 		}
	// 	$http.post('./php/server.php', data, {headers: {
 //       	    'Content-Type': 'application/json; charset=utf-8'
 //       		}
	// 	}).then(function(data) {
	// 		console.log('Admin login data sent');
	// 		console.log(data);
	// 		$scope.sessionData = {
	// 			username: data.data[0],
	// 			image: data.data[1],
	// 			role_id: data.data[2]
	// 		}
	// 		console.log($scope.sessionData);
	// 		if (data.data === 'Wrong Username or Password') {
	// 			console.log(data.data);
	// 			window.alert(data.data);
	// 			$state.go('login');
	// 		} else {
	// 			$state.go('school.summary-sc');
	// 			$scope.login = false;
	// 		}

	// 	}, function(data, status, headers, config){
	// 		console.log('Admin login data not send');
	// 		console.log(data);
	// 	});

	// 	}

	$scope.logout = function() {
		var data = {
			action: 'logout'
		};
		$http.post('./php/server.php', data, {headers: {
   	        'Content-Type': 'application/json; charset=utf-8'
   	    	}
		}).then(function(data, status, headers, config) {
			console.log('Admin logout data sent');
			console.log(data);

		}, function(data, status, headers, config){
			console.log('Admin logout data not send');
			console.log(data);
		});
	}
}]);

schoolApp.controller('adminController', ['$scope', '$http', function($scope, $http){
	$scope.getData = function() {
		console.log("Initiating Admin data");
		var action = {action: 'read_admin'};
		$http.post('./php/server.php',action).then(function(data, status, headers, config) {
			console.log('get admin data success');
			console.log(data);
			$scope.adminData = data.data;
		}, function(data, status, headers, config){
			console.log('get admin data failed');
			console.log(data);
		});
	};

	$scope.getData()

}]);

schoolApp.controller('studentController', ['$scope', '$http', function($scope, $http){
	$scope.getData = function() {
		console.log("Initiating Student data");
		var action = {action: 'read_student'};
		$http.post('./php/server.php',action).then(function(data, status, headers, config) {
			console.log('get student data success');
			console.log(data);
			$scope.studentData = data.data;
		}, function(data, status, headers, config){
			console.log('get student data failed');
			console.log(data);
		});
	};

	$scope.getData()

}]);

schoolApp.controller('courseController', ['$scope', '$http', function($scope, $http){
	$scope.getData = function() {
		console.log("Initiating Course data");
		var action = {action: 'read_course'};
		$http.post('./php/server.php',action).then(function(data, status, headers, config) {
			console.log('get course data success');
			console.log(data);
			$scope.courseData = data.data;
		}, function(data, status, headers, config){
			console.log('get course data failed');
			console.log(data);
		});
	};

	$scope.getData()

}]);

schoolApp.controller('adminFormCntrl', ['$scope', '$http', '$state', function($scope, $http, $state) {
		$scope.form = [];
	    $scope.files = [];

	    $scope.setImage = function() {


	      	$scope.form.image = $scope.files[0];

	      	$http.post('./php/upload.php', data = $scope.form, {
	      		processData: false,
	      		transformRequest: function (data) {
			      var formData = new FormData();
			      formData.append("image", $scope.form.image);
			      return formData;
			  },
			  	 headers: {
			         'Content-Type': undefined
			  }
			}).then(function success(data, status, headers, config) {
					console.log('upload image success');
					$image_name = data;
					console.log($image_name);
					$scope.form[0] = $image_name;

			}, function(data, status, headers, config){
					console.log('upload image failed');
					console.log(data);
			});
			return data;
		}


	      $scope.uploadedFile = function(element) {
		    $scope.currentFile = element.files[0];
		    var reader = new FileReader();

		    reader.onload = function(event) {
		      $scope.image_source = event.target.result
		      $scope.$apply(function($scope) {
		        $scope.files = element.files;
		      });
		    }
                    reader.readAsDataURL(element.files[0]);
		  }



	$scope.postData = function (){
		// $scope.setImage();
		var data = {
				action: 'insert_admin',
				ad_name: $scope.adName,
				ad_phone: $scope.adPhone,
				ad_email: $scope.adEmail,
				ad_image: $scope.setImage(),
				ad_role_id: $scope.adRole_id,
				ad_password: $scope.adPassword
		};

		$http.post('./php/server.php', data, {headers: {
            'Content-Type': 'application/json; charset=utf-8'
        	}
		}).then(function(data, status, headers, config) {
			console.log('insert admin success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('insert admin failed');
			console.log(data);
		});
	}

}]);

schoolApp.controller('studentFormCntrl', ['$scope', '$http', function($scope, $http) {
	$scope.postData = function() {
		var data = {
				action: 'insert_student',
				st_name: $scope.stName,
				st_phone: $scope.stPhone,
				st_email: $scope.stEmail,
				st_image: $scope.stImage

		};
		$http.post('./php/server.php', data, {headers: {
            'Content-Type': 'application/json; charset=utf-8'
        	}
		}).then(function(data, status, headers, config) {
			console.log('insert student success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('insert student failed');
			console.log(data);
		});
	}
}]);

schoolApp.controller('courseFormCntrl', ['$scope', '$http', function($scope, $http) {
	$scope.postData = function() {
		var data = {
				action: 'insert_course',
				cr_name: $scope.crName,
				cr_description: $scope.crDescription,
				cr_image: $scope.crImage

		};
		$http.post('./php/server.php', data, {headers: {
            'Content-Type': 'application/json; charset=utf-8'
        	}
		}).then(function(data, status, headers, config) {
			console.log('insert course success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('insert course failed');
			console.log(data);
		});
	}
}]);

schoolApp.controller('summary-adController', ['$scope', '$http', function($scope, $http){
	$scope.getAdSummary = function() {
		var action = {
			action: 'count_admins'
		};
		$http.post('./php/server.php', action).then(function(data, status, headers, config) {
			console.log('count admins success');
			console.log(data);
			$scope.adSummary = data.data;
		}, function(data, status, headers, config){
			console.log('count admins failed');
			console.log(data);
		});
	}
	$scope.getAdSummary()
}]);

schoolApp.controller('summary-scController', ['$scope', '$http', function($scope, $http){
	$scope.getStSummary = function() {
		var action = {
			action: 'count_students'
		};
		$http.post('./php/server.php', action).then(function(data, status, headers, config) {
			console.log('count students success');
			console.log(data);
			$scope.stSummary = data.data;
		}, function(data, status, headers, config){
			console.log('count students failed');
			console.log(data);
		});
	};
	$scope.getCrSummary = function() {
		var action = {
			action: 'count_courses'
		};
		$http.post('./php/server.php', action).then(function(data, status, headers, config) {
			console.log('count courses success');
			console.log(data);
			$scope.crSummary = data.data;
		}, function(data, status, headers, config){
			console.log('count courses failed');
			console.log(data);
		});
	};
	$scope.getStSummary()
	$scope.getCrSummary()
}]);

schoolApp.controller('readAdminController', ['$scope', '$stateParams', '$http', '$state', function($scope, $stateParams, $http, $state){
	$scope.id = $stateParams.id
	$scope.name = $stateParams.name
	$scope.phone = $stateParams.phone
	$scope.email = $stateParams.email
	$scope.role = $stateParams.role
	$scope.image = $stateParams.image

	$scope.deleteData = function() {
		var data = {
			action: 'delete_admin',
			ad_id: $scope.id
		};
		$http.post('./php/server.php', data).then(function(data, status, headers, config) {
			console.log('delete course success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('delete course failed');
			console.log(data);
		});
		$state.go('admin.summary-ad')
	}

}]);

schoolApp.controller('readStudentController', ['$scope', '$stateParams', '$http', '$state', function($scope, $stateParams, $http, $state){
	$scope.id = $stateParams.id
	$scope.name = $stateParams.name
	$scope.phone = $stateParams.phone
	$scope.email = $stateParams.email
	$scope.course = $stateParams.course
	$scope.image = $stateParams.image

	$scope.deleteData = function() {
		var data = {
			action: 'delete_student',
			st_id: $scope.id
		};
		$http.post('./php/server.php', data).then(function(data, status, headers, config) {
			console.log('delete course success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('delete course failed');
			console.log(data);
		});
		$state.go('school.summary-sc')
	}

}]);

schoolApp.controller('readCourseController', ['$scope', '$stateParams', '$http', '$state', function($scope, $stateParams, $http, $state){
	$scope.id = $stateParams.id
	$scope.name = $stateParams.name
	$scope.description = $stateParams.description
	$scope.image = $stateParams.image

	$scope.deleteData = function() {
		var data = {
			action: 'delete_course',
			cr_id: $scope.id,
			cr_name: $scope.name,
			cr_description: $scope.description
		};
		$http.post('./php/server.php', data).then(function(data, status, headers, config) {
			console.log('delete course success');
			console.log(data);
		}, function(data, status, headers, config){
			console.log('delete course failed');
			console.log(data);
		});
		$state.go('school.summary-sc')
	}

}]);