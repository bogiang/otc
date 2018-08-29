$(document).ready(function(){
	$('input').focus(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password'){
			t.css({'box-shadow':'none','border':'0','border-bottom':'1px solid #1798f2','color':'#333'});
		}
		if(t.val()==t.attr('placeholder'))
			t.val('');
	});
	$('input').blur(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password')
			t.css({'box-shadow':'none','border':'none','border-bottom':'1px solid #e6e6e6','color':'#333' });
		if(t.attr('type')!='password'&&!t.val())
			t.val(t.attr('placeholder'));
	});
});