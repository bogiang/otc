$(document).ready(function(){
	$('input').focus(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password'){
			t.css({'box-shadow':'none','border':'0','border-bottom':'none','color':'#999','line-height':'40px;'});
		}
		if(t.val()==t.attr('placeholder'))
			t.val('');
	});
	$('input').blur(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password')
			t.css({'box-shadow':'none','border':'0','border-bottom':'none','color':'#999','line-height':'40px;' });
		if(t.attr('type')!='password'&&!t.val())
			t.val(t.attr('placeholder'));
	});
});