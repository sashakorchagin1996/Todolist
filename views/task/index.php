<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="box box-aqua">
      <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">To Do List</h3>
      </div>

      <div class="box-body">
        <ul class="todo-list ui-sortable">
        	<?php foreach ($tasks as $key => $task): ?>
          <li id="li-<?php echo $task->id; ?>">
            <?php $ch = $task->checked ? "checked" : "" ?>
            <input type="checkbox" name="checkbox" <?php echo $ch; ?>>
            <?php $addclass = $task->checked ? "checked_task" : "";  ?>
            <span class="text <?php echo $addclass; ?>"><?php echo $task->task; ?></span>
            <div class="tools">
              <i class="fa fa-trash-o"> </i>
            </div>
          </li> 
      <?php endforeach; ?>
        </ul>
      </div>
      <div class="box-footer clearfix no-border">
        <div class="row">
          <div class="col-md-8">
            <input type="text" class="form-control" id="addTaskText">
          </div>
          <div class="col-md-3 col-md-offset-1">
            <button type="button" id="addTaskBtn" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Добавить задачу</button>
          </div>
        </div>
      </div>
    </div>
  </div> 
  
 
</div>
<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).on('click', '.fa-trash-o', function(e) {
		var li = $(e.target).parents().eq(1);
		var task_id = li.attr('id').split("-")[1]; // дергаем id
		$.ajax({
            url: 'index.php?r=task/remove',
            method: 'POST',
            data: {id: task_id}
        }).done(function(errors) {
            if(errors.length > 0) {
                alert(errors[0]);
            } else {
                li.remove();
            }
        });
	});
  $(document).on('click', '[name=checkbox]', function(e) {
    var li = $(e.target).parents().eq(0);
    var task_id = li.attr('id').split("-")[1]; // дергаем id
    if ($(this).is(":checked")){
      $.ajax({
          url: 'index.php?r=task/checked',
          method: 'POST',
          data: {
                id: task_id,
                checked: 1
          }
      }).done(function(errors) {
          if(errors.length > 0) {
              alert(errors[0]);
          } else {
              var ul = $(".todo-list");
              li.children(".text").addClass("checked_task");
              var tmp = li;
              li.remove();
              ul.append(tmp);
          }
      });
    }
    else{
      $.ajax({
          url: 'index.php?r=task/checked',
          method: 'POST',
          data: {
                id: task_id,
                checked: 0
          }
      }).done(function(errors) {
          if(errors.length > 0) {
              alert(errors[0]);
          } else {
              var ul = $(".todo-list");
              li.children(".text").removeClass("checked_task");
              var tmp = li;
              li.remove();
              ul.prepend(tmp);
          }
      });
    }
  });
  $("#addTaskBtn").click(function(e){
     if(!$("#addTaskText").is(":visible")) {
        $("#addTaskText").show();
        $(e.target).text('Сохранить'); 
     } else {
        var text = $("#addTaskText").val().trim();
        $.ajax ({
          url: 'index.php?r=task/add',
          method: 'POST',
          data: {
                task: text
          }
        }).done(function(data){
          if(data['errors'].length > 0) {
            alert(data['errors'][0]);
          } else {
            var ul = $(".todo-list");
            var li = '<li id="li-'+data['id']+'"><span class="handle ui-sortable-handle"> \
                        <i class="fa fa-ellipsis-v"></i> \
                        <i class="fa fa-ellipsis-v"></i> \
                      </span> \
                      <input type="checkbox" name="checkbox"> \
                        <span class="text">'+data['task']+'</span> \
                        <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small> \
                        <div class="tools"> \
                          <i class="fa fa-edit"></i> \
                          <i class="fa fa-trash-o"> </i> \
                      </div>';
            ul.prepend(li);
          }
        });
        $("#addTaskText").hide();
        $("#addTaskText").val("");
        $(e.target).text('Добавить задачу'); 
     }
  });
  $("#searchT").click(function(e){
    var text = $("#search_text").val().trim();
    $.ajax ({
          url: 'index.php?r=task/index',
          method: 'POST',
          data: {
                search: text
          }
        })
  });
</script>
