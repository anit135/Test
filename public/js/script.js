$(document).ready(function () {
  $('.page-link').click(function () {
    var show_page = $(this).data("pageNumber");
    var sort_by = $("input:checked").val();
    var active_link = $(this).parent();

    if (!!!show_page) {
      show_page = parseInt($(this).attr("data-next-page-number"));
      active_link = $("a[data-page-number=" + show_page + "]").parent();
    }
    if (!!!show_page) {
      show_page = parseInt($(this).attr("data-prev-page-number"));
      active_link = $("a[data-page-number=" + show_page + "]").parent();
    }
    if (!!!sort_by) {
      sort_by = null;
    }

    RefreshData(show_page, sort_by, active_link);

  });

  $("input[name='sort_by']").click(function () {
    var show_page = 1;
    var sort_by = $(this).val();
    var active_link = $("a[data-page-number=" + show_page + "]").parent();

    RefreshData(show_page, sort_by, active_link);

  });

  $("button[name='preview']").click(function () {
    $("#name").text($("input[name='user_name']").val());
    $("#email").text($("input[name='user_email']").val());
    $("#text").text($("textarea[name='task_text']").val());

    var file = $("input[type=file]")[0];
    if (file.files && file.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#img").attr('src', e.target.result);
        $("#img").css("display", "block");
      };
      reader.readAsDataURL(file.files[0]);
    } else {
      $("#img").css("display", "none");
    }

    $("#previewModal").modal('show');

  })
});

function RefreshData(show_page, sort_by, active_link) {
  $.ajax({
    url: "tasks/index",
    type: "GET",
    dataType: 'json',
    data: {page_now: show_page, sort_now: sort_by},
    success: function (data) {
      var tasks_element = $("#tasks");
      tasks_element.empty();
      var str = "";

      $.each(data.tasks, function (index, task) {
        str += "<div class='row'>";
        str += "<p class='col-md-12'>";
        str += task.user_name + " | " + task.user_email + " | ";
        if (task.task_status == 0) {
          str += "<i class='fa fa-question' aria-hidden='true'></i>";
        } else {
          str += "<i class='fa fa-check' aria-hidden='true'></i>";
        }
        str += "</p>";
        str += "<p class='col-md-12'>" + task.task_text + "</p>";
        if (task['task_img']) {
          str += "<p class='col-md-12'><img src='/public/img/" + task.task_img + "'></p>";
        }
        str += "</div><hr>";
        str += "</div>";
      });

      tasks_element.append(str);

      $(".page-item").removeClass("active");

      active_link.addClass("active");

      var link_next_page = $("a[data-next-page-number]");
      var link_prev_page = $("a[data-prev-page-number]");
      if (show_page >= data.count) {
        link_next_page.parent().addClass("disabled");
        link_prev_page.parent().removeClass("disabled");
        link_prev_page.attr("data-prev-page-number", show_page - 1);
      } else if (show_page <= 1) {
        link_next_page.parent().removeClass("disabled");
        link_prev_page.parent().addClass("disabled");
        link_next_page.attr("data-next-page-number", show_page + 1);
      } else {
        link_next_page.parent().removeClass("disabled");
        link_next_page.attr("data-next-page-number", show_page + 1);
        link_prev_page.parent().removeClass("disabled");
        link_prev_page.attr("data-prev-page-number", show_page - 1);
      }
    }
  });
}

