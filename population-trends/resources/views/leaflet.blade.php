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
          <div class="form-title">
            <p>人口構成</p>
          </div>
          <div class="form-content">
              <div class="form-radio">
                <p><input type="radio" name="options" value="5sai" checked>年齢5歳階級</p>
                <p><input type="radio" name="options" value="3sedai">3世代区分</p>
              </div>

            <div class="5age-group mt-1" id="5age-group">
              <div v-for="(fiveage, index) in fiveages" class="fiveage-check" :key="index">
                <input type="checkbox" :id="'fiveage'+index" v-model="fiveageChecked" :value="index * 5" @change="getMethod">
                <label :for="'fiveage'+index">@{{ fiveage }}</label>
              </div>
            </div>

            <div class="3sedai-group mt-1">

              <div v-for="(sedai, index) in sedais" class="sedai-check" :key="index">
                <input type="checkbox" :id="'sedai'+index" v-model="sedaiChecked" :value="index + 1" @change="getMethod">
                <label :for="'sedai'+index">@{{ sedai }}</label>
              </div>
                
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
            {{-- 仮の値を入れディレクティブの動作をチェック --}}
            {{-- <p>@{{ fiveageChecked }}</p>
            <p>@{{ sedaiChecked }}</p> --}}
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

{{-- <script>

<label>
      <input type="radio" name="options" value="option2" onchange="toggleCheckboxes(this)"> Option 2
    </label>

    <div id="checkboxes1" class="checkboxes">
      <label><input type="checkbox" name="checkbox1"> Checkbox 1</label>
      <label><input type="checkbox" name="checkbox2"> Checkbox 2</label>
      <label><input type="checkbox" name="checkbox3"> Checkbox 3</label>
    </div>

        function toggleCheckboxes(radio) {
        var checkboxes1 = document.getElementById('5age-group');
        var checkboxes2 = document.getElementById('3sedai-group');

        if (radio.value === 'option1') {
            checkboxes1.style.display = 'block';
            checkboxes2.style.display = 'none';
        } else if (radio.value === 'option2') {
            checkboxes1.style.display = 'none';
            checkboxes2.style.display = 'block';
        }
    }
</script> --}}
<script>

    //ロード時にマーカーを全削除する
    // window.addEventListener('unload', function(e) {
    //   let indexDel = 0;
    //   while(indexDel < this.markerLeft.length){
    //     this.map_left.removeLayer(this.markerLeft[indexDel]);
    //     console.log('削除');
    //     indexDel++;
    //   }
    // });

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
                fiveages:[],
                fiveageChecked:[],
                sedais:[],
                sedaiChecked:[],
                mapOptionsExecuted: false,
            },
            methods:{
              //セレクトボックスに年代を取得
              mapOptions(){

                // マーカーの最大値、最小値の平均の緯度経度を取得
                fetch('/mapOptions')
                .then(response => response.json())
                .then(data => {
 
                // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
                var map_left = L.map('map_left').setView([data[0].AvgIDO,data[0].AvgKEIDO], this.zoomLevel);
                var map_right = L.map('map_right').setView([data[0].AvgIDO,data[0].AvgKEIDO], this.zoomLevel);

                // OpenStreetMapをタイルレイヤーとして追加
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map_left);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map_right);


                //双方向にマップを連動させる
                this.map_right = map_right.sync(map_left);
                this.map_left = map_left.sync(map_right);
                })
              },
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
              getFiveage(){

                fetch('/fiveage')
                .then(response => response.json())
                .then(data => {

                  let fiveageIndex = 0;
                  while(fiveageIndex < data.length){
                    this.fiveages.push(data[fiveageIndex]['5SAI_NAME']);
                    fiveageIndex++;
                  }

                });
              },
              getSedai(){

                fetch('/sedai')
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
                fetch('/popData?year='+this.yearLeft+'&fiveage='+this.fiveageChecked+'&sedai='+this.sedaiChecked) 
                    .then(response => response.json())
                    .then(data => {

                      //初期設定
                      this.markerLeft = [];
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
                
                fetch('/popData?year='+ this.yearRight+'&fiveage='+this.fiveageChecked+'&sedai='+this.sedaiChecked)
                    .then(response => response.json())
                    .then(data => {
                      
                      //初期設定
                      this.markerRight = [];
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
              if(this.mapOptionsExecuted == false){
                this.mapOptions();
                this.getYears();
                this.getFiveage();
                this.getSedai();
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

