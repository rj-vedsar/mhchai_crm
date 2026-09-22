const path_get = "./config/get.php";
const path_set = "./config/set.php";

/* Sweet Alert swal function Function */
function swaMsg(icon, message, bg) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: icon,
    title: message,
    color: bg,
    customClass: {
      title: "font-13",
    },
    background: "#fff",
  });
}

function inval(clas, color) {
  $(clas).css("color", color);
}

/* Validation Functions */
function validateInput(id, cls, validationFn) {
  let input;

  $(document).on("input", id, function () {
    input = $(this).val();
    const validation = validationFn(input);

    $(cls).text(validation.message);
    inval(cls, validation.color);

    if (validation.isValid) {
      return input;
    }

    inval(cls, "#a90228");
  });
}

const isEmailValid = (input) => {
  if (input == "") {
    return {
      isValid: false,
      message: "Please enter your email address.",
      color: "#a90228",
    };
  } else if (
    !input.match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    )
  ) {
    return {
      isValid: false,
      message: "Please enter your correct email address.",
      color: "#a90228",
    };
  } else {
    return {
      isValid: true,
      message: "",
      color: "#0F843F",
    };
  }
};

const isPassValid = (input) => {
  if (input == "") {
    return {
      isValid: false,
      message: "Please enter your password.",
      color: "#a90228",
    };
  } else if (input.length < 6) {
    return {
      isValid: false,
      message: "Minimum 6 characters allowed.",
      color: "#a90228",
    };
  } else if (input.length > 15) {
    return {
      isValid: false,
      message: "Maximum 15 characters allowed.",
      color: "#a90228",
    };
  } else {
    return {
      isValid: true,
      message: "",
      color: "#0F843F",
    };
  }
};

const isNameValid = (input) => {
  if (input == "") {
    return {
      isValid: false,
      message: "Please enter your name.",
      color: "#a90228",
    };
  } else if (input.length < 3) {
    return {
      isValid: false,
      message: "Name should be minimum 3 characters.",
      color: "#a90228",
    };
  } else if (!input.match(/^[a-zA-Z\s]*$/)) {
    return {
      isValid: false,
      message: "Name should only contain letters and spaces.",
      color: "#a90228",
    };
  } else {
    return {
      isValid: true,
      message: "",
      color: "green",
    };
  }
};

/* Usage of Validation Functions */
// validateInput("#ad_user_email", ".ad_user_email", isEmailValid);
// validateInput("#ad_user_pass", ".ad_user_pass", isPassValid);
// validateInput("#ad_user_name", ".ad_user_name", isNameValid);

/* Other Functions */
function loadTable(page_no, table_id, funName, custom = "", custom2 = "") {
  $.ajax({
    url: path_set,
    type: "POST",
    data: { page_no: page_no, data: funName, custom: custom, custom2: custom2 },
    success: function (data) {
      $(table_id).html(data);
      setTimeout(() => {
        hide_spinner(table_id);
      }, 500);
    },
  });
}

function load_pagination(
  pagination_id,
  table_id,
  funName,
  custom = "",
  custom2 = "", forSupport = false
) {
  $(document).on("click", pagination_id, function (e) {
    e.preventDefault();
    let page_id = $(this).attr("id");
    show_spinner(table_id);
    custom = custom;
    if (forSupport) { custom = $("#attdmonthly").val(); }
    custom2 = custom2;
    loadTable(page_id, table_id, funName, custom, custom2);
  });
}
/* USAGE */
/*  load_pagination("#alertPagination .page", "#alertRecordsId", 'load_alert_records');
    $.ajax({
        url: path_set,
        type: "POST",
        data: {
            page_no: 1,
            data: "load_alert_records"
        },
        success: function(data) {
            $('#alertRecordsId').html(data);
            filterTable('searchTr', '#alertRecordsId');

        },
    }); */
