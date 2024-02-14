<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Leaflet地図サンプル</title>
    {{-- leaflet.css --}}
    <link rel="stylesheet" href="{{ asset('css/leaflet.css')}}">
    {{-- Leaflet CDN --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    {{-- Leaflet プラグイン --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
<div class="container">
    <div class="map-container">
        <div class="space"></div>
        <div id="map_left" class="map"></div>
        <div id="map_right" class="map"></div>
    </div>
</div>

{{-- Vue@3 CDN --}}
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
{{-- Leaflet プラグイン --}}
<script src="{{ asset('Leaflet.Sync-master/L.Map.Sync.js') }}"></script>

<script>

    //base_populationのデータを全部持ってくる
    fetch('/pop')
    .then(response => response.json())
    .then(pop =>{
      console.log(pop);
    });

    //areaのデータを全部持ってくる
    fetch('/area')
    .then(response => response.json())
    .then(area =>{
        console.log(area);
        //初期設定
        const lat = []; //緯度
        const lng = []; //経度
        const markerLeft = []; //マーカー
        const markerRight = [];
        const zoomLevel = 12;
        let indexLat = 0;
        let indexLng = 0;
        let indexArea = 0;

        // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
        const map_left = L.map('map_left').setView([area[3].IDO, area[3].KEIDO], zoomLevel);
        const map_right = L.map('map_right').setView([area[3].IDO,area[3].KEIDO], zoomLevel);

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

        //緯度を配列化
        while(indexLat < area.length){
          lat.push(area[indexLat].IDO);
          indexLat++;
        }
        //経度を配列化
        while(indexLng < area.length){
          lng.push(area[indexLng].KEIDO);
          indexLng++;
        }

        // マーカー、ポップアップを追加する
        while(indexArea < area.length){
          markerLeft.push(L.marker([lat[indexArea],lng[indexArea]]).addTo(map_left));
          markerRight.push(L.marker([lat[indexArea],lng[indexArea]]).addTo(map_right));
          markerLeft[indexArea].bindPopup(area[indexArea].CHIIKINAME).openPopup();
          markerRight[indexArea].bindPopup(area[indexArea].CHIIKINAME).openPopup();
          indexArea++;
        }

        // // マーカーを追加する
        // const markerLeft = L.marker([lat,lng]).addTo(map_left);
        // const markerRight = L.marker([lat,lng]).addTo(map_right);

        // // ポップアップを追加する
        // markerLeft.bindPopup('<b id="JUSHO1"></b>').openPopup();
        // markerRight.bindPopup('<b id="JUSHO2"></b>').openPopup();
        // document.getElementById('JUSHO1').innerHTML = area[0].CHIIKINAME;
        // document.getElementById('JUSHO2').innerHTML = area[0].CHIIKINAME;
  });

    
</script>

</body>
</html>

