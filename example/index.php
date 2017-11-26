<!doctype html>
<html lang="ru" ng-app="app">
  <head>
    <title>Just do it!</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
          display: none !important;
        }
    </style>
  </head>
  <body>
    <section class="container">
      <h1>Just do it!</h1>
      <p>Очередь заданий.</p>
        
        <div class="card" ng-controller="controller">
          <div class="card-header">
            <button class="btn btn-success btn-sm" ng-click="addTask()">Добавить задание</button>
            <button class="btn btn-secondary btn-sm" ng-click="clear()">Очистить очередь</button>
            <button class="btn btn-secondary btn-sm" ng-click="refresh()" ng-disabled="autorefresh">Обновить</button>
            <label><input type="checkbox" ng-model="autorefresh" name="autorefresh"> Обновлять автоматически</label>
          </div>
          <div class="card-body">
          
            <div ng-repeat="item in list">
              <div>Задание #{{item.id}} ({{item.status}}): {{item.data.progressText}}</div>
              <div class="progress mb-3">
                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{item.data.progress ? item.data.progress : '0'}}%">
                   {{item.data.progress ? item.data.progress : '0'}}%
                </div>
              </div>
            </div>
            
          </div>
        </div>
    </section>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script type="text/javascript">
'use strict';
var app = angular.module("app", []);

app.factory('service', ['$rootScope', '$http', '$timeout',
function($rootScope, $http, $timeout) {    
    var $scope = $rootScope.$new(true);
    var items  = [{}];

    return {
        scope  : $scope,
        items  : items,
        update : updateList,
        addTask: addTask,
        clear  : clear
    };
    
    function req(action, data) {
        return $http.post("api.php?action=" + action, null, {responseType: 'json'});
    };
    
    function updateList() {
        req('list').then(function(response){
            $timeout(function(){
                angular.copy(response.data, items);
            });
        }, function(){
            
        });
    }
    
    function addTask() {
        req('add').then(function(){
            
        }, function(){
            
        });
    }
    
    function clear() {
        req('clear').then(function(){
            
        }, function(){
            
        });
    }
} ]);

app.controller('controller', ['$scope', 'service', '$interval',
function($scope, service, $interval){
    $scope.list        = service.items;
    $scope.autorefresh = true;
    
    $scope.addTask = function() {
        service.addTask();
    };
    
    $scope.clear = function() {
        service.clear();
    };
    
    $scope.refresh = function() {
        service.update();
    };
    
    var stop;
    
    $scope.$watch('autorefresh', function(n,o){
        if (n) {
            $interval.cancel(stop);
            stop = $interval(function() {
                $scope.refresh();
            }, 500);
        } else {
            $interval.cancel(stop);
        }
    });
}]);
    </script>
  </body>
</html>