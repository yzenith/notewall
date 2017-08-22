$(function(){
	
	$('.btn-wish').click(function(){
		$('.wish-form').toggle();
	})
	// close click and hide
	$('.close').click(function(){
		$(this).parents('.ul-wish').hide();
	})
	
	//for-close click and hide
	$('.form-close').click(function(){
		$('.wish-form').hide();
	})
	
	//wish-note can draggable
		$('.ul-wish').draggable();
	

	//wish-form ajax_add_msg
	$('.btn-submit').click(function(){
		var data = $('form[name=wish-form]').serialize();
		$.ajax({
			url : APP + "?a=ajax_add_msg",
			data: data,
			type: "POST",
			dataType: "JSON",
			success:function(data){
				if(data.status){
					var str = '<ul class="list-group ul-wish">\
								  <li class="list-group-item list-group-item-danger">Your lucky number: '+data.lucky+'<span class="glyphicon glyphicon-remove close" style="float:right"></span></li>\
								  <li class="list-group-item list-group-item-success">Title: '+data.title+'</li>\
								  <li class="list-group-item list-group-item-info"><span style="display:inline-block">Avator:</span><img style="width:20px;height:20px;display:inline-block" class="img-responsive img-circle" src="'+PUBLIC+"/images/"+data.avator+'"></li>\
								  <li class="list-group-item list-group-item-warning">'+data.content+'</li>\
								  <li class="list-group-item list-group-item-danger">Published on:'+data.time+'</li>\
								</ul>';
					$('.space').append(str);
					$('.wish-form').hide(); //submit form will gone after submit
					$('.ul-wish').draggable();
					$('.wish-form>input').val('');
					$('.wish-form>textarea').val('');
				}else{
					alert("Sorry,I don't get it");
				}
				
			}
		})
	})
	
	// function area
	//random show up  why it not working if I try to use function
	/*function pos(obj){
		var FW = $('.spcae').width();
		var FH = $('.space').height();
		obj.css({
			left : parseInt( Math.floor( Math.random() * ( FW - obj.width() ) ) ) + 'px';
			top : parseInt( Math.floor( Math.random() * ( FH - obj.height() ) ) ) + 'px';
		});
	}*/
})




