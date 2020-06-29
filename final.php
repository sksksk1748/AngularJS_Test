<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>NCUT AngularJS Final Examination</title>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <style>
    #divMain{
      width: 90%;
      margin: 0 auto;
      margin-top: 100px;
    }
    th{
      background-color: #4CAF50;
      color: white;
    }
    .table-bordered{
      width: 1200px;
    }
    .autoWidthCol { 
      width: 300px;
    }
    .EnterCityName{
      font-size: 24px;
    } 
  </style>
</head>
<body>
  <div id="divMain" ng-app="myApp" ng-controller="customersCtrl">
    <table class="table-bordered">
      <!-- 參考 https://abgne.tw/angularjs/angularjs-getting-stared/filters-2.html -->
      <!-- <tr><td>輸入桃園市的任一區: </td><td><input type="text" ng-model="CityName.Add"></td></tr> -->
      <!-- <tr><td>輸入桃園市的任一區: </td><td><select ng-model="selectedName" ng-options="item for item in district"></select></td></tr> -->
      <tr>
      <th>景點地址</th>
        <th>景點地址</th>
        <th>景點名稱</th>
      </tr>
      <!-- data是所有JSON載入資料，mydata是分頁過後的資料 -->
      <tr ng-repeat="x in mydata">
      <td>{{$index+1}}</td>
        <td ng-bind="x.Add" class="autoWidthCol"></td>
        <td class="autoWidthCol">
          <a ng-href="{{'http://' + x.TYWebsite}}" target="_blank">{{x.Name}}</a>
        </td>
      </tr>
    </table>
    
    <nav>
      <ul class="pagination">
        <li>
          <a ng-click="Previous()">
            <span>上一頁</span>
          </a>
        </li>
        <li ng-repeat="page in pageList" ng-class="{active: isActivePage(page)}" >
          <a ng-click="selectPage(page)" >{{ page }}</a>
        </li>
        <li>
          <a ng-click="Next()">
            <span>下一頁</span>
          </a>
        </li>
      </ul>
    </nav>
    
    <table>
      <tr>
        <td class="EnterCityName">輸入桃園市的任一區 : </td>
        <td>
          <!-- reference :　https://www.w3schools.com/angular/ng_ng-options.asp -->
          <select ng-model="selectedName" ng-options="item for item in district" class="EnterCityName">
          </select>
        </td>
      </tr>
    </table>

    <ul>
      <!-- data是所有JSON載入資料，mydata是分頁過後的資料 -->
      <!-- filter: {Add:selectedName} 找 Add裡符合option所選的字串 -->
      <li ng-repeat="City in data | filter: {Add:selectedName} |orderBy:'InfoId'">
         {{ City.Add +' ............................... '  }}
         <!-- JSON裡的TYWebsite 沒有 http:// 開頭，點擊連結會在網址前加上localhost，會無法連接到該網站伺服，所以要採用以下方式加入http:// -->
         <!-- https://stackoverflow.com/questions/16939371/how-to-link-to-external-sites-in-xampp -->
         <!-- https://www.coder.work/article/299708 -->
         <a ng-href="{{'http://' + City.TYWebsite}}" target="_blank">{{City.Name}}</a>
      </li>
    </ul>
  </div>

<script>
  var app = angular.module('myApp', []);
  app.controller('customersCtrl', function($scope, $http) {
    $scope.district = ["中壢區", "平鎮區", "龍潭區", "楊梅區", "新屋區", "觀音區", "桃園區", "龜山區", "八德區", "大溪區", "復興區", "大園區", "蘆竹區"];
    $scope.selectedName = "楊梅區";
    // JOSN DataSet From : https://data.gov.tw/dataset/26352
    $http.get("Taoyuan.json").then(function(response) {
      $scope.data = response.data.infos;

      //分頁總數
      $scope.pageSize = 15;

      //分頁數
      $scope.pages =Math.ceil($scope.data.length/$scope.pageSize);
      
      // document.write($scope.pages) 
      $scope.newPages = $scope.pages > 5 ? 5 : $scope.pages;
      $scope.pageList = [];
      $scope.selPage = 1;

      //設定表格資料來源(分頁)
      $scope.setData = function () {
        //通過當前頁數篩選出表格當前顯示資料
        $scope.mydata = $scope.data.slice(($scope.pageSize * ($scope.selPage - 1)), ($scope.selPage * $scope.pageSize));        
      }
      $scope.mydata = $scope.data.slice(0, $scope.pageSize);

      //分頁要repeat的陣列
      for (var i = 0; i < $scope.newPages; i++  ) {
        $scope.pageList.push(i +  1);
      }

      //列印當前選中頁索引
      $scope.selectPage = function (page) {
      //不能小於1大於最大
      if (page < 1 || page > $scope.pages) return;
      //最多顯示分頁數5
      if (page > 2) {
        //因為只顯示5個頁數，大於2頁開始分頁轉換
        var newpageList = [];
        for (var i = (page - 3) ; i < ((page +  2) > $scope.pages ? $scope.pages : (page +  2)) ; i++ ) {
          newpageList.push(i +  1);
        }
        $scope.pageList = newpageList;
      }
      $scope.selPage = page;
      $scope.setData();
      $scope.isActivePage(page);
      console.log("選擇的頁："  + page);
      };

      //設定當前選中頁樣式
      $scope.isActivePage = function (page) {
        return $scope.selPage == page;
      };

      //上一頁
      $scope.Previous = function () {
        $scope.selectPage($scope.selPage - 1);
      }

      //下一頁
      $scope.Next = function () {
        $scope.selectPage($scope.selPage +  1);
      };
    }, function errorCallback(response) {
			// 請求失敗執行程式碼
    });
  });

</script>
</body>
</html>