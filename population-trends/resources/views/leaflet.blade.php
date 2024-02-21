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
        <div class="col-2">
          <div class="form-group">

            <input type="radio" name="options" value="data1" checked>
            <label for="">年齢5歳階級</label><br>
            <input type="radio" name="options" value="data2">
            <label for="">3世代区分</label>
            
            {{-- <select class="form-control" v-model="sai">
              <option v-for="sai in sais" :value="sai">@{{ sai }}</option>
            </select> --}}

            {{-- <select class="form-control" v-model="sedai">
              <option v-for="sedai in sedais" :value="sedai">@{{ sedai }}</option>
            </select> --}}

            <div class="form-group">
              <div v-for="(sai, index) in sais" class="sai-check" :key="index">
                <input type="checkbox" :id="'sai'+index" v-model="saiChecked" :value="sai" @change="getMethod">
                <label :for="'sai'+index">@{{ sai }}</label>
              </div>
            </div>

            <div class="form-group">

              <div v-for="(sedai, index) in sedais" class="sedai-check" :key="index">
                <input type="checkbox" :id="'sedai'+index" v-model="sedaiChecked" :value="index + 1" @change="getMethod">
                <label :for="'sedai'+index">@{{ sedai }}</label>
              </div>
                
                {{-- <div class="sedai-check">
                  <input type="checkbox" id="sedai0" v-model="sedaiChecked":value="sedais[0]">
                  <label for="sedai0">@{{ sedais[0] }}</label>
                  <span>@{{ sedaiChecked }}</span>
                </div>
                <div class="sedai-check">
                  <input type="checkbox" id="sedai1" v-model="sedaiChecked":value="sedais[1]">
                  <label for="sedai2">@{{ sedais[1] }}</label>
                </div>
                <div class="sedai-check">
                  <input type="checkbox" id="sedai2" v-model="sedaiChecked":value="sedais[2]">
                  <label for="sedai2">@{{ sedais[2] }}</label>
                </div> --}}
            
              </div>
          </div>

        </div>
          <div class="col-5">
            <div class="form-group">
              <label>年代</label>
                <select class="form-control" v-model="yearLeft" @change="getPopDataLeft">
                  <option v-for="yearLeft in years" :value="yearLeft">@{{ yearLeft }} 年</option>
                </select>
                <div id="map_left" class="map"></div>
            </div>
            <p>@{{ saiChecked }}</p>
            <p>@{{ sedaiChecked }}</p>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>年代</label>
              <select class="form-control" v-model="yearRight" @change="getPopDataRight">
                <option v-for="yearRight in years" :value="yearRight">@{{ yearRight }} 年</option>
              </select>
              <div id="map_right" class="map"></div>
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
                yearLeft: 1980,
                yearRight: 2050,
                years:[],
                sais:[],
                saiChecked:[],
                sedais:[],
                sedaiChecked:[],
                mapOptionsExecuted: false,
                markerLeft:[],
                markerRight:[],
                sedaiSample:[],
            },
            methods:{
              //セレクトボックスに年代を取得
              mapOptions(){
                this.map_left = [];
                this.map_right = [];
 
                // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
                this.map_left = L.map('map_left').setView([33.327329,130.454671], this.zoomLevel);
                this.map_right = L.map('map_right').setView([33.327329,130.454671], this.zoomLevel);

                // OpenStreetMapをタイルレイヤーとして追加
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map_left);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map_right);

                //双方向にマップを連動させる
                this.map_right.sync(this.map_left);
                this.map_left.sync(this.map_right);
              
              },
              getYears(){

                fetch('/year')
                .then(response => response.json())
                .then(data => {

                  //年代の昇順
                    this.years = data;
                    this.yearLeft = data[0];
                    this.yearRight = data[data.length-1];

                });
              },
              getsai(){

                fetch('/5sai')
                .then(response => response.json())
                .then(data => {

                  let saiIndex = 0;
                  while(saiIndex < data.length){
                    this.sais.push(data[saiIndex]['5SAI_NAME']);
                    saiIndex++;
                  }

                });
              },
              getsedai(){

                fetch('/3sedai')
                .then(response => response.json())
                .then(data => {


                  let sedaiIndex = 0;
                  while(sedaiIndex < data.length){
                    this.sedais.push(data[sedaiIndex]['3SEDAI_NAME']);
                    sedaiIndex++;
                  }

                });
                
              },

              getMethod(){
                this.getPopDataLeft();
                this.getPopDataRight();
              },

              //年代から人口を絞り込み
              getPopDataLeft(){

                fetch('/yearData?year='+this.yearLeft) 
                    .then(response => response.json())
                    .then(data => {
                      console.log(data);

                      // if(!this.sedaiChecked === []){
                      //   let index = 0
                      //   while(index < sedaiChecked.length){
                      //     filteredData = _.filter(data, { '3SEDAI': sedaiChecked[index] });
                      //   }
                      // }

                      //結合データを地域・年代・緯度・経度の順で並び替える
                      // const groupOrder = _.orderBy(data,group =>{
                      //   return [group.CHIIKINAME,"group.5SAI","group.3SEDAI",group.YEAR,group.IDO,group.KEIDO]
                      // });
                      // console.log(groupOrder);

                      // let filteredData = _.filter(groupOrder, { '3SEDAI': "3" });
                      // console.log(filteredData);

                      //地域・緯度・経度・人口のグループ化・配列化
                      // let areaName = _.groupBy(data,'CHIIKINAME');
                      // let areaNameGroup = areaName;
                      // areaName = _.keys(areaName);
                    
                      // let latitude = _.groupBy(data,'IDO');
                      // latitude = _.keys(latitude);
    
                      // let longitude = _.groupBy(data,'KEIDO');
                      // longitude = _.keys(longitude);
 
                      // let population = _.map(areaNameGroup,group=>{
                      //   return _.sumBy(group,'POPLATION');
                      // });


                      //初期設定
                      this.markerLeft = [];
                      // const area = areaName; //地域名配列
                      // const lat = latitude; //緯度配列
                      // const lng = longitude; //経度配列
                      // const pop = population; //人口配列
                      let indexArea = 0;

                      // マーカー、ポップアップを追加する
                      while(indexArea < data.length){
                        this.markerLeft.push(L.marker([data[indexArea].IDO,data[indexArea].KEIDO]).addTo(this.map_left));
                        this.markerLeft[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                        indexArea++;
                      }  

                    });
              },
              getPopDataRight(){
                
                fetch('/yearData?year='+ this.yearRight)
                    .then(response => response.json())
                    .then(data => {
                      
                      // //結合データを地域・年代・緯度・経度の順で並び替える
                      // const groupOrder = _.orderBy(data,group =>{
                      //   return [group.CHIIKINAME,group.YEAR,"group.5SAI","group.3SEDAI",group.IDO,group.KEIDO]
                      // });
                      
                      //地域・緯度・経度・人口のグループ化・配列化
                      // let areaName = _.groupBy(data,'CHIIKINAME');
                      // let areaNameGroup = areaName;
                      // areaName = _.keys(areaName);

                      // let latitude = _.groupBy(data,'IDO');
                      // latitude = _.keys(latitude);

                      // let longitude = _.groupBy(data,'KEIDO');
                      // longitude = _.keys(longitude);

                      // let population = _.map(areaNameGroup,group=>{
                      //   return _.sumBy(group,'POPLATION');
                      // });

                      //初期設定
                      this.markerRight = [];
                      // const area = areaName; //地域名配列
                      // const lat = latitude; //緯度配列
                      // const lng = longitude; //経度配列
                      // const pop = population; //人口配列
                      let indexArea = 0;

                      // マーカー、ポップアップを追加する    
                      while(indexArea < data.length){
                        this.markerRight.push(L.marker([data[indexArea].IDO,data[indexArea].KEIDO]).addTo(this.map_right));
                        this.markerRight[indexArea].bindPopup('<b>'+data[indexArea].CHIIKINAME+'</b><br>'+data[indexArea].population+'人');
                        indexArea++;
                      }    
                    });
              },
            },
            
              mounted() {
              this.mapOptions();
              //クリアする処理メソッドを入れる
              this.getYears();
              this.getsai();
              this.getsedai();
              this.getPopDataLeft();
              this.getPopDataRight();

              }
          });

</script>

</body>
</html>

