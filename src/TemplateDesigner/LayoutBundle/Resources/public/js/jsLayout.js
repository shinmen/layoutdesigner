var layout = {};
var node = "div";

function deleteElem(event,elem){
    elem.remove();
    event.stopPropagation();
}

function addLink(elem){
    elem.append('<a href="#" onclick="deleteElem($(this).parent()); return false;">delete</a>');
}

function addClassCol(elem,type){
    var l = $('.ui-selected').length;
    // $('#col').addClass('column col-'+type+'-'+l).text(l);
    $('#col').attr('data-class',$('#col').attr('data-class')+' col-'+type+'-'+l).show();
    // $('#col').addClass('column col-'+type+'-'+l);

}

function addClassVisible(elem,type){
    $('#col').addClass('visible-'+type+'-block');
}

function addClassHidden(elem,type){
    $('#col').addClass('hidden-'+type);
}

$(function() {

    // template creation
    var options_drag = {cursor: "move",revert:true};
    var options_drop = {
        hoverClass: "ui-state-hover",
        greedy: true,
        drop: function( event, ui ) {
            if(ui.draggable.attr('id')=="row"){
                ui.draggable.addClass('row droppable').attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                $(this).height($(this).height()+200+'px');
                $(this).parentsUntil('#root').each(function(){
                    $(this).height($(this).height()+200+'px');
                })

                $('.toolbox .containers').append('<div id="row" class="draggable">Row</div>');
            }else if(ui.draggable.attr('id')=="container"){
                ui.draggable.addClass('container droppable').attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                $('.toolbox .containers').append('<div id="container" class="draggable">Container</div>');
            }
            else{
                ui.draggable.addClass('droppable '+ui.draggable.attr('data-class')).attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                
                // add 50px height every two times
                if($(this).children().length %2 == 0){
                    $(this).height($(this).height()+100+'px');
                    $(this).parentsUntil('#root').each(function(){
                        $(this).height($(this).height()+100+'px');
                    })
                }

                $('#col').each(function(){
                    $(this).remove();
                })
                $('.toolbox .containers').append('<div style="display:none;" id="col" data-class="column" class="draggable">Column</div>');
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
        if(confirm('Do you wish to confirm?')){
            container = $('.container.root');
            rec(container,layout);
            var name = $('form input[name="name"]').val();
            var url = $('form').attr('action');
            $.ajax({
                type:'POST',
                url:url,
                data: {name:name, layout:layout}
            }).done(function(data){
                // console.log(data);
            })
        }

    })
});

// create template object
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