var app = {};
app.chats = {};

function create_chat(members) {
	$.post('/mm/api/chats.php?a=create',{'members':members}, function(data){
    	if(data.code != 0) {
        	console.log(data.error);
    	}
       	var chat = data.data.chat;
       	app.chats[chat.id] = chat;
    	active_chat(chat.id);
       	console.log(chat);
    });
}

// active tab if exist, create otherwise
function active_chat(chatid) {
	var li = $('#chat-tab-'+chatid);
	var chat_tab_id = '#chat-tab-'+chatid+' a';
	var chat_content_id = 'chat-'+chatid;
	
	if(li.length > 0) {
		$(chat_tab_id).click();
	}
	else {
		// insert new tab
		var chat = app.chats[chatid];
		var chat_tab = $('#chat-tab').append(
				'<li id="chat-tab-'+chatid+'"><a href="#chat-'+chatid+'"><img class="avatar" src="'+chat.avatar+'">'+chat.title+'</a></li>'
				);
		$('#chat').append(
			'<div class="tab-pane" id="chat-'+chatid+'">'+
				'<div class="messages"></div>' +
				'<input id="" class="chat_input">' +
				'<a class="btn">发送</a>' +
			'</div>');
		$(chat_tab_id).click();
	}
	
	return {'tab_id': chat_tab_id, 'content_id': chat_content_id };
}

function append_message(el, msg) {
	var html = '<p>'+ '<img class="avatar" src="'+msg.user.avatar+'"> ';

	if(msg.media_type == 'image/*') {
		html += '<img src="'+msg.media_url+'"> ';
	}
	else if(msg.media_type == 'audio/*') {
		html += '<audio controls="controls" src="'+msg.media_url+'" autoplay="autoplay"></audio>';
	}
	else if(msg.media_type == 'text/plain') {
		html += msg.text;
	}
	else if(msg.media_type == 'file/*') {
		html += '<a href="'+msg.media_url+'" target="_blank">'+ msg.text +'</a>';
	}
	
	html += '</p>'; 

	if(typeof(el) == 'string') {
		$('#'+el).append(html);
	}
	else {
		el.append(html);
	}
	
	return html;
}

function upload(form, loader){
    //only do this if the form exists
    if(form){
        //display a loadbar
        //loader.innerHTML = 'loading.gif';
    	var media_type = 'file/*';
    	var s1 = form.media.value.split('\\');
    	var filename = s1[s1.length-1];
    	var s2 = form.media.value.split('.');
    	var ext = s2[s2.length-1];
    	if(['jpg','png','gif'].indexOf(ext) >= 0) {
    		media_type = 'image/*';
    	}
    	else if(['mp3','m4r','m4a','caf'].indexOf(ext) >= 0) {
    		media_type = 'audio/*';
    	}
    	var chatid = form.chatid.value;
    	form.media_type.value = media_type;
    	form.text.value = filename;
    	
    	var msg = { 'user': {'avatar':user.avatar}, 'text':'','media_type':media_type,'chatid': form.chatid.value};
    	var div_pair = active_chat(msg.chatid);
		append_message(div_pair.content_id + ' .messages', msg);
//		$(form).submit(function(data){
//			console.log(data);
//		});
		
        form.submit();
    }
}

function add_friend() {
	var uid = $("#search_result form input[name=uid][type=radio]:checked").val();
	console.log(uid);
	$.post('/mm/api/friends.php?a=create',{'uid': uid}, function(data){
    	console.log(data);
    	if(data.code != 0) {
    	}
    	else {
    	}
    });
}

$(function() {
	$(document).on('keyup','.chat_input',function(e){
		if(e.keyCode == 13)
		{
		  $(this).trigger("enterKey");
		}
	});
	
	$(document).on("enterKey",'.chat_input',function(e){
		var input = $(this);
		var messages_div = input.siblings('.messages');
		var parent_id = input.parent().attr('id');
		var chatid = parent_id.split('-')[1];
		var msg = { 'user': {'avatar':user.avatar}, 'text':input.val(),'media_type':'text/plain','chatid': chatid};
		append_message(messages_div, msg)
		input.val('');
		
		// send message
		$.post('/mm/api/chats.php?a=message_send', msg, function(data) {
			if (data.code != 0) {
				console.log('send message error')
			}
		});
	});
	
	$('ul.friends li').click(function(e) {
		var uid = $(this).attr('data-uid');
		create_chat(uid);
	});
	
	$('ul.nav-tabs').on('click', 'a',function(e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#logout').click(function(e) {
		$.post('/mm/api/users.php?a=logout', {}, function(data) {
			if (data.code != 0) {
				console.log('logout error')
			}

			window.location.href = 'index.php';
		});
		e.preventDefault();
	});
	
	$('#search_user').submit(function(e){
		var values = $('#search_user').serialize();
		$('#search_result').load('friends.php?'+values);
    	e.preventDefault();
    });
	
	//connect ws
	var ws = new WebSocket(ws_url);
	ws.onmessage = function(e){
		var msg = $.parseJSON(e.data);
		console.log(msg);
		var chatid= msg.chatid;
		
		if(typeof(app.chats[chatid]) == 'undefined') {
			// pull chat info first
			$.get('/mm/api/chats.php?a=show',{'chatid':chatid}, function(data){
		    	if(data.code != 0) {
		        	console.log(data.error);
		    	}
		       	var chat = data.data.chat;
		       	app.chats[chat.id] = chat;
				var div_pair = active_chat(chatid);
				append_message(div_pair.content_id + ' .messages', msg);
		       	console.log(chat);
		    });
		} else {
			// find the chat tab
			var div_pair = active_chat(chatid);
			append_message(div_pair.content_id + ' .messages', msg);
		}
	};
	ws.onopen = function(){
		console.log('ws connection open');
	};
	
	ws.onclose = function(){
		console.log('ws connection closed');
	};
	
	ws.onerror = function(){
		console.log('ws connection error');
	};

});
