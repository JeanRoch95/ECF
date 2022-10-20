
$(document).ready(function(){
    $('#form_search').keyup(function(){
        $('#result-search').html('');
        var product=$(this).val();
        if(product !== ""){
            $.ajax({
                type:'GET',
                url:    url,
                data:   {recherche : val},
                success: function(data){
                    if(data !== ""){
                        $('#result-search').append(data);
                    }else{
                        document.getElementById('result-search').innerHTML ="<div style='font-size: 20px; text-align:center;margin-top:10px'>Aucun produit trouvé</div>"
                    }
                }
            });
        }
    });
});
