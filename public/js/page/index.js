if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}


$(function() {
    "use strict";
 
    $.getJSON('/patientOverview/', function(response){
        //console.log(response);
        var options = {
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            colors: ['var(--chart-color1)'],
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Registration',
                type: 'column',
                data: [response[11].ptot, response[10].ptot, response[9].ptot, response[8].ptot, response[7].ptot, response[6].ptot, response[5].ptot, response[4].ptot, response[3].ptot, response[2].ptot, response[1].ptot, response[0].ptot]
            }],
            stroke: {
                width: [0, 2]
            },
            title: {
                text: '',
            },
            labels: [response[11].mname, response[10].mname, response[9].mname, response[8].mname, response[7].mname, response[6].mname, response[5].mname, response[4].mname, response[3].mname, response[2].mname, response[1].mname, response[0].mname],
            xaxis: {
                type: ''
            },
            yaxis: [{
                title: {
                    text: 'Patient Registration',
                },

            }]
        }
        var chart = new ApexCharts(document.querySelector("#patientOverview"), options);
        chart.render();
    });

    $.getJSON('/patientmonth/', function(response){        
        var arr = Object.values(response);
        var dayArr = []; var pArr = [];
        dayArr = arr.map(function(el){
            return el.day
        });
        pArr = arr.map(function(el){
            return el.pcount
        });        
        var options = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            colors: '#4FB8C9',
            grid: {
                yaxis: {
                    lines: {
                        show: false,
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: pArr,
                type: 'column',
                name: 'Number of Patients'
            }],
            xaxis: {
                categories: dayArr,
                title: {
                    text: 'Patient Count'
                }
            },
            yaxis: [{
                title: {
                    text: 'Days',
                },
            }]
        };
    
        var chart = new ApexCharts(
            document.querySelector("#patientmonth"),
            options
        );
        
        chart.render();
    });

    $.getJSON('/pharmacymonth/', function(response){  
        console.log(response);      
        var arr = Object.values(response);
        var dayArr = []; var pArr = [];
        dayArr = arr.map(function(el){
            return el.day
        });
        pArr = arr.map(function(el){
            return el.total
        });        
        var options = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            colors: '#4FB8C9',
            grid: {
                yaxis: {
                    lines: {
                        show: false,
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: pArr,
                type: 'column',
                name: 'Total'
            }],
            xaxis: {
                categories: dayArr,
                title: {
                    text: 'Total'
                }
            },
            yaxis: [{
                title: {
                    text: 'Days',
                },
            }]
        };
    
        var chart = new ApexCharts(
            document.querySelector("#pharmacyMonth"),
            options
        );
        
        chart.render();
    });

    $.getJSON('/pharmacyoutmonth/', function(response){  
        console.log(response);      
        var arr = Object.values(response);
        var dayArr = []; var pArr = [];
        dayArr = arr.map(function(el){
            return el.day
        });
        pArr = arr.map(function(el){
            return el.total
        });        
        var options = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            colors: '#4FB8C9',
            grid: {
                yaxis: {
                    lines: {
                        show: false,
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: pArr,
                type: 'column',
                name: 'Total'
            }],
            xaxis: {
                categories: dayArr,
                title: {
                    text: 'Total'
                }
            },
            yaxis: [{
                title: {
                    text: 'Days',
                },
            }]
        };
    
        var chart = new ApexCharts(
            document.querySelector("#pharmacyOutMonth"),
            options
        );
        
        chart.render();
    });

    $.getJSON('/incomeexpense/', function(response){        
        var arr = response;
        var incArr = []; var expArr = []; var dayArr = [];
        incArr = arr.map(function(el){
            return el.income
        });
        expArr = arr.map(function(el){
            return el.expense
        });
        dayArr = arr.map(function(el){
            return el.day
        });
        var options = {
            series: [{
                name: 'Income',
                data: incArr
            }, {
                name: 'Expense',
                data: expArr
            }],
            colors: ['#4FB8C9', '#FFA500'],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    //endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['transparent']
            },
            xaxis: {
                categories: dayArr,
            },
            yaxis: {
                title: {
                    text: '₹'
                }
            },
            legend: {
                position: 'bottom', // left, right, top, bottom
                horizontalAlign: 'left',  // left, right, top, bottom
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                    return "₹ " + val
                    }
                }
            },
        };
        new ApexCharts(document.querySelector("#incomeexpense"), options).render();
    });

    $.getJSON('/reviewmonth/', function(response){        
        var arr = response;
        var dayArr = []; var pArr = [];
        dayArr = arr.map(function(el){
            return el.day
        });
        pArr = arr.map(function(el){
            return el.pcount
        });        
        var options = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            colors: '#FFA500',
            grid: {
                yaxis: {
                    lines: {
                        show: false,
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: pArr,
                type: 'column',
                name: 'Number of Reviews'
            }],
            xaxis: {
                categories: dayArr,
                title: {
                    text: 'Patient Count'
                }
            },
            yaxis: [{
                title: {
                    text: 'Days',
                },
            }]
        };
    
        var chart = new ApexCharts(
            document.querySelector("#reviewmonth"),
            options
        );
        
        chart.render();
    });
});
