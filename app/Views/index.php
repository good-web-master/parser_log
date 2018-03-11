<!doctype html>
<html lang="ru" ng-app="myApp">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Parser Log</title>

	<!-- Bootstrap core CSS -->
	<link href="../assets/bootstrap-4.0.0/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="../assets/css/main.css" rel="stylesheet">

	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.7/angular.min.js"></script>
</head>
<body>
	<header class="fixed-top">
		<nav class="navbar navbar-expand navbar-dark flex-column flex-md-row bg-dark">
			<a class="navbar-brand" href="#">Parser Log</a>	
		
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">
						Parser
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						Settings
					</a>
				</li>
			</ul>
		</nav>
	</header>
	<div class="container-fluid" ng-controller="tree_blocks">
		<div class="row">
			<main role="main" class="col-sm-4 ">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Parser Log</h1>
				</div>

				<form style="display: none;">
					<div class="form-group row">
						<div class="col-sm-4">
						<div class="row">
							<label for="exampleInputEmail1" class="col-sm-10">Отображать только с даты</label>
							<div class="form-check col-sm-2">
							<input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
							</div>
						</div>
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control datepicker">
						</div>
					</div>

					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck3">
						<label class="form-check-label" for="defaultCheck3">
						Отобразить только блоки с ошибкой
						</label>
					</div>

					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck4">
						<label class="form-check-label" for="defaultCheck4">
						Искать по наименованию
						</label>
					</div>

					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck5">
						<label class="form-check-label" for="defaultCheck5">
						Искать по значению
						</label>
					</div>

					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck6">
						<label class="form-check-label" for="defaultCheck6">
						Искать внутри list и map
						</label>
					</div>

					<button type="submit" class="btn btn-primary">Найти</button>
				</form>

				<div >
					<ul class="tree">
						<li class="open-node" ng-repeat="node in tree_blocks" ng-include="'block-tree'"></li>
					</ul>
				</div>
			</main>

			<div class="col-sm-8 sidebar-right">
				<form>
					
				</form>


            	<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="input-tab" data-toggle="tab" href="#input" role="tab" aria-controls="input" aria-selected="true">Input params</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="output-tab" data-toggle="tab" href="#output" role="tab" aria-controls="output" aria-selected="false">Output param</a>
				  </li>
				</ul>

				<div class="tab-content" id="myTabContent" >
					<div ng-include="'table-parameters'" ng-repeat="(type, parameters) in parameters" class="tab-pane fade" id="{{::type}}" role="tabpanel" aria-labelledby="{{::type}}-tab" >
				  <!-- <div ng-include="'table-parameters'" ng-init="pr = parameters.input" class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				  </div>
				  <div ng-include="'table-parameters'" ng-init="pr = parameters.output" class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab" >
				  </div> -->
				</div>
			</div>
		</div>
	</div>

	<script type="text/ng-template" id="block-tree">
		<div class="tree_label">
			<div ng-if="node.node_type == 'pageflow'">{{::node.pageflow.name}}</div>
		
			<div class="block" ng-if="node.node_type == 'block'" ng-click="showParameters(node.block.id)">
				<div class="block-header">
					{{::node.block.number}}
					{{::node.block.type}}
					{{::node.block.title}}
				</div>
				<div class="block-body">
					<div class="block-title">{{::node.block.caption}}</div>
					<div class="block-text">{{::node.block.pageflow}}</div>
					<div class="block-text">{{::node.block.parent_pageflow}}</div>
					
					<div class="row">
						<div class="col-sm-6">Input Params <span class="badge badge-info">{{::node.block.count_input_params}}</span></div>
						<div class="col-sm-6">Output Params <span class="badge badge-info">{{::node.block.count_output_params}}</span></div>
					</div>
				</div>

				
			</div>
		</div>
		
	    <ul ng-if="node.childs">
	        <li class="{{node.childs?'open-node':''}}" ng-repeat="node in node.childs" ng-include="'block-tree'"></li>
	    </ul>
	</script>

	<script type="text/ng-template" id="table-parameters">
		{{pr}}
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">Name</th>
		      <th scope="col">Value</th>
		      <th scope="col">Type</th>
		      <th scope="col">Scope</th>
			  <th scope="col">Name out</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr ng-repeat="(name, parameter) in parameters">
		      <th scope="row">{{::name}}</th>
		      <td ng-if="!isObject(parameter.value)">{{::parameter.value}}</td>
		      <td ng-if="isObject(parameter.value)"></td>
		      <td>{{::parameter.type}}</td>
		      <td>{{::parameter.scope}}</td>
		      <td>{{::parameter.name_out}}</td>
		    </tr>
		  </tbody>
		</table>
	</script>

	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="../assets/bootstrap-4.0.0/js/bootstrap.min.js"></script>

	<script src='../assets/js/main.js'></script>
</body>
</html>