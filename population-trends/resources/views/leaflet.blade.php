<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaflet地図サンプル</title>
    {{-- leaflet.css --}}
    <link rel="stylesheet" href="{{ asset('css/leaflet.css')}}">
    {{-- Leaflet CDN --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    {{-- Leaflet プラグイン --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  </body>
</html>
<body>

  <div id="app" class="container-fluid">
      <div class="row">
        <div class="col-2"></div>
          <div class="col-5">
            <div class="form-group">
              <label>年代</label>
                <select class="form-control" v-model="year">
                  <option v-for="year in years" :value="year">@{{ year }} 年</option>
                </select>
                <div id="map_left" class="map"></div>
            </div>
          </div>
          <div class="col-5">
            {{-- <div class="form-group">
              <label>年代</label>
              <select class="form-control" v-model="year">
                <option v-for="year in years" :value="year">@{{ year }} 年</option>
              </select> --}}
              <div id="map_right" class="map"></div>
            {{-- </div> --}}
          </div>
      </div>
  </div>

{{-- Vue@3 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@3.4.19"></script> --}}
{{-- Leaflet プラグイン --}}
<script src="{{ asset('Leaflet.Sync-master/L.Map.Sync.js') }}"></script>
{{-- lodash CDN --}}
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
{{-- Bootstrap CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<script>
    // Vue.createApp({
    //   data:'こんにちは'
    // }).mount('#app')
    new Vue({
            el: '#app',
            data: {
                year: '{{ date('Y') }}',
                years:[],
            },
            methods:{
              getYears(){

                fetch('/year')
                .then(response => response.json())
                .then(data => this.years = data);

              },
              mounted() {

              this.getYears();

              }
            }
          });

    // getYears(){
    //        // 年代の取得
           

    //           var years = ["1980", "1990", "2000", "2010", "2020"];

    //           function createSelectBox(array) {
    //             var selectBox = document.getElementById("yearSelect");

    //             // 配列の要素をセレクトボックスのオプションとして追加
    //             array.forEach(function(year) {
    //                 var option = document.createElement("option");
    //                 option.text = year;
    //                 selectBox.add(option);
    //             });
    //           }
    // //         });
    // }

// // 関数を呼び出してセレクトボックスを作成
// createSelectBox(years);


    //areaとbase_populationの結合データを全部持ってくる
    fetch('/areaPop')
    .then(response => response.json())
    .then(data =>{

        //結合データを地域・緯度・経度の順で並び替える
        const groupOrder = _.orderBy(data,group =>{
          return [group.CHIIKINAME,group.IDO,group.KEIDO]
        });
        console.log(groupOrder);

        //地域・緯度・経度・人口のグループ化・配列化
        let areaName = _.groupBy(groupOrder,'CHIIKINAME');
        let areaNameGroup = areaName;
        areaName = _.keys(areaName);

        let latitude = _.groupBy(groupOrder,'IDO');
        latitude = _.keys(latitude);

        let longitude = _.groupBy(groupOrder,'KEIDO');
        longitude = _.keys(longitude);

        let population = _.map(areaNameGroup,group=>{
          return _.sumBy(group,'POPLATION');
        });

        //初期設定
        const area = areaName; //地域名配列
        const lat = latitude; //緯度配列
        const lng = longitude; //経度配列
        const pop = population; //人口配列
        const markerLeft = []; //マーカー
        const markerRight = [];
        const zoomLevel = 12;
        let indexArea = 0;


        // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
        const map_left = L.map('map_left').setView([lat[0], lng[0]], zoomLevel);
        const map_right = L.map('map_right').setView([lat[0],lng[0]], zoomLevel);

        // OpenStreetMapをタイルレイヤーとして追加
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map_left);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map_right);

        //双方向にマップを連動させる
        map_left.sync(map_right);
        map_right.sync(map_left);

        // マーカー、ポップアップを追加する
        while(indexArea < area.length){
          markerLeft.push(L.marker([lat[indexArea],lng[indexArea]]).addTo(map_left));
          markerRight.push(L.marker([lat[indexArea],lng[indexArea]]).addTo(map_right));
          markerLeft[indexArea].bindPopup('<b>'+area[indexArea]+'</b><br>'+population[indexArea].toLocaleString()+'人').openPopup();
          markerRight[indexArea].bindPopup('<b>'+area[indexArea]+'</b><br>'+population[indexArea].toLocaleString()+'人').openPopup();
          indexArea++;
        }
  });

    
</script>

</body>
</html>

