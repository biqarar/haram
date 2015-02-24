route(/portal\/classes\/status=(add|edit)/, function(){
	var _self = this;
	$( ".select-plan", this).combobox();
	$( ".select-place", this).combobox();
	$("#teachername", this).sautocomplate();
	
});

route(/classes\/status\=done\/classesid\=\d+/, function(){
	$(".classes-done", this).click(function(){
		classesid = $(this).attr("classesid");
		$.ajax({
			type: "POST",
			url : "classes/status=setdone/classesid=" + classesid,

			success : function(data){
				if(data.fatal){
					xhr_error(data.fatal[0]);

				}else if(data.warn){
					xhr_warn(data.warn[0]);

				}else{
					xhr_true(data.true[0]);
				}
			}
		});
	});
});