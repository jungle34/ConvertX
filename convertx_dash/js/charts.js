$(document).ready(async function(){
    await actionTypeChart();

    await clicksPerSite();

    await devicesChart();
});

function checkToken(){
    if(TOKEN){
        return TOKEN
    }else{
        return false
    }
}


async function actionTypeChart(){
    var chart = am4core.create("chartdiv", am4charts.XYChart);
    // Add data to the chart
    chart.data = await getChartData('actionType');

    // Create X and Y axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "category";
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create a series for the chart
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "value";
    series.dataFields.categoryX = "category";

    // Add a cursor to the chart
    chart.cursor = new am4charts.XYCursor();
}

async function clicksPerSite(){
    let data = await getChartData('clicksPerSite');
    
    $.each(data, function(field, val){
        
        let row = `
            <tr>
                <td><a href="${field}">${field}</a></td>
                <td class="text-center">${val.val}</td>
            </tr>`

        $(row).appendTo('#tbody_dash');
    })
}

async function getChartData(type){
    let url = `https://localhost/convertx/api/Dashboard.php?function=${type}`
    let data = await requestData(url);

    if(data){
        return data;
    }
}

async function requestData(url){
    let token = checkToken();
    let response = await $.ajax({url, method: 'GET', headers: {'Authorization': `Bearer ${token}`}});

    if(response){
        if(response.TYPE == 'SUCCESS'){
            return response.RESULTS;
        }
    }
    
}

async function devicesChart(){
    var chart = am4core.create("piechart", am4charts.PieChart);
    let data = await getChartData('devicesChart');
    console.log(data)
    // Add data
    chart.data = data;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "title";

}