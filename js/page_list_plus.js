$(function(){
    $('DIV.sbs_plp_submitViaAjax').each(function(){
        var $PLP = $(this);
        $PLP.find("FORM").bind('submit', function(event){
            var $theForm = $(this);
            $theForm.fadeTo(1,.5);
            $PLP.find('.plp_form_loading').show();
            var formValues = $theForm.serialize();
            Concrete.event.publish('page_list_plus/submit', {vals:formValues, form:$theForm});
            return false;
        });
    });
    $('DIV.sbs_plp_receiveViaAjax').on('click', '.pagination A', function(){
        Concrete.event.publish('page_list_plus/paginate', this);
        return false;
    });
    Concrete.event.subscribe("page_list_plus/paginate", function(e, el) {
        var $el = $(el);
        var $PLP = $el.closest('.sbs_plp_receiveViaAjax');
        sbs_plp_ajaxGet($el.attr('href'), $PLP);
    });
    Concrete.event.subscribe("page_list_plus/submit", function(e, data) {
        $('DIV.sbs_plp_receiveViaAjax').each(function(){
            var $PLP = $(this);
            $PLP.attr('data-serialized',data.vals);
            var bID = $PLP.attr('data-bID');
            var cID = $PLP.attr('data-cID');
            var url = $PLP.find('.reloadURL').val()+"?bID="+bID+"&cID="+cID+"&ccm_paging_p=1&"+data.vals;

            sbs_plp_ajaxGet(url, $PLP);
        });
    });
});
function sbs_plp_ajaxGet(url, $PLP) {
    $.ajax({
        type: 'GET',
        url: url,
        error: function(resp, status, errorthrown) {
            var $submitViaAjax = $('DIV.sbs_plp_submitViaAjax');
            $submitViaAjax.find('FORM').fadeTo(.5,1);
            $submitViaAjax.find('.plp_form_loading').hide();
        },
        success: function(resp) {
            var $resp = $(resp);
            var templateResultAreas = ['.ccm-block-page-list-pages','.pagination'];
            for (var i=0;i<templateResultAreas.length;i++) {
                var $responseResults = $resp.find(templateResultAreas[i]);
                $PLP.find(templateResultAreas[i]).html($responseResults.children());
            }
            Concrete.event.publish('infinite_scroller/reset', $PLP);
            var $submitViaAjax = $('DIV.sbs_plp_submitViaAjax');
            $submitViaAjax.find('FORM').fadeTo(.5,1);
            $submitViaAjax.find('.plp_form_loading').hide();
        }		
    });	    
}