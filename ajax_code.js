$(document).ready(function(){
    //alert("Hiii");
    var db_name, table_name, srvr_name, param_name, funct_name;

    $("#database").change(function(){
        $("#chartContainer").empty();
        $("#table").empty();
        $("#server").empty();
        $("#parameter").empty();
        $("#function").empty();
        $("#table").append("<option value=0 disabled selected>-- Select Table --</option>");
        $("#server").append("<option value=0 disabled selected>-- Select Server --</option>");
        $("#parameter").append("<option value=0 disabled selected>-- Select Parameter --</option>");
        $("#function").append("<option value=0 disabled selected>-- Select Function --</option>");
        $("#function").append("<option value='sum'>SUM</option>");
        $("#function").append("<option value='avg'>AVG</option>");
        $("#function").append("<option value='std_dev'>STD_DEV</option>");
        $("#function").append("<option value='sum_of_deltas'>SUM_OF_DELTAS</option>");
        $("#function").append("<option value='avg_of_deltas'>AVG_OF_DELTAS</option>");
        $("#function").append("<option value='stddev_of_deltas'>STDDEV_OF_DELTAS</option>");



        db_name = $(this).val();
        //alert("Fetching Tables");
        //var jsonMimeType = "application/json;charset=UTF-8";
        $.ajax({
            url: './fetch_tables.php',
            type: "POST",
            // beforeSend: function(x) {
            //       if(x && x.overrideMimeType){
            //        x.overrideMimeType(jsonMimeType);
            //       }
            // },
            data: JSON.stringify({"db_name":db_name}),
            contentType: "application/json",
            dataType: JSON.stringify(),
            //data: JSON.stringify({"db_name":db_name}),
            
            success:function(response){
                //alert("Done fetching tables.");
                response = JSON.parse(response);
                //$("#table").empty();
                for(var key in response) {
                  //alert(key);
                  //alert(response[key]);
                  $("#table").append("<option value='"+response[key]+"'>"+response[key]+"</option>");
                }    

            },
            error:function(xhr){
                var jsonResponse = JSON.parse(xhr.responseText);
                $(".alert").html(jsonResponse.message);
            }
        });
        
    });

    $("#table").change(function(){
        $("#chartContainer").empty();
        $("#server").empty();
        $("#parameter").empty();
        $("#function").empty();
        $("#server").append("<option value=0 disabled selected>-- Select Server --</option>");
        $("#parameter").append("<option value=0 disabled selected>-- Select Parameter --</option>");
        $("#function").append("<option value=0 disabled selected>-- Select Function --</option>");
        $("#function").append("<option value='sum'>SUM</option>");
        $("#function").append("<option value='avg'>AVG</option>");
        $("#function").append("<option value='std_dev'>STD_DEV</option>");
        $("#function").append("<option value='sum_of_deltas'>SUM_OF_DELTAS</option>");
        $("#function").append("<option value='avg_of_deltas'>AVG_OF_DELTAS</option>");
        $("#function").append("<option value='stddev_of_deltas'>STDDEV_OF_DELTAS</option>");
        table_name = $(this).val();

        $.ajax({
            url: './fetch_servers.php',
            type: "POST",
            contentType: "application/json",
            dataType: JSON.stringify(),
            data: JSON.stringify({"db_name":db_name, "table_name":table_name}),
            
            success:function(response){
                //alert("Done fetching servers");
                response = JSON.parse(response);
                //$("#table").empty();
                for(var key in response) {
                  //alert(key);
                  //alert(response[key]);
                  $("#server").append("<option value='"+response[key]+"'>"+response[key]+"</option>");
                }    

            },
            error:function(xhr){
                alert("NO!!!!");
                var jsonResponse = JSON.parse(xhr.responseText);
                $(".alert").html(jsonResponse.message);
            }
        });
        
    });

    

    $("#server").change(function(){
        $("#chartContainer").empty();
        $("#parameter").empty();
        $("#function").empty();


        $("#parameter").append("<option value=0 disabled selected>-- Select Parameter --</option>");
        $("#function").append("<option value=0 disabled selected>-- Select Function --</option>");
        $("#function").append("<option value='sum'>SUM</option>");
        $("#function").append("<option value='avg'>AVG</option>");
        $("#function").append("<option value='std_dev'>STD_DEV</option>");
        $("#function").append("<option value='sum_of_deltas'>SUM_OF_DELTAS</option>");
        $("#function").append("<option value='avg_of_deltas'>AVG_OF_DELTAS</option>");
        $("#function").append("<option value='stddev_of_deltas'>STDDEV_OF_DELTAS</option>");

        srvr_name = $(this).val();

        $.ajax({
            url: './fetch_params.php',
            type: 'POST',
            contentType: "application/json",
            dataType: JSON.stringify(),
            data: JSON.stringify({"srvr_name":srvr_name,"table_name":$("#table").val(), "db_name":$("#database").val()}),
            
            success:function(response){
                //alert("Done fetching parameters");
                //alert("111111\n"+response);
                response = JSON.parse(response);
                //alert("2222222\n"+JSON.stringify(response));
                for(var key in response) {
                    //alert(key+" -> "+response[key]);
                   $("#parameter").append("<option value='"+response[key]+"'>"+response[key]+"</option>");
                }
            },
            error:function(xhr){
                //alert("ERROR");
                var jsonResponse = JSON.parse(xhr.responseText);
                $(".alert").html(jsonResponse.message);
            }
        });
        
    });

    $("#parameter").change(function(){
        $("#function").empty();
        $("#chartContainer").empty();
        $("#function").append("<option value=0 disabled selected>-- Select Function --</option>");
        $("#function").append("<option value='sum'>SUM</option>");
        $("#function").append("<option value='avg'>AVG</option>");
        $("#function").append("<option value='std_dev'>STD_DEV</option>");
        $("#function").append("<option value='sum_of_deltas'>SUM_OF_DELTAS</option>");
        $("#function").append("<option value='avg_of_deltas'>AVG_OF_DELTAS</option>");
        $("#function").append("<option value='stddev_of_deltas'>STDDEV_OF_DELTAS</option>");
        param_name = $(this).val();
        
    });

    $("#function").change(function(){
        //alert("1111111111");
        $("#chartContainer").empty();
        funct_name = $(this).val();
        if(db_name && table_name && srvr_name && param_name &&funct_name){
            //alert("222222222222222");
            drawGraph();
        }
    });

});



