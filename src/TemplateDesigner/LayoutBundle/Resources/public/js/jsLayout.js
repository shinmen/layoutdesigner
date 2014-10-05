var layout = {};
var node = "div";
var buttons = {};
function deleteElem(elem){
	elem.remove();
}
function addLink(elem){
	elem.append('<a href="#" onclick="deleteElem($(this).parent()); return false;">delete</a>');
}

function addClassCol(elem,type){
	var l = $('.ui-selected').length;
    $('#col').addClass('column col-'+type+'-'+l).text(l);
}

function addClassVisible(elem,type){
    $('#col').addClass('visible-'+type+'-block');
}

function addClassHidden(elem,type){
    $('#col').addClass('hidden-'+type);
}

function delElem(elem){
	elem.remove();
}

 $(function() {
 	var options_drag = {cursor: "move",revert:true};
 	var options_drop = {
 	   	hoverClass: "ui-state-hover",
 	   	greedy: true,
		drop: function( event, ui ) {
			if(ui.draggable.attr('id')=="row"){
				ui.draggable.addClass('row droppable').attr('ondblclick','delElem($(this)); return false;').html('');
				$(this).height($(this).height()+200+'px');
				$(this).parentsUntil('#root').each(function(){
					$(this).height($(this).height()+200+'px');
				})

				$('.toolbox .containers').append('<div id="row" class="draggable">Row</div>');
			}else if(ui.draggable.attr('id')=="container"){
				ui.draggable.addClass('container droppable').attr('ondblclick','delElem($(this)); return false;').html('');
				$('.toolbox .containers').append('<div id="container" class="draggable">Container</div>');
			}
			else{
				ui.draggable.addClass('droppable').attr('ondblclick','delElem($(this)); return false;').html('');
				
				// add 50px height every two times
				if($(this).children().length %2 == 0){
					$(this).height($(this).height()+50+'px');
					$(this).parentsUntil('#root').each(function(){
						$(this).height($(this).height()+50+'px');
					})
				}

				$('#col').each(function(){
					$(this).remove();
				})
				$('.toolbox .containers').append('<div id="col" class="draggable"></div>');
			}
			$(this).append(ui.draggable);
			ui.draggable.removeAttr('id');
			ui.draggable.removeAttr('style');
			$('.draggable').draggable(options_drag);
			$('.droppable').droppable(options_drop);
		}
	}
    $( "#selectable" ).selectable({
        selected: function( event, ui ) {
            // selectedTab.push(ui.selected);
        },
    });

    $('form select').on('change',function(){
    	node = $('form select option:selected').val();
    })

    $('.btn-ok-m').on('click',function(){
    	addClassCol($('.ui-selected'),'xs');
    })
    $('.btn-ok-t').on('click',function(){
    	addClassCol($('.ui-selected'),'sm');
    })
    $('.btn-ok-d').on('click',function(){
    	addClassCol($('.ui-selected'),'md');
    })
    $('.btn-ok-ld').on('click',function(){
    	addClassCol($('.ui-selected'),'lg');
    })

    $('.btn-ok-hm').on('click',function(){
    	addClassHidden($('.ui-selected'),'xs');
    })
    $('.btn-ok-ht').on('click',function(){
    	addClassHidden($('.ui-selected'),'sm');
    })
    $('.btn-ok-hd').on('click',function(){
    	addClassHidden($('.ui-selected'),'md');
    })
    $('.btn-ok-hld').on('click',function(){
    	addClassHidden($('.ui-selected'),'lg');
    })

    $('.btn-ok-vm').on('click',function(){
    	addClassVisible($('.ui-selected'),'xs');
    })
    $('.btn-ok-vt').on('click',function(){
    	addClassVisible($('.ui-selected'),'sm');
    })
    $('.btn-ok-vd').on('click',function(){
    	addClassVisible($('.ui-selected'),'md');
    })
    $('.btn-ok-vld').on('click',function(){
    	addClassVisible($('.ui-selected'),'lg');
    })

    $('.draggable').draggable(options_drag);
    $('.droppable').droppable(options_drop);


    $('.btn-transform').on('click',function(){
    	container = $('.container.root');
    	rec(container,layout);
    	var name = $('form input[name="name"]').val();
    	console.log(layout);
    	$.ajax({
    		url:"{{path('layout_transform')}}",
    		data: {name:name, layout:layout}
    	}).done(function(data){
    		console.log(data);
    	})
    })
});

function rec(container,obj){

		// obj.tag = container.get(0).nodeName;
		obj.tag = node;
		obj.cssClass = container.attr('class');
		obj.children = [];
		
		if(container.children().length>0){
			container.children().each(function(){
				var child = {};
				var children = obj.children;
				children.push(child);
				rec($(this),child);
			})
		};
}