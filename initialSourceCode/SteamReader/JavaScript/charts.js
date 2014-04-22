$(function () {
    $.barChart = function(barChart)
	{      
		var chart;
		var colWidth = 21;
		Highcharts.theme = 
		{
			colors:["#5fc4c5", "#FF0000", "#1E72C8", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee"],
			chart:{
					backgroundColor:{
						linearGradient:[0, 0, 0, 400],
						stops:[
								[0, 'rgb(96, 96, 96)'],
								[1, 'rgb(16, 16, 16)']
						]
					},
					borderWidth: 0,
					borderRadius: 0,
					plotBackgroundColor: null,
					plotShadow: false,
					plotBorderWidth: 0
			},
			title:{
				style: { 
					color: '#FFF',
					font: '1.5em "Segoe UI", Interstate,Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
				}
			},
			subtitle:{
				style: { 
					color: '#FFF',
					font: '12px "Segoe UI", Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
				}
			},
			xAxis:{
				gridLineWidth: 0,
				lineColor: '#999',
				tickColor: '#999',
				labels: {
					style: {
						color: '#FFF',
						font: '1.3em "Segoe UI"'
					}
				},
				title:{
					style:{
						color: '#FFF',
						font: 'normal 0em "Segoe UI", Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
					}                               
				}
			},
			yAxis:{
                alternateGridColor: null,
                minorTickInterval: null,
                gridLineColor: 'rgba(255, 255, 255, .1)',
                lineWidth: 0,
                tickWidth: 0,
                labels:{
					style:{
						color: '#999',
						fontWeight: 'normal',
						font: 'normal 1.2em "Segoe UI", Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
					}
                },
                title:{
					style:{
						color: '#AAA',
						font: 'normal 1.5em "Segoe UI", Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
					}                               
                },
			},
			legend:{
				enabled: false
			},
			labels:{
                style:{
					color: '#FFF'
                }
			},
			tooltip:{
                backgroundColor:{
					linearGradient: [0, 0, 0, 50],
					stops:[
						[0, 'rgba(96, 96, 96, .8)'],
						[1, 'rgba(16, 16, 16, .8)']
					]
                },
                borderWidth: 0,
                style:{
                        color: '#FFF'
                }
			},
			plotOptions:{
                line:{
					dataLabels:{
						color: '#CCC'
					},
					marker:{
						lineColor: '#333'
					}
                },
                spline:{
					marker:{
						lineColor: '#333'
					}
                },
                scatter:{
					marker:{
						lineColor: '#333'
					}
                },
                candlestick:{
					lineColor: 'white'
                }
			},
			toolbar:{
                itemStyle:{
					color: '#CCC'
                }
			},
			navigation: {
                buttonOptions: {
					backgroundColor: {
						linearGradient: [0, 0, 0, 20],
						stops: [
							[0.4, '#606060'],
							[0.6, '#333333']
						]
					},
					borderColor: '#000000',
					symbolStroke: '#C0C0C0',
					hoverSymbolStroke: '#FFFFFF'
                }
			},
			exporting: {
                buttons: {
					exportButton: {
						symbolFill: '#55BE3B'
					},
					printButton: {
						symbolFill: '#7797BE'
					}
                }
			},
			rangeSelector: {
                buttonTheme: {
					fill: {
						linearGradient: [0, 0, 0, 20],
						stops: [
							[0.4, '#888'],
							[0.6, '#555']
						]
					},
					stroke: '#000000',
					style: {
						color: '#FFF',
						fontWeight: 'bold'
					},
					states: {
						hover: {
							fill: {
								linearGradient: [0, 0, 0, 20],
								stops: [
									[0.4, '#BBB'],
									[0.6, '#888']
								]
							},
							stroke: '#000000',
							style: {
								color: 'white'
							}
						},
						select: {
							fill: {
								linearGradient: [0, 0, 0, 20],
								stops: [
									[0.1, '#000'],
									[0.3, '#333']
								]
							},
							stroke: '#000000',
							style: {
								color: 'yellow'
							}
						}
					}                                       
				},
                inputStyle: {
					backgroundColor: '#333',
					color: 'silver'
                },
                labelStyle: {
					color: 'silver'
                }
			},
			navigator: {
                handles: {
					backgroundColor: '#666',
					borderColor: '#AAA'
                },
                outlineColor: '#CCC',
                maskFill: 'rgba(16, 16, 16, 0.5)',
                series: {
					color: '#7798BF',
					lineColor: '#A6C7ED'
                }
			},
			scrollbar: {
                barBackgroundColor: {
					linearGradient: [0, 0, 0, 20],
					stops: [
						[0.4, '#888'],
						[0.6, '#555']
					]
				},
                barBorderColor: '#CCC',
                buttonArrowColor: '#CCC',
                buttonBackgroundColor: {
					linearGradient: [0, 0, 0, 20],
					stops: [
						[0.4, '#888'],
						[0.6, '#555']
					]
				},
                buttonBorderColor: '#CCC',
                rifleColor: '#FFF',
                trackBackgroundColor: {
					linearGradient: [0, 0, 0, 10],
					stops: [
						[0, '#000'],
						[1, '#333']
					]
                },
                trackBorderColor: '#666'
			},
			legendBackgroundColor: 'rgba(48, 48, 48, 0)',
			legendBackgroundColorSolid: 'rgb(70, 70, 70)',
			dataLabelsColor: '#FF00FF',
			textColor: '#FFFFFF',
			maskColor: 'rgba(255,255,0,0.3)'
		};

		Highcharts.setOptions(Highcharts.theme);
		
		var chartOptions = 
		{
            chart: {
                renderTo: barChart.container,
                type: 'bar'
            },
            title: {
                text: barChart.title
            },
            xAxis: {
                categories: barChart.xAxisCategory,
                title: {
                    text: barChart.xAxisCaption
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: barChart.yAxisCaption,
                    align: 'high'
                },
                labels: {
                    overflow: 'justify',
                },
				stackLabels: {
					style: {
						color: 'red'
					},
					enabled: true,
					verticalAlign: 'middle',
					align: 'left'
				}
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y + barChart.unit;
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
						color: 'white',
						y: 10,
						x: 5,
					    style: {
							fontSize: '1.2em'
						}
                    },
                    pointWidth: 32,
                    pointPadding: 0
				}
			},
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'bottom',
                x: -10,
                y: -60,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: false
            },
            credits: {
                enabled: false
            },
            series: barChart.dataValue
        }
  
		chart = new Highcharts.Chart(chartOptions);		
	}
});