<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/favicon.ico">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/gijgo@1.8.1/combined/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.7/angular.min.js"></script>
  </head>

  <body ng-app="myApp">
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Diasoft</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="list"></span>
                  Parser Log
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="settings"></span>
                  Settings
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-6 ml-sm-auto  pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Parser Log</h1>
          </div>

          <form>
            <div class="form-group row">
              <div class="col-sm-4">
                <div class="row">
                  <label for="exampleInputEmail1" class="col-sm-10">Отображать только с даты</label>
                  <div class="form-check class="col-sm-2"">
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

          <div ng-controller="tree_blocks">
            <div class="card block <?=$class?> mb-4"  ng-repeat="block in tree_blocks"  data-id="{{block.id}}" style="max-width: 30rem;">
              <div class="card-header title">
                {{block.number}}
                {{block.title}}
              </div>
              <div class="card-body">
                <h5 class="card-title">{{block.caption}}</h5>
                <p class="card-text">{{block.pageflow}}</p>
                <p class="card-text">{{block.parent_pageflow}}</p>
                <p class="card-text">Input Params <span class="badge badge-info">0</span></p>
                <p class="card-text">Output Params <span class="badge badge-info">0</span></p>
              </div>
            </div>
          </div>

        </main>
        <div class="col-md-4 sidebar-right">
          <div class="text">
          </div>
        </div>


      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../assets/bootstrap-4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/gijgo@1.8.1/combined/js/gijgo.min.js" type="text/javascript"></script>
    <script>
      $('.datepicker').datepicker({
            uiLibrary: 'bootstrap4',
            value: '19/02/2018'
        });
    </script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
    <script src="../assets/js/main.js"></script>
  </body>
</html>