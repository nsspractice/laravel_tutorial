<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Leaflet地図サンプル</title>
  <!-- leaflet.css-->
  <link rel="stylesheet" href="{{ asset('css/leaflet.css')}}">
  <!-- Leaflet CDN-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <!-- Leaflet プラグイン -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
<div class="container">
    <div class="map-container">
        <div class="space"></div>
        <div id="map_left" class="map">map_left</div>
        <div id="map_right" class="map">map_right</div>
    </div>
</div>

<script src="{{ asset('Leaflet.Sync-master/L.Map.Sync.js') }}"></script>

<script>
    const lat = 35.6895;//緯度
    const lng = 139.6917;//経度
    const zoomLevel = 20;

    // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
    const map_left = L.map('map_left').setView([lat, lng], zoomLevel);
    const map_right = L.map('map_right').setView([lat,lng], zoomLevel);

    // OpenStreetMapをタイルレイヤーとして追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map_left);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map_right);

    // マーカーを追加する
    const marker1 = L.marker([lat,lng]).addTo(map_left);
    const marker2 = L.marker([lat,lng]).addTo(map_right);

    // ポップアップを追加する
    marker1.bindPopup('<b>東京都</b><br>日本').openPopup();
    marker2.bindPopup('<b>東京都</b><br>日本').openPopup();

    //双方向にマップを連動させる
    map_left.sync(map_right);
    map_right.sync(map_left);
    
</script>

</body>
</html>

