
"use strict";

    var searchProcess = {
        'url' : '',
        'loadingImage' : 'css/loading.gif',
        'searchResult' : function(){
            $("input[name=searchKeys]").keyup(function () {
            var inputVal = $(this).val();
            
            if ( inputVal.length >= 2 ) {
                $("#searchResult").show().html('<div id="loadingImage"><img src="'+searchProcess.loadingImage+'"></div>');
                $(this).parent().css({position: 'relative'});
                $.ajax({
                    url: searchProcess.url,
                    type: "GET",
                    data: {search: inputVal},
                    success: function (data, textStatus, jqXHR) {
                        if ( data && data.error ) {
                            
                            $("#searchResult").html(data.error);
                            
                        }//if (data && data.error)
                        else if ( data.search_result ) {
                            
                            $("#searchResult").html(data.search_result);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });

                return false;

            }//if($(this).val().length >= 3)
            else {
                $("#searchResult").empty().hide();
                return false;
            }
        });//$("input[name=search_merchant]").keyup(function ()
        }
    };
