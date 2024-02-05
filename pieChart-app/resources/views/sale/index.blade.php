<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="app" class="container p-3">
        <div class="row">
            <div class="col-md-6">

                <!--  円グラフを表示するキャンバス -->
                <canvas id="chart" width="400" height="400"></canvas>

                <!--  年を選択するセレクトボックス -->
                <div class="form-group">
                    <label>販売年</label>
                    <select class="form-control" v-model="year" @change="getSales"> {{-- v-on:changeの省略 --}}
                        <option v-for="year in years" :value="year">@{{ year }} 年</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
    <script>

        new Vue({ 
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
                                this.years = data.slice().sort(function(a,b){
                                    return a-b;
                                });
                            // this.years = data.slice().sort((a,b) => a-b); JavaScriptにはorderByがないため、sort()メソッドを使用
                        }); //Json形式に変換されたdataをVueインスタンスのyearsプロパティに設定

                },
                getSales() {

                    //  販売実績データを取得 ・・・ ②
                    fetch('/ajax/sales/?year='+ this.year)
                        .then(response => response.json())
                        .then(data => {

                            if(this.chart) { // チャートが存在していれば初期化

                                this.chart.destroy();

                            }

                            //  lodashでデータを加工 ・・・ ③
                            const groupedSales = _.groupBy(data, 'company_name'); // 会社ごとにグループ化
                            const amounts = _.map(groupedSales, companySales => { //新しい配列として取得する
                                return _.sumBy(companySales, 'amount'); // 販売金額の合計を出し、値をcompanySalesに返す

                            });
                            const companyNames = _.keys(groupedSales); // 会社名が格納されている

                            //  円グラフを描画 ・・・ ④
                            const ctx = document.getElementById('chart').getContext('2d');
                            this.chart = new Chart(ctx, {
                                type: 'doughnut', //グラフの種類
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
                                        text: '売上統計'
                                    },
                                    tooltips: {
                                        callbacks: {
                                            label(tooltipItem, data) {

                                                const datasetIndex = tooltipItem.datasetIndex;
                                                const index = tooltipItem.index;
                                                const amount = data.datasets[datasetIndex].data[index];
                                                const amountText = Math.round((amount/1000).toLocaleString()); //Math.roundで四捨五入
                                                const company = data.labels[index];
                                                return ' '+ company +' '+amountText +' 千円';

                                            }
                                        }
                                    }
                                }
                            });

                        });

                }
            },
            mounted() {

                this.getYears();
                this.getSales();

            }
        });

    </script>
</body>
</html>