function check_status(button_class, table_name_tb) {
  $(document).on("click", button_class, function () {
    if ($(this).prop("checked") == true) {
      let active = $(this).data("checkid");
      let table_name = table_name_tb;
      let check_status = "check_status";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { active: active, table_name: table_name, data: check_status },
        success: function (data) {
          let json11 = JSON.parse(data);
          if (json11.status == 1) {
            swaMsg("success", json11.msg, "#0F843F");
          } else if (json11.status == 2) {
            swaMsg("error", json11.msg, "#a90228");
          }
        },
      });
    } else if ($(this).prop("checked") == false) {
      let inactive = $(this).data("checkid");
      let table_name = table_name_tb;
      let check_status = "check_status";
      $.ajax({
        url: path_set,
        type: "POST",
        data: {
          inactive: inactive,
          table_name: table_name,
          data: check_status,
        },
        success: function (data) {
          let json12 = JSON.parse(data);
          if (json12.status == 3) {
            swaMsg("success", json12.msg, "#0F843F");
          } else if (json12.status == 4) {
            swaMsg("error", json12.msg, "#a90228");
          }
        },
      });
    }
  });
}


function checkbx_status(button_class) {
  $(document).on("click", button_class, function () {
    if ($(this).prop("checked") == true) {
      let active = $(this).data("checkid");
      let itemid = $(this).data("itemid");
      let orderid = $(this).data("orderid");
      
      let checkbx_status = "checkbx_status";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { active: active,  data: checkbx_status, orderid: orderid,
          itemid: itemid },
        success: function (data) {
          let json11 = JSON.parse(data);
          if (json11.status == 1) {
            swaMsg("success", json11.msg, "#0F843F");
          } else if (json11.status == 2) {
            swaMsg("error", json11.msg, "#a90228");
          }
        },
      });
    } else if ($(this).prop("checked") == false) {
      let inactive = $(this).data("checkid");
      let itemid = $(this).data("itemid");
      let orderid = $(this).data("orderid");
      let checkbx_status = "checkbx_status";
      $.ajax({
        url: path_set,
        type: "POST",
        data: {
          inactive: inactive,
          orderid: orderid,
          itemid: itemid,
          data: checkbx_status,
        },
        success: function (data) {
          let json12 = JSON.parse(data);
          if (json12.status == 3) {
            swaMsg("success", json12.msg, "#0F843F");
          } else if (json12.status == 4) {
            swaMsg("error", json12.msg, "#a90228");
          }
        },
      });
    }
  });
}


function check_status1(button_class, table_name_tb, type) {
  $(document).on("click", button_class, function () {
    if ($(this).prop("checked") == true) {
      let active = $(this).data("checkid");
      let table_name = table_name_tb;
      let check_status = "check_status1";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { active: active, table_name: table_name, data: check_status, type: type },
        success: function (data) {
          let json11 = JSON.parse(data);
          if (json11.status == 1) {
            swaMsg("success", json11.msg, "#0F843F");
          } else if (json11.status == 2) {
            swaMsg("error", json11.msg, "#a90228");
          }
        },
      });
    } else if ($(this).prop("checked") == false) {
      let inactive = $(this).data("checkid");
      let table_name = table_name_tb;
      let check_status = "check_status1";
      $.ajax({
        url: path_set,
        type: "POST",
        data: {
          inactive: inactive,
          table_name: table_name,
          data: check_status,
          type: type
        },
        success: function (data) {
          let json12 = JSON.parse(data);
          if (json12.status == 3) {
            swaMsg("success", json12.msg, "#0F843F");
          } else if (json12.status == 4) {
            swaMsg("error", json12.msg, "#a90228");
          }
        },
      });
    }
  });
}

function show_delete(del_id, this_btn, table_name) {
  swal({
    title: "Are you sure?",
    text: "want to delete this data",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let delete_table = "delete_table";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { del_id: del_id, table_name: table_name, data: delete_table },
        success: function (data) {
          console.log(data)
          let json7 = JSON.parse(data);
          if (json7.status == 1) {
            swaMsg("success", "Data deleted successfully.", "#0F843F");
           if ($(this_btn).hasClass("delete-menu")) {
              $(this_btn).closest(".dd-item").remove();
            }
            else {
              $(this_btn).closest("tr").remove();
            }
          } else if (json7.status == 2) {
            swaMsg("error", json7.msg, "#a90228");
          }
          else if (json7.status == 4) {
            swaMsg("error", 'Sorry! You can not delete Yourself', "#a90228");
          }
        },
      });
    } else {
    }
  });
}

