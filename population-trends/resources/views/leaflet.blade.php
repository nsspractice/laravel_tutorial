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

  <div id="app" class="container-fluid" v-cloak>
      <div class="row">
        <div class="col-2">
          <div class="population-container">
            <div class="form-title">
              <p>人口構成</p>
            </div>
            <div class="form-content">
              <div class="form-radio">
                <p class="radio-element"><input type="radio"  name="options" value="5age" v-model="selectedOption" @change="toggleCheckboxes" checked>年齢5歳階級</p>
                <p class="radio-element"><input type="radio"  name="options" value="3sedai" v-model="selectedOption" @change="toggleCheckboxes">世代3区分</p>
              </div>

              <div id="scrollbar" class="scrollbar">
                <div v-if="selectedOption === '5age'">
                  <div v-for="(fiveageName, index) in fiveagesName" class="fiveage-check" :key="index">
                    <input type="checkbox" :id="'fiveageName'+index" v-model="fiveageChecked" :value="fiveages[index]" @change="getFiveageChecked">
                    <label :for="'fiveageName'+index" class="check-element">@{{ fiveageName }}</label>
                  </div>
                </div>

                <div v-else-if="selectedOption === '3sedai'">
                  <div v-for="(sedaiName, index) in sedaisName" class="sedai-check" :key="index">
                    <input type="checkbox" :id="'sedai'+index" v-model="sedaiChecked" :value="sedais[index]" @change="getSedaiChecked">
                    <label :for="'sedai'+index" class="check-element">@{{ sedaiName }}</label>
                  </div>  
                </div>
              </div>
            </div>
          </div>
          <div class="chiiki-container">
            <div class="form-title">
              <p>地域選択</p>
            </div>
            <div class="form-content">
            <div class="searchBox">
              <input type="search" v-model="searchBox" placeholder="検索" @change="getSearchChiiki">
              {{-- <input type="submit" name="submit" value="検索"> --}}
            </div>
              <div id="scrollbar" class="scrollbar">
                <div v-for="(chiikiName, index) in chiikisName" class="chiiki-check" :key="index">
                    <input type="checkbox" :id="'chiiki'+index" v-model="chiikiChecked" :value="chiikis[index]" @change="getChiikiChecked">
                    <label :for="'chiiki'+index" class="check-element">@{{ chiikiName }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="col-5">
            <div class="form-group">
              <label class="form-title">年代</label>
                <select class="form-control" v-model="yearLeft" @change="getPopDataLeft">
                  <option v-for="yearLeft in years" :value="yearLeft">@{{ yearLeft }} 年</option>
                </select>
                <div id="map_left" class="map">
                  <div class="marker-toggle">
                      <select class="select-plot" v-model="selectedmarkerLeft" @change="getPopDataLeft">
                        <option value="marker">マーカー</option>
                        <option value="bubble">バブル</option>
                      </select>
                  </div>
                </div>
            </div>
            {{-- 仮の値を入れディレクティブの動作をチェック --}}
            {{-- <p>@{{ fiveageChecked }}</p>
            <p>@{{ sedaiChecked }}</p>
            <p>@{{ chiikiChecked }}</p>
            <p>@{{ searchBox }}</p>
            <p>@{{ selectedmarkerLeft }}</p>
            <p>@{{ selectedmarkerRight }}</p> --}}
          </div>
          <div class="col-5">
            <div class="form-group">
              <label class="form-title">年代</label>
              <select class="form-control" v-model="yearRight" @change="getPopDataRight">
                <option v-for="yearRight in years" :value="yearRight">@{{ yearRight }} 年</option>
              </select>
              <div id="map_right" class="map">
                <div class="marker-toggle">
                  <select class="select-plot" v-model="selectedmarkerRight" @change="getPopDataRight">
                    <option value="marker">マーカー</option>
                    <option value="bubble">バブル</option>
                  </select>
                </div>
              </div>
            </div>
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

    var app = new Vue({
            el: '#app',
            data: {
                map_left:[],
                map_right:[],
                zoomLevel:12,
                yearLeft: [],
                yearRight: [],
                markerLeft: [],
                markerRight: [],
                years:[],
                fiveages:[null],
                fiveagesName:['すべて'],
                fiveageChecked:[null],
                sedais:[null],
                sedaisName:['すべて'],
                sedaiChecked:[],
                chiikis:[null],
                chiikisName:['すべて選択'],
                chiikiChecked:[null],
                fiveage_flag:1,
                sedai_flag:1,
                chiiki_flag:1,
                mapOptionsExecuted: false,
                selectedOption: '5age',
                selectedmarkerLeft: 'marker',
                selectedmarkerRight: 'marker',
                searchBox:[],
            },
            methods:{
              //セレクトボックスに年代を取得
              mapOptions(){
              
                // マーカーの最大値、最小値の平均の緯度経度を取得
                fetch('/mapOptions')
                .then(response => response.json())
                .then(data => {

                // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
                this.map_left = L.map('map_left').setView([data[0].AvgIDO,data[0].AvgKEIDO], this.zoomLevel);
                this.map_right = L.map('map_right').setView([data[0].AvgIDO,data[0].AvgKEIDO], this.zoomLevel);

                // OpenStreetMapをタイルレイヤーとして追加
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map_left);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map_right);


                //双方向にマップを連動させる
                this.map_right = this.map_right.sync(this.map_left);
                this.map_left = this.map_left.sync(this.map_right);
                })
              },
              //年代項目の取得
              getYears(){
                fetch('/year')
                .then(response => response.json())
                .then(data => {

                  //年代の昇順
                    this.years = data;
                    this.yearLeft = data[0];
                    this.yearRight = data[data.length-1];
                    this.getPopDataLeft();
                    this.getPopDataRight();
                });
              },
              //年齢5歳階級の年齢項目を取得
              getFiveage(){

                fetch('/fiveage')
                .then(response => response.json())
                .then(data => {
                  let fiveageIndex = 0;
                  while(fiveageIndex < data.length){
                    this.fiveages.push(data[fiveageIndex]['5SAI']);
                    this.fiveagesName.push(data[fiveageIndex]['5SAI_NAME']);
                    fiveageIndex++;
                  }
                });
              },
              //世代3区分の世代項目を取得
              getSedai(){

                fetch('/sedai')
                .then(response => response.json())
                .then(data => {


                  let sedaiIndex = 0;
                  while(sedaiIndex < data.length){
                    this.sedais.push(data[sedaiIndex]['3SEDAI']);
                    this.sedaisName.push(data[sedaiIndex]['3SEDAI_NAME']);
                    sedaiIndex++;
                  }

                });
                
              },
              //地域項目の取得
              getChiikiName(){
                fetch('/chiiki?chiiki='+this.searchBox)
                .then(response => response.json())
                .then(data => {
                  let chiikiIndex = 0;
                  let chiikisCopy = [null];
                  let chiikisNameCopy = ['すべて選択'];
                  while(chiikiIndex < data.length){
                    chiikisCopy.push(data[chiikiIndex]['CHIIKINAME']);
                    chiikisNameCopy.push(data[chiikiIndex]['CHIIKINAME']);
                    chiikiIndex++;
                  }
                  this.chiikis = chiikisCopy;
                  this.chiikisName = chiikisNameCopy;
                });
              },
              //年齢５歳階級の絞り込みの時のチェックの処理
              getFiveageChecked(){

                //チェックボックスで何も選択されていない場合の処理
                if (this.selectedOption === '5age' && this.fiveageChecked.length === 0) {
                  if(this.fiveage_flag === 1){
                    let fiveagesOnly = this.fiveages.slice(1);
                    this.fiveageChecked = fiveagesOnly;
                    this.fiveage_flag = 0;
                  }else if(this.fiveage_flag === 0){
                    this.fiveageChecked = [null];
                    this.fiveage_flag = 1;
                  }
                }

                //「すべて」が選択された場合、他の項目のチェックを全て外す処理
                if (this.fiveageChecked.length > 0 && this.fiveageChecked[this.fiveageChecked.length - 1] === null) {
                  this.fiveageChecked = [null];
                  this.fiveage_flag = 1;
                }

                //「すべて」を選択中に別の項目を選択した場合、「すべて」のチェックを外す処理
                if(this.fiveageChecked.includes(null) && this.fiveageChecked.length > 1){
                  this.fiveageChecked = this.fiveageChecked.filter(element => element !== null);
                  this.fiveage_flag = 0;
                }

                this.getChiikiName();
                this.getPopDataLeft();
                this.getPopDataRight();
              },
              //世代３区分の絞り込みの時のチェックの処理
              getSedaiChecked(){

                if(this.selectedOption === '3sedai' && this.sedaiChecked.length === 0) {
                  if(this.sedai_flag === 1){
                    let sedaisOnly = this.sedais.slice(1);
                    this.sedaiChecked = sedaisOnly;
                    this.sedai_flag = 0;
                  }else if(this.sedai_flag === 0){
                    this.sedaiChecked = [null];
                    this.sedai_flag = 1;
                  }
                }

                if (this.sedaiChecked.length > 0 && this.sedaiChecked[this.sedaiChecked.length - 1] === null){
                  this.sedaiChecked = [null];
                  this.sedai_flag = 1;
                }

                if(this.sedaiChecked.includes(null) && this.sedaiChecked.length > 1){
                  this.sedaiChecked = this.sedaiChecked.filter(element => element !== null);
                  this.sedai_flag = 0;
                }

                this.getChiikiName();
                this.getPopDataLeft();
                this.getPopDataRight();
              },
              //地域選択の絞り込みの時のチェックの処理
              getChiikiChecked(){

                if(this.chiikiChecked.length === 0) {
                  if(this.chiiki_flag === 1){
                    let chiikisOnly = this.chiikisName.slice(1);
                    this.chiikiChecked = chiikisOnly;
                    this.chiiki_flag = 0;
                  }else if(this.chiiki_flag === 0){
                    this.chiikiChecked = [null];
                    this.chiiki_flag = 1;
                  }
                }

                if(this.chiikiChecked.length > 0 && this.chiikiChecked[this.chiikiChecked.length-1] === null){
                  this.chiikiChecked = [null];
                  this.chiiki_flag = 1;
                }

                if(this.chiikiChecked.includes(null) && this.chiikiChecked.length > 1){
                  this.chiikiChecked = this.chiikiChecked.filter(element => element !== null);
                  this.chiiki_flag = 0;
                }
                this.getChiikiName();
                this.getPopDataLeft();
                this.getPopDataRight();

              },
              getSearchChiiki(){
                this.chiikiChecked = [null];
                this.chiiki_flag = 1;
                this.getChiikiName();
                this.getPopDataLeft();
                this.getPopDataRight();
              },
              //左のマップ、年代から人口を絞り込み
              getPopDataLeft(){
                this.mapIniLeft();
                fetch('/popData?year='+this.yearLeft+'&fiveage='+this.fiveageChecked+'&sedai='+this.sedaiChecked+'&chiiki='+this.chiikiChecked) 
                    .then(response => response.json())
                    .then(data => {

                      //初期設定
                      this.markerLeft = [];
                      let indexArea = 0;

                      let redIcon = L.icon({
                        iconUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-icon.png",
                        iconRetinaUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-icon-2x.png",
                        shadowUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-shadow.png",
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        tooltipAnchor: [16, -28],
                        shadowSize: [41, 41],
                        className: "icon-red", // <= ここでクラス名を指定
                      });

                      // マーカー、ポップアップを追加する
                      if(this.selectedmarkerLeft === 'marker'){
                        while(indexArea < data.length){
                          if(data[indexArea].population >= 10000){
                            redIcon.options.className = "icon-red";
                          }else{
                            redIcon.options.className = "icon-green";
                          }
                          this.markerLeft.push(L.marker([data[indexArea].IDO,data[indexArea].KEIDO],{ icon: redIcon }).addTo(this.map_left));
                          this.markerLeft[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                          indexArea++;
                        }
                      }else if(this.selectedmarkerLeft === 'bubble'){
                        while(indexArea < data.length){
                          this.markerLeft.push(L.circleMarker([data[indexArea].IDO,data[indexArea].KEIDO] /*円の緯度と経度*/, { 
                                radius: data[indexArea].population/300,  /*円の半径*/
                                fill: true, /*円の内側に色を塗るかどうか*/
                                color: 'red',  /*円の色*/
                                weight: 3    /*円の線幅*/
                        }).addTo(this.map_left));
                          this.markerLeft[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                          indexArea++;
                        }
                      }

                    });

              },
              //右のマップ、年代から人口を絞り込み
              getPopDataRight(){
                this.mapIniRight();
                fetch('/popData?year='+this.yearRight+'&fiveage='+this.fiveageChecked+'&sedai='+this.sedaiChecked+'&chiiki='+this.chiikiChecked)
                    .then(response => response.json())
                    .then(data => {
                      
                      //初期設定
                      this.markerRight = [];
                      let indexArea = 0;

                      let redIcon = L.icon({
                        iconUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-icon.png",
                        iconRetinaUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-icon-2x.png",
                        shadowUrl: "https://esm.sh/leaflet@1.9.2/dist/images/marker-shadow.png",
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        tooltipAnchor: [16, -28],
                        shadowSize: [41, 41],
                        className: "icon-red", // <= ここでクラス名を指定
                      });

                      // マーカー、ポップアップを追加する    
                      if(this.selectedmarkerRight === 'marker'){
                        while(indexArea < data.length){
                          if(data[indexArea].population >= 10000){
                            redIcon.options.className = "icon-red";
                          }else{
                            redIcon.options.className = "icon-green";
                          }
                          this.markerRight.push(L.marker([data[indexArea].IDO,data[indexArea].KEIDO]).addTo(this.map_right));
                          this.markerRight[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                          indexArea++;
                        }
                      }else if(this.selectedmarkerRight === 'bubble'){
                        while(indexArea < data.length){
                          this.markerRight.push(L.circleMarker([data[indexArea].IDO,data[indexArea].KEIDO] /*円の緯度と経度*/, { 
                                radius: data[indexArea].population/300,  /*円の半径*/
                                fill: true, /*円の内側に色を塗るかどうか*/
                                color: 'red',  /*円の色*/
                                weight: 3    /*円の線幅*/
                        }).addTo(this.map_right));
                          this.markerRight[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                          indexArea++;
                        }
                      }
                    });
              },
              //ラジオボタンの切替、スクロールバーの大きさの変更、「すべて」にチェックを入れる処理
              toggleCheckboxes(){

                if(this.selectedOption === '5age'){
                  var scrollHeight = document.getElementById('scrollbar').style.height = '250px';
                  this.fiveageChecked = [null];
                  this.sedaiChecked = [];
                  this.fiveage_flag = 1;
                }else if(this.selectedOption === '3sedai'){
                  var scrollHeight = document.getElementById('scrollbar').style.height = '100px';
                  this.fiveageChecked = [];
                  this.sedaiChecked = [null];
                  this.sedai_flag = 1;
                }

                this.getPopDataLeft();
                this.getPopDataRight();
              },
              //map_leftのマーカー初期化
              mapIniLeft(){
                //ロード時にマーカーを全削除する
                let indexDel = 0;
                while(indexDel < this.markerLeft.length){
                  this.map_left.removeLayer(this.markerLeft[indexDel]);
                  indexDel++;
                }
              },
              //map_rightのマーカー初期化
              mapIniRight(){
                //ロード時にマーカーを全削除する
                let indexDel = 0;
                while(indexDel < this.markerRight.length){
                  this.map_right.removeLayer(this.markerRight[indexDel]);
                  indexDel++;
                }
              },
            },  
            mounted() {
              if(this.mapOptionsExecuted == false){
                this.mapOptions();
                this.getYears();
                this.getFiveage();
                this.getSedai();
                this.getChiikiName();
                this.mapOptionsExecuted = true;
              }else{
                this.getPopDataLeft();
                this.getPopDataRight();
              }
            }
          });

</script>

</body>
</html>

