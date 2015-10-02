// This file contains script to plot a bar graph on the 'Visualize' view. Value have been hard coded by can be made dynamic.

             var chart;

            var chartData = [
                {
                    "country": "Zimbabwe",
                    "visits": 1
                },
                {
                    "country": "New York",
                    "visits": 1
                },
                {
                    "country": "North Carolina",
                    "visits": 3
                },
                {
                    "country": "UK, Unknown",
                    "visits": 1
                },     
                {
                    "country": "Baden-Württemberg",
                    "visits": 2
                },
                 {
                    "country": "Unknown",
                    "visits": 0
                }                      

            ];


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "country";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 0;
                categoryAxis.gridPosition = "start";

                // value
                // in case you don't want to change default settings of value axis,
                // you don't need to create it, as one value axis is created automatically.

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.balloonText = "[[category]]: <b>[[value]]</b>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                chart.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-right";

                chart.write("chartdiv");
            });
        