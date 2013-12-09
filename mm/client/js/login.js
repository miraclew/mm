function sw(id1, id2) {
    $('#'+id1).hide();
    $('#'+id2).show();
}

function start() {
	window.location.href = 'mm.php';
}

$(function(){
	$('#login').submit(function(e){
    	$.post('/mm/api/users.php?a=login',$("form").serialize(), function(data){
        	if(data.code != 0) {
            	alert('uid or password not correct');
            	return;
        	}
        	else {
        		start();
        	}
        });
    	e.preventDefault();
    });

	$('#register').submit(function(e){
    	$.post('/mm/api/users.php?a=register',$("form").serialize(), function(data){
        	console.log(data);
        	if(data.code != 0) {
            	alert(data.error);
        	}
        	else {
            	var profile = data.data;
            	sw('register','register_ok');
            	$('#ro_uid').html(profile.uid);
            	$('#ro_name').html(profile.name);
        	}
        });
    	e.preventDefault();
    });
	
})
