// JavaScript Document
$(function(){
    var i = 0;
    var main = $("#sandbox");
    $('#service').focus();
    var availableTags = [
    "getTest"
    ];
    
    $( "#service" ).autocomplete({
        source: availableTags
    });
    
    
    $(".button").button();
    
    
    $( "#add" ).button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    });
    
    $( "#help" ).button({
        icons: {
            primary: "ui-icon-info"
        },
        text: false
    });
    $( "#help" ).tooltip({
        content: function(){
            
            if (!$("#service").val()){
                return "Type or select a Service name and come back, you will see the possible parameters for the service selected";
            }else{
                
                return "I lied, I will one day";
            }
        }
    });
           
    $("#params").delegate("button", "click", function(){
        $(this).parents('p').remove();
        i--;
    });
    

      
    $("#add").click(function(){
        var paramsDiv = $("#params");
        $('<p>'
            +'<input type="text" id="paramName'+i+'" size="20" value="" placeholder="Param name" /> '
            +'<input type="text" id="paramValue'+i+'" size="80" value="" placeholder="value" />'
            +'<select id="select'+i+'"><option name="text" value="text">text</option><option name="json" value="json">json</option></select>'
            +'<button class="button" id="rem'+i+'">-</button></p>').appendTo(paramsDiv);
        
        i++;
        return false;
         
    }); 
    $("#test").click(function(){
        var paramNameA = new Array();
        var paramValueA = new Array();
        var selectA = new Array();
        var params = '{';
        var j=0;
        var result = $("#result");
        
        if (!$("#service").val()){

            $("#service").css({
                'background-color' : 'red'
            });

        }else{
        
            result.html("<p><img src='images/loading.gif' alt='loading'  /></p>");
            $("#service").css({
                'background-color' : ''
            });
            while (j<i){
                var paramName = '#paramName'+j;
                var paramValue = '#paramValue'+j;
                var select = '#select'+j;
            
                paramNameA.push($(paramName).val());
                paramValueA.push($(paramValue).val());
                selectA.push($(select).val());
                j++;
            }
            for ( var k = 0, param; param = paramNameA[k]; k++ ) {
                if (selectA[k] == "text"){ 
                    params = params+ '"'+param+'":"'+paramValueA[k] +'",';
                }else if(selectA[k] == "json"){
                    params = params+ '"'+param+'":'+paramValueA[k]+",";
                }
            }
            if (j == 0){
                params = "";
            }else{
                var len = params.length;
                len--;
                params = params.substring(0,len) + '}';
                params = $.parseJSON(params);
            }
            $.post(
                '../services.php',
                {
                    exec: $("#service").val(),
                    params: params
                },function(data){
                    result.empty();
                    if (!data){
                        result.append("Nothing received"); 
                    }else{
                        try
                        {
                            var dataj = $.parseJSON(data);
                        }
                        catch(err)
                        {
                            result.append("<p>Error: </p>"+data+"<br/>");
                        }   

                        if (dataj.records != null){
                            result.append("<p>Result:</p>" + data+"<br/>");
                        }else{
                            result.append("<p>Json empty:</p>"+data+"<br/>");
                        }
                    }

                });
        }
    });   
 
});	