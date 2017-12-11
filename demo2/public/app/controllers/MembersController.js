app.directive('fileModel', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function(){
				scope.$apply(function(){
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	};
}]);

app.controller('MembersController', function ($scope,$http,API){
	$http({
		method: 'GET',
		url: API + 'list'
	}).then(function successCallback(response) {
		$scope.members = response.data;
		$scope.reverse = false;
		$scope.sortData = function (column) {
			if ($scope.sortColumn == column)
				$scope.reverse = !$scope.reverse;
			else
				$scope.reverse = false;
				$scope.sortColumn = column;
		}
		$scope.getSortClass = function (column) {
			if ($scope.sortColumn == column) {
				return $scope.reverse ? 'arrow-up' : 'arrow-down';
			}
			return '';
		}
		

	$scope.modal = function (state,id) {
		$scope.state = state
		switch (state) {
			case "add" :
			$scope.frmTitle = "Add member";
			break;
			case "edit" :
			$scope.frmTitle = "Update member";
			$scope.id = id;
			$http.get('edit/' + id).then(function (response){
				$scope.member = response.data;
			});
			break;
			default :
			$scope.frmTitle = "";
			break;
		}
		// console.log(id);
		$("#myModal").modal('show');
		$scope.member = null;
	}

	$scope.save = function(state,id){
		if (state == "add") {
			
			var data = {
				"name":$scope.member.name,
				"age":$scope.member.age,
				"address":$scope.member.address,
			};
			if ($scope.myFile) {
				data.photo = $scope.myFile;
			}
			// console.log(data.photo.type);

			$http({
				method: 'POST',
				url: API +'add',
				headers: {'Content-Type': undefined},
				data: data,
				transformRequest: function (data, headersGetter) {
					var fd = new FormData();
					angular.forEach(data, function (value, key) {
						fd.append(key, value);
					});
					var headers = headersGetter();
					delete headers['Content-Type'];
					return fd;
				}
			}).then(function successCallback(response) {
				
				$http({
					method: 'GET',
					url: API + 'list'
				}).then(function successCallback(response) {
					$scope.members = response.data;
				});
				$("#myModal").modal('hide');

				
			}, function errorCallback(response) {
				var data = response
				angular.forEach(data, function(value, key){
					$('#error').html(value['photo']);
				});
			});
		}

		if (state == "edit") {
			var data = {
				"name":$scope.member.name,
				"age":$scope.member.age,
				"address":$scope.member.address,
			};
			if ($scope.myFile) {
				data.photo = $scope.myFile;
			}
			$http({
				method: 'POST',
				url: API + 'edit/' + id,
				headers: {'Content-Type': undefined},
				data: data,
				transformRequest: function (data, headersGetter) {
					var fd = new FormData();
					angular.forEach(data, function (value, key) {
						fd.append(key, value);
					});
					var headers = headersGetter();
					delete headers['Content-Type'];
					return fd;
				}
			}).then(function successCallback(response) {
				$http({
					method: 'GET',
					url: API + 'list'
				}).then(function successCallback(response) {
					$scope.members = response.data;

				});
				$("#myModal").modal('hide');
			}, function errorCallback(response) {
				var data = response
				angular.forEach(data, function(value, key){
					$('#error').html(value['photo']);
				});
			});
		}
	}

	$scope.confirmDelete = function (id){
		var isConfirmDelete = confirm("Are you sure?");
		if (isConfirmDelete) {
			$http({
				method: 'GET',
				url: API + '/delete/' + id,
			}).then(function successCallback(response) {
				$http({
					method: 'GET',
					url: API + 'list'
				}).then(function successCallback(response) {
					$scope.members = response.data;

				});
				$("#myModal").modal('hide');
			}, function errorCallback(response) {
				console.log(response);
				alert('Error');
			});
			return true;
		}else{
			return false;
		}
	}
	
	$scope.clear = function () {
		$scope.myFile = null;
		console.log($scope.myFile);
	};

	$scope.reset = function(){
		$scope.member.name = null;
		$scope.member.age = null;
		$scope.member.address = null;
		$scope.myFile = null;
	}

});
});


