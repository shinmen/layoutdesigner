var layout = {};
var node = "div";

function deleteElem(event,elem){
    elem.remove();
    event.stopPropagation();
}

function addLink(elem){
    elem.append('<a href="#" onclick="deleteElem($(this).parent()); return false;">delete</a>');
}

function alertColumn(text){
    $('.alert-success').html('added: '+text).show().fadeOut(8000);
}

function addClassCol(elem,type){
    var l = $('.ui-selected').length;
    $('#col').attr('data-class',$('#col').attr('data-class')+' col-'+type+'-'+l).show();
    alertColumn(type+l);
}

function addClassVisible(elem,type){
    $('#col').attr('data-class',$('#col').attr('data-class')+' visible-'+type+'-block').show();
    alertColumn(type+' visible');
}

function addClassHidden(elem,type){
    $('#col').attr('data-class',$('#col').attr('data-class')+' hidden-'+type).show();
    alertColumn(type+' hidden');
}

// create template object
function createLayout(container,obj){

    // obj.tag = container.get(0).nodeName;
    obj.tag = node;
    obj.cssClass = container.attr('class');
    obj.children = [];
        
    if(container.children().length > 0){
        container.children().each(function(){
            var child = {};
            var children = obj.children;
            children.push(child);
            createLayout($(this),child);
        })
    };
}

// -------------- Layout edition ------------------
function getSelectSubs(data,selector){
   $.ajax({
        type: 'POST',
        url: urlSelectSub,
        data: data,
    }).done(function(data){
        selector.html(data);
    }); 
}

function displaySubForm(data,selector){
    $.ajax({
        type: 'POST',
        url: urlDisplaySubForm,
        data: data,
    }).done(function(data){
        selector.html(data);
    });
}

function displayTemplate(data,selector){
   $.ajax({
        type: 'POST',
        url: urlDisplayTemplate,
        data: data,
    }).done(function(data){
        selector.html(data);
    }); 
}

function refreshSelectSubsAndTemplate(root){
   var $sub_selector = $('.childLayoutSelect');
    var data = {root: root};
    $.when(getSelectSubs(data,$sub_selector),displayTemplate(data,$('#showFullLayout'))).done(function(data1,data2){
        $('#editLayout').html('');
    }); 
}

function form_submit(elem){
    var values = elem.serialize();
    var url = elem.attr('action');
    $.ajax({
        type: 'POST',
        url: url,
        data: values,
    }).done(function(data){
        $('#editLayout').html(data);
        displayTemplate({root:$('.parentLayoutSelect option:selected').val()},$('#showFullLayout'))
    });
    return false; 
}

function form_submit_add_child(elem){
    var form = elem.closest('form');
    var parent = $('.childLayoutSelect').val();
    $.ajax({
        type: 'POST',
        url: urlFormSubmitAddChild,
        data: {parent:parent},
    }).done(function(data){
        refreshSelectSubsAndTemplate($(".parentLayoutSelect").val());
    });
    return false; 
}

$(function() {
    // Layout edition
    $(".parentLayoutSelect").on('change',function(e){
        refreshSelectSubsAndTemplate($(this).val());
    });
    $(".childLayoutSelect").on('change',function(e){
        var selected = $('option:selected',this).val();
        var data = {layout: selected};
        displaySubForm(data,$('#editLayout'))
    });

    

    $('.toolbox form select').on('change',function(){
        node = $('option:selected',$(this)).val();
    })

    // template creation
    var options_drag = {cursor: "move",revert:true};
    var options_drop = {
        hoverClass: "ui-state-hover",
        greedy: true,
        drop: function( event, ui ) {
            if(ui.draggable.attr('id')=="row"){
                ui.draggable.addClass('row droppable').attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                $(this).height($(this).height()+200+'px');
                $(this).parentsUntil($('.root'),'div').each(function(){
                    $(this).height($(this).height()+200+'px');
                })

                $('.toolbox .containers .container-row').append('<div id="row" class="draggable">Row</div>');
            }else if(ui.draggable.attr('id')=="container"){
                ui.draggable.addClass('container droppable').attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                $('.toolbox .containers .container-container').append('<div id="container" class="draggable">Container</div>');
            }
            else{
                ui.draggable.addClass('droppable '+ui.draggable.attr('data-class')).attr('ondblclick','deleteElem(event,$(this)); return false;').html('');
                $('.container-column div').attr('data-class','column');
                $('.btns .btn').show();
                
                // add 50px height every two times
                if($(this).children().length %2 == 0){
                    $(this).height($(this).height()+100+'px');
                    $(this).parentsUntil($('.root'),'div').each(function(){
                        $(this).height($(this).height()+100+'px');
                    })
                }

                $('#col').each(function(){
                    $(this).remove();
                })
                $('.toolbox .containers .container-column').append('<div style="display:none;" id="col" data-class="column" class="draggable">Column</div>');
            }
            $(this).append(ui.draggable);
            ui.draggable.removeAttr('id');
            ui.draggable.removeAttr('style');
            $('.draggable').draggable(options_drag);
            $('.droppable').droppable(options_drop);
        }
    }

    $( "#selectable" ).selectable();
    // columns
    $('.btns-primary .btn').on('click',function(){
        $(this).fadeOut(500);
        addClassCol($('.ui-selected'),$(this).attr('data-class'));
    })
    // hidden
    $('.btns-hidden .btn').on('click',function(){
        $(this).fadeOut(500);
        addClassHidden($('.ui-selected'),$(this).attr('data-class'));
    })
    // visible
    $('.btns-visible .btn').on('click',function(){
        $(this).fadeOut(500);
        addClassVisible($('.ui-selected'),$(this).attr('data-class'));
    })
    

    $('.draggable').draggable(options_drag);
    $('.droppable').droppable(options_drop);


    $('.btn-transform').on('click',function(){
        if(confirm($(this).attr('data-message'))){
            var $btn = $(this);
            container = $('.container.root');
            createLayout(container,layout);
            var name = $('form input[name="name"]').val();
            var url = $('form').attr('action');
            $.ajax({
                type:'POST',
                url:url,
                data: {name:name, layout:layout}
            }).done(function(data){
                if(data!=''){
                   $('.alert-danger').html();
                   $('.alert-danger').show().fadeOut(4000);
                   for(error in data){
                        $('.alert-danger').append('<div>'+data[error]+'</div>')
                    } 
                }else{
                    alertColumn($btn.attr('data-success'));
                }                
            })
        }

    })
});