function show_delete_permanently(del_id, this_btn, table_name) {
  swal({
    title: "Are you sure?",
    text: "want to delete this data permanently",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let delete_table_permanently = "delete_table_permanently";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { del_id: del_id, table_name: table_name, data: delete_table_permanently },
        success: function (data) {
          console.log(data)
          let json7 = JSON.parse(data);
          if (json7.status == 1) {
            swaMsg("success", "Data deleted successfully.", "#0F843F");
            if ($(this_btn).hasClass("delete-menu")) {
              $(this_btn).closest(".dd-item").remove();
            }
            else {
              $(this_btn).closest("tr").remove();
            }
          } else if (json7.status == 2) {
            swaMsg("error", json7.msg, "#a90228");
          }
          else if (json7.status == 4) {
            swaMsg("error", 'Sorry! You can not delete Yourself', "#a90228");
          }
        },
      });
    } else {
    }
  });
}

function delete_table(button_class, table_name_tb) {
  $(document).on("click", button_class, function (e) {
    e.preventDefault();
    let this_btn = $(this);
    let del_id = $(this).data("delid");
    let table_name = table_name_tb;
    // alert(del_id);
    show_delete(del_id, this_btn, table_name);
  });
}
function delete_table_permanently(button_class, table_name_tb) {
  $(document).on("click", button_class, function (e) {
    e.preventDefault();
    let this_btn = $(this);
    let del_id = $(this).data("delid");
    let table_name = table_name_tb;
    // alert(del_id);
    show_delete_permanently(del_id, this_btn, table_name);
  });
}
function show_recycle(del_id, this_btn, table_name) {
  swal({
    title: "Are you sure?",
    text: "Do you want to recover?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let delete_recycle = "delete_recycle";
      $.ajax({
        url: path_set,
        type: "POST",
        data: { del_id: del_id, table_name: table_name, data: delete_recycle },
        success: function (data) {
          console.log(data)
          let json7 = JSON.parse(data);
          if (json7.status == 1) {
            swaMsg("success", "Data recovered successfully.", "#0F843F");
            if ($(this_btn).hasClass("delete-banner") || $(this_btn).hasClass("delete-gallery")) {
              $(this_btn).closest(".col-md-3").remove();
            }
            else if ($(this_btn).hasClass("delete-faq")) {
              $(this_btn).closest(".col-md-12").remove();
            }
            else if ($(this_btn).hasClass("delete-blend") || $(this_btn).hasClass("delete-packagingcategory")) {
              $(this_btn).closest(".col-md-4").remove();
            }
            else if ($(this_btn).hasClass("delete-menu")) {
              $(this_btn).closest(".dd-item").remove();
            }
            else {
              $(this_btn).closest("tr").remove();
            }
          } else if (json7.status == 2) {
            swaMsg("error", json7.msg, "#a90228");
          }
          else if (json7.status == 4) {
            // swaMsg("error", 'Sorry! You can not delete Yourself', "#a90228");
          }
        },
      });
    } else {
    }
  });
}
function delete_recycle(button_class, table_name_tb) {
  $(document).on("click", button_class, function (e) {
    e.preventDefault();
    let this_btn = $(this);
    let del_id = $(this).data("delid");
    let table_name = table_name_tb;
    // alert(del_id);
    show_recycle(del_id, this_btn, table_name);
  });
}

function show_spinner(id) {
  $(id).addClass("form");
}

function hide_spinner(id) {
  $(id).removeClass("form");
}

function message(cls, msg) {
  swaMsg("error", msg, "#D30C0C");
  $(cls).text(`${msg}`);
  inval(cls, "#D30C0C");
}

function reload_data(cls, function_name) {
  $.ajax({
    url: path_set,
    type: "POST",
    data: { data: function_name },
    success: function (data) {
      $(cls).html(data);
    },
  });
}
// for table search
function filterTable(inputId, tableId) {
  var myInput = document.getElementById(inputId);
  var myTableRows = document.querySelectorAll(`${tableId} tr`);

  myInput.addEventListener("keyup", function () {
    var value = this.value.toLowerCase();
    myTableRows.forEach(function (row) {
      var rowText = row.textContent.toLowerCase();
      row.style.display = rowText.indexOf(value) > -1 ? "table-row" : "none";
    });
  });
}