var myApp = angular.module('myApp', []);

myApp.controller('tree_blocks', function ($scope, $http, $httpParamSerializerJQLike) {
	
	$scope.search = [];

	$scope.idBlock = null;

	$scope.parameters = {
		input: {},
		output: {}
	};


	$scope.isObject = angular.isObject;


	$scope.showParameters = function(idBlock) {
		$scope.showInputParameters(idBlock);
		$scope.showOutputParameters(idBlock);

	}

	$scope.showInputParameters = function(idBlock) {
		$scope.parameters.input = {};
		$scope.showParam(idBlock, "input");
	}

	$scope.showOutputParameters = function(idBlock) {
		$scope.parameters.output = {};
		$scope.showParam(idBlock, "output");
	}


	$scope.showParam = function(idBlock, type) {
		$http({
		    url: '',
		    method: 'POST',
		    data: $httpParamSerializerJQLike({
				mod: "parameters",
				go: "get",
				id: idBlock,
				type: type
			}),
		    headers: {
		      'Content-Type': 'application/x-www-form-urlencoded'
		    }
		}).then(
			function(response) {
				console.log(response);
				console.log($scope);
				$scope.parameters[type] = response.data;
			},

			function() {
				alert('error');
			}
		);
	}

    return $http.get(
    	'?mod=tree_blocks&go=get'
	).then(
		function(response) {
			$scope.tree_blocks = response.data;
		},

		function() {
			alert('error');
		}
	);





});


function sleep(ms) {
	ms += new Date().getTime();
	while (new Date() < ms){}
}