function drawGraph(){
    //alert("Drawing Graph");
    $.ajax({
            url: './eval_funct.php',
            type: 'POST',
            contentType: "application/json",//content being sent
            dataType: JSON.stringify(),//expected data
            data: JSON.stringify({"funct_name":$("#function").val(),srvr_name:$("#server").val(),param_name:$("#parameter").val(),"table_name":$("#table").val(), "db_name":$("#database").val()}),

            success:function(response){
                //alert("Done Evaluating Function");
                //alert("33333333\n"+response);
                response = JSON.parse(response);
                //alert("44444444\n"+response);
                var data_pts = [];
                var fun_val;
                var max_x = -1;
                var max_y = -1;
                for(var key in response){
                    // if(parseInt(key) > max_x){
                    //     max_x = parseInt(key);
                    // }
                    if(parseFloat(response[key]) > max_y){
                        max_y = parseFloat(response[key]);
                    }
                    if((key == "avg" )|| (key == "sum") || (key == "std_dev")
                        || (key == "sum_of_deltas") || (key == "avg_of_deltas")||(key == "stddev_of_deltas")){
                        fun_val = parseFloat(response[key]);
                        fun_val = fun_val.toFixed(5);
                        fun_val = parseFloat(fun_val);

                    }
                    else{
                        data_pts.push({x:parseInt(key), y:parseFloat(response[key])});
                    }
                }
                // x_interval = max_x/10;
                y_interval = max_y/5;
                var x_interval;
                if(data_pts.length < 50){
                    x_interval = 1;
                }
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,  
                    exportEnabled: true,
                    title:{
                        text: `${$("#function").val()} = ${fun_val}`
                    },
                    axisX: {
                        interval: x_interval,
                        minimum: 0,
                        title: "Number of Samples",
                        titleFontSize: 20,
                        titleFontColor: "red"
                    },
                    axisY: {
                        title: "Values",
                        titleFontSize: 20,
                        titleFontColor: "red",
                        stripLines: [{
                            color: "red",
                            thickness: 4,
                            value: fun_val,
                            labelFontColor:"red",
                            labelFontSize: 20,
                            label: $("#function").val(),

                        }]
                    },
                    data: [{
                        
                        type: "spline",
                        dataPoints: data_pts
                    }]
                });
                chart.render();
                chart.exportChart({format: "png"});

            },

            error:function(xhr){
                alert("ERROR");
                //alert(JSON.stringify(xhr));
                var jsonResponse = JSON.parse(xhr.responseText);

                $(".alert").html(jsonResponse.message);
            }
        });
}