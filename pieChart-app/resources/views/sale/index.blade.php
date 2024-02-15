<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css">
    <style>
        .content {
            padding: 20px 100px 60px 100px;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id="app" class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-xs-12 content">
                <h1>年度別売上</h1>
                <!--  年を選択するセレクトボックス -->
                <div class="form-group">
                    <label>販売年</label>{{-- v-modelで選択された年はyearプロパティに格納--}}
                    <select class="form-control" v-model="year" @change="getSales1"> {{-- v-on:changeの省略 --}}
                        <option v-for="year in years" :value="year">@{{ year }} 年</option>
                    </select>
                </div>
                <!--  円グラフを表示するキャンバス -->
                <canvas id="pieChart" width="250" height="250"></canvas>
            </div>    
                                
            <div class="col-md-6 col-xs-12 content">
                <h1>会社別年度推移</h1>
                <!--  折れ線グラフを表示するキャンバス -->
                <canvas id="lineChart" width="250" height="300"></canvas>
            </div>

            <div class="col-md-6 col-xs-12 content">
                <h1>総売上推移</h1>
                <!--  積み上げ棒グラフを表示するキャンバス -->
                <canvas id="barChart" width="250" height="250"></canvas>
            </div>
            <div class="col-md-6 col-xs-12 content">
                <h1>集計表</h1>
                <!-- 集計表を表示するグリッド-->
                <div id='table' class="table" width="250" height="250"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
    <script>
        var app = new Vue({ 
            //Vueオブジェクトのインスタンスをnewで生成し、Vueアプリケーションの起動
            el: '#app',
            //6行目のid="app"を指し、Vueアプリケーションの範囲、Vueの管轄領域を表します。タグ内部がすべてVueが適用。
            data: {
                sales: [],
                year: '{{ date('Y') }}',
                years: [],
                companies: [],
                grid: null,
                pieChart: null
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
                    fetch('/ajax/sales/lines')
                    .then(response => response.json())
                    .then(data => {
                        const groups = _.orderBy(data,group => { //会社名、年度の順で並び替える
                            return [group.company_name, group.year];
                        });
                        const groupCompany = _.groupBy(groups,'company_name'); //会社の名前でグループ化
                        const gridRows = _.keys(groupCompany); //会社名の配列作成
                        const groupYear = _.groupBy(groups,'year'); //年度でグループ化
                        const gridColumns = _.keys(groupYear); //年度の配列作成

                        const amountsCompany = _.map(groupCompany, group=>{ //会社毎の総売上
                            return _.sumBy(group,'amount');
                        });
                        const amountsYear = _.map(groupYear, group => {　   //年度別の総売上
                            return _.sumBy(group,'amount');
                        });                        
                        const amountsAll = _.sumBy(groups,'amount');//売上総額
                        
                        const groupsCY = _.groupBy(groups,group => {
                            return [group.company_name, group.year];
                        });

                        const amounts = _.map(groupsCY,group => {
                            return _.sumBy(group, 'amount');
                        });
                        console.log(amounts);

                        const amountsChunked = _.chunk(amounts,3);

                        for (let i = 0; i < amountsChunked.length; i++) {
                            amountsChunked[i].push(amountsCompany[i]); 
                        }
                        for (let i = 0; i < amountsChunked.length; i++) {
                            amountsChunked[i].unshift(gridRows[i]); 
                        }
                        amountsYear.unshift('年度総売上');//年度別の総売上を足す
                        amountsYear.push(amountsAll);
                        amountsChunked.push(amountsYear);
                        console.log(amountsChunked);
                    

                        gridColumns.push('会社総売上'); //合計値というカラムを配列に足す
                        gridColumns.unshift('会社名');
                        new gridjs.Grid({ 
                                columns: gridColumns,
                                data: amountsChunked,
                                style: {
                                    table: {
                                        border: '3px solid #ccc'
                                    },
                                    th: {
                                        fontSize: '14px',
                                        padding: '10px 5px',
                                        margin: '0',
                                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                                    },
                                    td: {
                                        fontSize: '14px',
                                        padding: '10px 5px',
                                        margin: '0',
                                    }
                                }   
                        }).render(document.getElementById('table'));
                        
                    });
                },
                getSales1() {

                    //  販売実績データを取得 ・・・ ②
                    fetch('/ajax/sales?year='+ this.year)
                        .then(response => response.json())
                        .then(data => {
                            if(this.pieChart) { // チャートが存在していれば初期化

                                this.pieChart.destroy();

                            }

                            //  lodashでデータを加工 ・・・ ③
                            const groups = _.orderBy(data,group=> {
                                return [group.company_name, group.year];
                            });
                            const groupedSales = _.groupBy(groups, 'company_name'); // 会社ごとにグループ化
                            const amounts = _.map(groupedSales, companySales => { //新しい配列として取得する
                                return _.sumBy(companySales, 'amount'); // 販売金額の合計を出し、値をcompanySalesに返す
                            });
                            const companyNames = _.keys(groupedSales); // 会社名が格納されている
                            this.companies = companyNames;

                            //  円グラフを描画 ・・・ ④
                            const ctx1 = document.getElementById('pieChart').getContext('2d');
                            new Chart(ctx1, {
                                type: 'pie', //グラフの種類
                                data: {
                                    datasets: [{
                                        data: amounts, //販売金額の合計が配列の上から表示される
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
                                    tooltips: { //tooltipsにツールチップに関する設定が含まれる
                                        callbacks: {  //ツールチップが表示されると、このコールバック関数が働きます。
                                            label(tooltipItem, data) { //ツールチップをラベルを設定するための引数であり変数
                                                console.log(data);
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
                        const groups = _.orderBy(data,group => {
                            return [group.company_name, group.year];
                        });
                        const groupsCY = _.groupBy(groups,group => {
                            return [group.company_name, group.year];
                        });
                        
                        const amounts = _.map(groupsCY,group => {
                            return _.sumBy(group, 'amount');
                        });
                        const amountsChunked = _.chunk(amounts,3);
                        this.sales = amountsChunked;
                        // 新しい配列を作成して、すべての値を1000で割って格納する

                        const groupedSales = _.groupBy(groups, 'company_name');
                        const companyNames = _.keys(groupedSales);
                        this.companies = companyNames;

                            // 折れ線グラフを描画
                            const ctx2 = document.getElementById('lineChart').getContext('2d');
                            new Chart(ctx2,{
                                type: 'line',
                                data: {
                                    datasets: 
                                    [{
                                        data: amountsChunked[0], //販売金額の合計が配列の上から表示される
                                        label: companyNames[0],
                                        lineTension:0,
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[1], //販売金額の合計が配列の上から表示される
                                        label: companyNames[1],
                                        lineTension:0,
                                        borderColor: 'rgb(255, 159, 64)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[2], //販売金額の合計が配列の上から表示される
                                        label: companyNames[2],
                                        lineTension:0,
                                        borderColor: 'rgb(255, 205, 86)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[3], //販売金額の合計が配列の上から表示される
                                        label: companyNames[3],
                                        lineTension:0,
                                        borderColor:  'rgb(75, 192, 192)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[4], //販売金額の合計が配列の上から表示される
                                        label: companyNames[4],
                                        lineTension:0,
                                        borderColor: 'rgb(54, 162, 235)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[5], //販売金額の合計が配列の上から表示される
                                        label: companyNames[5],
                                        lineTension:0,
                                        borderColor: 'rgb(153, 102, 255)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    },{
                                        data: amountsChunked[6], //販売金額の合計が配列の上から表示される
                                        label: companyNames[6],
                                        lineTension:0,
                                        borderColor: 'rgb(201, 203, 207)',
                                        backgroundColor: 'rgb(0,0,0,0)'
                                    }],
                                    labels: this.years
                                },
                                options: {
                                    scales: {
                                        xAxes: [{
                                            scaleLabel: {
                                                display:true,
                                                labelString: "(年)"
                                            }
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                // 目盛を千円単位にする
                                                callback: function(value, index, values) {
                                                    return value/1000;
                                                }

                                            },
                                            scaleLabel: {
                                                display:true,
                                                labelString: "(千円)"
                                            }
                                        }]
                                    },
                                    tooltips: { //tooltipsにツールチップに関する設定が含まれる
                                        callbacks: {  //ツールチップが表示されると、このコールバック関数が働きます。
                                            label(tooltipItem, data) { //ツールチップをラベルを設定するための引数であり変数
                                                console.log(data);
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
                getSales3() {
                    fetch('/ajax/sales/lines')
                    .then(response => response.json())
                    .then(data => {   
                        const groups = _.orderBy(data,group => {
                            return [group.company_name, group.year];
                        });
                        const groupsCY = _.groupBy(groups,group => {
                            return [group.company_name, group.year];
                        });
                        const amounts = _.map(groupsCY,group => {
                            return _.sumBy(group, 'amount');
                        });
                        
                        const amountsChunked = _.chunk(amounts,3);
                        const groupedSales = _.groupBy(groups, 'company_name');
                        const companyNames = _.keys(groupedSales);

                            // 積み上げ棒グラフを描画
                            const ctx3 = document.getElementById('barChart').getContext('2d');
                            new Chart(ctx3,{
                                type: 'bar',
                                data: {
                                    datasets: 
                                    [{
                                        data: amountsChunked[0], //販売金額の合計が配列の上から表示される
                                        label: companyNames[0],
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgb(255, 99, 132)'
                                    },{
                                        data: amountsChunked[1], //販売金額の合計が配列の上から表示される
                                        label: companyNames[1],
                                        borderColor: 'rgb(255, 159, 64)',
                                        backgroundColor: 'rgb(255, 159, 64)'
                                    },{
                                        data: amountsChunked[2], //販売金額の合計が配列の上から表示される
                                        label: companyNames[2],
                                        borderColor: 'rgb(255, 205, 86)',
                                        backgroundColor: 'rgb(255, 205, 86)'
                                    },{
                                        data: amountsChunked[3], //販売金額の合計が配列の上から表示される
                                        label: companyNames[3],
                                        borderColor:  'rgb(75, 192, 192)',
                                        backgroundColor: 'rgb(75, 192, 192)'
                                    },{
                                        data: amountsChunked[4], //販売金額の合計が配列の上から表示される
                                        label: companyNames[4],
                                        borderColor: 'rgb(54, 162, 235)',
                                        backgroundColor: 'rgb(54, 162, 235)'
                                    },{
                                        data: amountsChunked[5], //販売金額の合計が配列の上から表示される
                                        label: companyNames[5],
                                        borderColor: 'rgb(153, 102, 255)',
                                        backgroundColor: 'rgb(153, 102, 255)'
                                    },{
                                        data: amountsChunked[6], //販売金額の合計が配列の上から表示される
                                        label: companyNames[6],
                                        borderColor: 'rgb(201, 203, 207)',
                                        backgroundColor: 'rgb(201, 203, 207)'
                                    }],
                                    labels: this.years
                                },
                                options: {
                                    scales: {
                                        xAxes: [{
                                            stacked: true,
                                            xbarThickness: 16,
                                            scaleLabel: {
                                                display:true,
                                                labelString: "(年)",
                                          
                                            }
                                        }],
                                        yAxes: [{
                                            stacked: true,
                                            ticks: {
                                                // 目盛を千円単位にする
                                                callback: function(value, index, values) {
                                                    return (value/1000).toLocaleString();
                                                }

                                            },
                                            scaleLabel: {
                                                display:true,
                                                labelString: "(千円)",
                                            }
                                        }]
                                    },
                                    tooltips: { //tooltipsにツールチップに関する設定が含まれる
                                        callbacks: {  //ツールチップが表示されると、このコールバック関数が働きます。
                                            label(tooltipItem, data) { //ツールチップをラベルを設定するための引数であり変数
                                                console.log(data);
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
            },
            mounted() {
                this.getYears();
                this.getSales();
                this.getSales1();
                this.getSales2();
                this.getSales3();
            }
        });

    </script>
</body>
</html>