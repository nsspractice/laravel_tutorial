<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Leaflet地図サンプル</title>
  <!-- LeafletのCSSファイルを読み込む -->
  {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" /> --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <!-- LeafletのJavaScriptファイルを読み込む -->
  {{-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script> --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- 地図のスタイルを定義する -->
  <style>
    body {
        padding: 0;
        margin: 0;
    }
    .container {
    display: flex;
    justify-content: space-between;
    width: 100%;
    }

    .map-container {
        display: flex;
        width: 100%;
    }

    .map {
        flex: 1;
        width: 100%;
        height: 500px; 
        margin: 20px;
    }
    .space {
        width: 200px;
        height: 500px; 
        border: 1px solid black;
        margin:20px 0 20px 20px;
    }

</style>
</head>
<body>
<div class="container">
    <div class="map-container">
        <div class="space"></div>
        <div id="map1" class="map">map1</div>
        <div id="map2" class="map">map2</div>
    </div>
</div>

<script>
    // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定
    const map1 = L.map('map1').setView([35.6895, 139.6917], 20);

    // OpenStreetMapをタイルレイヤーとして追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map1);

    // マーカーを追加する
    const marker1 = L.marker([35.6895, 139.6917]).addTo(map1);
    // ポップアップを追加する
    marker1.bindPopup('<b>東京都</b><br>日本').openPopup();

    // 地図を表示する要素を指定し、地図の中心座標とズームレベルを設定

    const map2 = L.map('map2').setView([35.6895, 139.6917], 20);
    // OpenStreetMapをタイルレイヤーとして追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map2);
  
    // マーカーを追加する
    const marker2 = L.marker([35.6895, 139.6917]).addTo(map2);
    // ポップアップを追加する
    marker2.bindPopup('<b>東京都</b><br>日本').openPopup();
</script>

</body>
</html>

