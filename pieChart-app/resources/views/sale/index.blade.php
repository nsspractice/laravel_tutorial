<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="app" class="container p-3">
        <div class="row">
            <div class="col-md-6">
                <!--  年を選択するセレクトボックス -->
                <div class="form-group">
                    <label>販売年</label>{{-- v-modelで選択された年はyearプロパティに格納--}}
                    <select class="form-control" v-model="year" @change="getSales"> {{-- v-on:changeの省略 --}}
                        <option v-for="year in years" :value="year">@{{ year }} 年</option>
                    </select>
                </div>

                <!--  円グラフを表示するキャンバス -->
                <canvas id="pieChart" width="300" height="300"></canvas>
                
            </div>
            <div class="col-md-6">
                <!--  折れ線グラフを表示するキャンバス -->
                <canvas id="lineChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
    <script>

        var app = new Vue({ 
            //Vueオブジェクトのインスタンスをnewで生成し、Vueアプリケーションの起動
            el: '#app',　
            //6行目のid="app"を指し、Vueアプリケーションの範囲、Vueの管轄領域を表します。タグ内部がすべてVueが適用。
            data: {
                sales: [],
                year: '{{ date('Y') }}',
                years: [],
                chart: null
            },
            //Vueアプリケーション内で利用できるデータ（変数）を表します。
            methods: {
                getYears() {

                    //  販売年リストを取得 ・・・ ①
                    fetch('/ajax/sales/years') //非同期通信を開始 
                        .then(response => response.json()) //HTTPレスポンスをJson形式に変換
                        .then(data => {
                                console.log(data);
                                this.years = data.slice().sort(function(a,b){
                                    return a-b;
                                });
                            // this.years = data.slice().sort((a,b) => a-b); JavaScriptにはorderByがないため、sort()メソッドを使用
                        }); //Json形式に変換されたdataをVueインスタンスのyearsプロパティに設定

                },
                getSales() {

                    //  販売実績データを取得 ・・・ ②
                    fetch('/ajax/sales?year='+ this.year)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if(this.chart) { // チャートが存在していれば初期化

                                this.chart.destroy();

                            }

                            //  lodashでデータを加工 ・・・ ③
                            const groupedSales = _.groupBy(data, 'company_name'); // 会社ごとにグループ化
                            const amounts = _.map(groupedSales, companySales => { //新しい配列として取得する
                                return _.sumBy(companySales, 'amount'); // 販売金額の合計を出し、値をcompanySalesに返す
                            });
                            const companyNames = _.keys(groupedSales); // 会社名が格納されている

                            console.log(amounts);
                            //  円グラフを描画 ・・・ ④
                            const ctx1 = document.getElementById('pieChart').getContext('2d');
                            this.chart = new Chart(ctx1, {
                                type: 'pie', //グラフの種類
                                data: {
                                    datasets: [{
                                        data: amounts, //販売金額の合計が配列の上から表示される
                                        borderColor: 'black',
                                        backgroundColor: [
                                            'rgb(255, 99, 132)',
                                            'rgb(255, 159, 64)',
                                            'rgb(255, 205, 86)',
                                            'rgb(75, 192, 192)',
                                            'rgb(54, 162, 235)',
                                            'rgb(153, 102, 255)',
                                            'rgb(201, 203, 207)'
                                        ]
                                    }],
                                    labels: companyNames //x軸に表示される、会社の名前が配列の上からラベル付けされる
                                },
                                options: {
                                    title: {
                                        display: true,
                                        fontSize: 45,
                                        text: '年度推移'
                                    },
                                    tooltips: { //tooltipsにツールチップに関する設定が含まれる
                                        callbacks: {  //ツールチップが表示されると、このコールバック関数が働きます。
                                            label(tooltipItem, data) { //ツールチップをラベルを設定するための引数であり変数
                                                const datasetIndex = tooltipItem.datasetIndex; //データセット（グラフ上のデータの集まり）のdatasetIndex(インデックス)取得
                                                const index = tooltipItem.index; //データセット内のデータポイント（データの要素）のindex(インデックス)を取得
                                                const amount = data.datasets[datasetIndex].data[index];
                                                const amountText = Math.round((amount/1000).toLocaleString()); //Math.roundで四捨五入　多次元配列
                                                const company = data.labels[index];
                                                return ' '+ company +' '+amountText +' 千円';

                                            }
                                        }
                                    }
                                }
                            });
                        });
                },
                getSales2() {
                    fetch('/ajax/sales/lines')
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const groupedSales = _.groupBy(data, value => {
                            return value.company_name+'#'+value.year;
                        });
                        const orderedSales = _.orderBy(groupedSales,value => {
                            return value.year;
                        });
                        const amounts = _.map(orderedSales, companySales => { //新しい配列として取得する
                                return _.sumBy(companySales, 'amount'); // 販売金額の合計を出し、値をcompanySalesに返す
                            });
                        const companyNames = _.keys(groupedSales);
                        var chunkedArray = [];
                        var chunkSize = 3;

                        for (var i = 0; i < amounts.length; i += chunkSize) {
                            var chunk = amounts.slice(i, i + chunkSize);
                            chunkedArray.push(chunk);
                        }

                        console.log(chunkedArray);
                            // 折れ線グラフを描画
                            const ctx2 = document.getElementById('lineChart').getContext('2d');
                            this.chart = new Chart(ctx2,{
                                type: 'line',
                                data: {
                                    datasets: [{
                                        data: chunkedArray[0], //販売金額の合計が配列の上から表示される
                                        borderColor: 'black',
                                        backgroundColor: [
                                            'rgb(255, 99, 132)',
                                            'rgb(255, 159, 64)',
                                            'rgb(255, 205, 86)',
                                            'rgb(75, 192, 192)',
                                            'rgb(54, 162, 235)',
                                            'rgb(153, 102, 255)',
                                            'rgb(201, 203, 207)'
                                        ]
                                    }],
                                    labels: this.years
                                },
                                options: {
                                    title:{
                                        display: true,
                                        fontSize: 45,
                                        text: '売上推移'
                                    }
                                }
                            });
                    });
                },
            },
            mounted() {

                this.getYears();
                this.getSales();
                this.getSales2();
            }
        });

    </script>
</body>
</html>